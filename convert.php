<?
$body_class = "index";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
set_time_limit(0);
    global $arrFilter,$city,$cities;
?>
                <section>
                    <div class="center-col">
                        <div class="main-col">
                            <?php 
                            Cmodule::IncludeModule('catalog');
                            $arSelect = Array("ID", "IBLOCK_ID", "NAME","DETAIL_TEXT","PROPERTY_TYPE_REF");
                            $arFilter = Array("IBLOCK_ID"=>2, "ACTIVE"=>"Y");
                            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                            while($ob = $res->GetNextElement())
                            {
                                $arFields = $ob->GetFields();
                                
$db_props = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], array("sort" => "asc"), Array("CODE"=>"TYPE_REF"));
$vals = array();
   while ($ob = $db_props->GetNext())
    {
        $vals[] = array("VALUE" => $ob['VALUE'],"DESCRIPTION" => "");
    }
                                
                                $cont_items = CIBlockElement::GetList(
                                    array("SORT" => "ASC"),
                                    array(
                                            "IBLOCK_ID" => 3,
                                            "PROPERTY_CML2_LINK" => $arFields[ID]
                                            ),
                                    false,false,
                                    array("ID", "IBLOCK_ID", "NAME", "DETAIL_TEXT","PROPERTY_TYPE_REF","PROPERTY_CITY")
                                );
                                while($row = $cont_items->GetNext()) {
                                    $el = new CIBlockElement;
                                    $el->Update(
                                            $row["ID"],
                                            array(
                                                'TIMESTAMP_X' => FALSE,
                                                'DETAIL_TEXT' => $arFields["DETAIL_TEXT"],
                                                'DETAIL_TEXT_TYPE' => 'html'
                                            )
                                    );
                                    CIBlockElement::SetPropertyValueCode($row["ID"],"TYPE_REF",$vals);
                                }
                                //die("!!!");

                                //die;
                            }                            
                            ?>
                        </div>
                    </div>
                </section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>