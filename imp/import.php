<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?php
        if (!CModule::IncludeModule('iblock')) {die('API-инфоблоков не доступны');} 
        if (!CModule::IncludeModule('catalog')) {die('API-инфоблоков не доступны');} 
        if (!CModule::IncludeModule('sale')) {die('API-инфоблоков не доступны');} 

function addItems($cur_cat,$items) {
    echo "ADD ITEMS";
    $res = CIBlockSection::GetList(array(),array("IBLOCK_ID"  => "2", "NAME" => $cur_cat));
    if ($row = $res->GetNext()) {
        foreach($items as $i => $item) {
            foreach($item as $k => $v) {
                if ($k>0)
                    $item[$k] = round((float)str_replace(",",".",$v));
            }
            
            $desc = <<<EOF
                    <div class="autogen">
                    <h4>Пищевая ценность</h4>
                    <table>
                        <tr>
                            <th></th>
                            <th style="text-align:right;">В 100г</th>
                            <th style="text-align:right;">Порция</th>
                        </tr>
                        <tr>
                            <td>Белки</td>
                            <td style="text-align:right;">{$item[7]}</td>
                            <td style="text-align:right;">{$item[2]}</td>
                        </tr>
                        <tr>
                            <td>Жиры</td>
                            <td style="text-align:right;">{$item[8]}</td>
                            <td style="text-align:right;">{$item[3]}</td>
                        </tr>
                        <tr>
                            <td>Углеводы</td>
                            <td style="text-align:right;">{$item[9]}</td>
                            <td style="text-align:right;">{$item[4]}</td>
                        </tr>
                        <tr>
                            <td>Калории</td>
                            <td style="text-align:right;">{$item[6]}</td>
                            <td style="text-align:right;">{$item[1]}</td>
                        </tr>
                    </table>
                    </div>
EOF;
                            
            $res = CIBlockElement::GetList(array(),array("IBLOCK_ID" => 2,"CODE" => Cutil::translit($item[0],"ru"),"IBLOCK_SECTION_ID" => $row["ID"]));
            if ($elem = $res->GetNext()) {
                $elem["DETAIL_TEXT"] = preg_replace('/<div class="autogen">(.+)<\/div>/Usi','',$elem["DETAIL_TEXT"]);
                $fields = array(
                   'MODIFIED_BY' => $GLOBALS['USER']->GetID(),
                   "ACTIVE"         => "Y",
                   "IBLOCK_ID"      => 2, 
                   "IBLOCK_SECTION_ID" => $row["ID"],
                   "NAME"  => $item[0],
                   "DETAIL_TEXT" => $elem["DETAIL_TEXT"].$desc,
                    "DETAIL_TEXT_TYPE" => 'html'
                );
                $el = new CIBlockElement;
                $el->Update($elem["ID"],$fields);
                $id = $elem["ID"];
                
            } else {
                $fields = array(
                   'MODIFIED_BY' => $GLOBALS['USER']->GetID(),
                   "ACTIVE"         => "Y",
                    "CODE" => Cutil::translit($item[0],"ru"),
                   "IBLOCK_ID"      => 2, 
                   "IBLOCK_SECTION_ID" => $row["ID"],
                   "NAME"  => $item[0],
                   "CATALOG_WEIGHT" => $item[5],
                   "SORT" => ($i+1)*100,
                   "DETAIL_TEXT" => $desc,
                    "DETAIL_TEXT_TYPE" => 'html'
                        );
                $el = new CIBlockElement;
                $id = $el->Add($fields);
                if (!$id) {
                    echo "<br/>ERROR ADDING: {$item[0]}";
                }
                CCatalogProduct::Add(array(
                    "ID" => $id,
                    "CAN_BUY_ZERO" => "Y",
                    "WEIGHT" => $item[5],
                ));
                CPrice::SetBasePrice($id,150,"RUB");
            }
        }
    } else
        die("No such cat: {$cur_cat}");
    
}

$data = file("./bbb.csv");
$is_start = false;
$cur_cat = "";
$cur_items = array();
foreach($data as $s) {
    $ss = explode(";",$s);
    if (!$ss[0])
        continue;
    if ($is_start) {
        if ($cur_cat && count($cur_items)>0) {
            addItems($cur_cat,$cur_items);
            $cur_items = array();
        }
        $cur_cat = $ss[0];
        $is_start = false;
        continue;
    }
    if ($ss[0]=="Наименование блюда") {
        $is_start = true;
        continue;
    }
    $cur_items[] = $ss;
}
if ($cur_cat && count($cur_items)>0) {
    addItems($cur_cat,$cur_items);
}

echo "<br/><br/>".$cur_cat;
