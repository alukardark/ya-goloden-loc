<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?php

global $base_url,$iblock_id,$offers_iblock_id,$city_id,$cities;
$base_url = "http://158.46.252.166:1860/FastOperator.asmx";

CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');
CModule::IncludeModule('sale');

    CModule::IncludeModule('highloadblock');    
    //use Bitrix\Highloadblock as HL;
    //use Bitrix\Main\Entity;
    $hlblock_id = 4;
    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $main_query = new Bitrix\Main\Entity\Query($entity);
    $main_query->setSelect(array('ID','UF_XML_ID','UF_NAME','UF_ACTIVE','UF_DELIVERY_PIC','UF_DELIVERY','UF_ORDER_ENABLED','UF_SMS_PHONE'));
    $result = $main_query->exec();
    $result = new CDBResult($result);
    $cities = array();
    while ($row = $result->Fetch())
    {
        $cities[$row["UF_XML_ID"]] = array("ID" => $row["ID"],"NAME" => $row["UF_NAME"]);
    }

$city_id = 1;
$iblock_id = 2;
$offers_iblock_id = 3;


function getSiteCats() {
    global $iblock_id;
    $cats = array();
    $arFilter = array('IBLOCK_ID' => $iblock_id, 'ACTIVE' => 'Y');
    $rsSections = CIBlockSection::GetList(array(), $arFilter);
    while ($arSection = $rsSections->Fetch())
    {
        $cat = array(
            "id" => $arSection["ID"],
            "title" => $arSection["NAME"],
            "code" => $arSection["CODE"],
        );
        $rr = CIBlockElement::GetList(array(),array("IBLOCK_ID" => $iblock_id,"SECTION_ID" => $cat["id"],"INCLUDE_SUBSECTIONS" => $cat['code']=='wok' ? 'Y' : "N"));
        $items = array();
        while ($arItem = $rr->Fetch())
        {
            $items[] = array(
                "id" => $arItem["ID"],
                "title" => $arItem["NAME"],
                "code" => $arItem["CODE"],
            );
        }
        if (count($items)>0) {
            $cat['items'] = $items;
        }
                
        $cats[] = $cat;
        
    }
    return $cats;
}

function parseItems($root) {
    $items = array();
    foreach($root->childNodes as $n) {
        if ($n->tagName=="Items") {
            foreach($n->childNodes as $node) {
               if ($node->tagName=="Item") {
                   $items[] = array(
                        "title" => $node->getAttribute("Name"),
                        "code" => $node->getAttribute("Code"),
                        "sort" => $node->getAttribute("Sort"),
                        "price" => $node->getAttribute("Price"),
                        "weight" => $node->getAttribute("Weight")
                   );
               }
            }

        }
    }
    return $items;
}

function parseCats($root) {
    $cats = array();
    foreach($root->childNodes as $node) {
        if ($node->tagName=="Category") {
            $cat = array(
                "title" => $node->getAttribute("Name"),
                "code" => $node->getAttribute("Code"),
                "sort" => $node->getAttribute("Sort")
                    
            );
            $childs = parseCats($node);
            if (count($childs)>0) {
                $cat['childs'] = $childs;
            }
            $cat['items'] = parseItems($node);
            $cats[] = $cat;
        }
    }
    return $cats;
}

function getMenu($city_id) {
    global $base_url;
    $url = $base_url."/GetMenu?Brand=0&Area=1";
    $doc = new DOMDocument();
    $doc->load($url);
    $doc->loadXML($doc->textContent);
    $root = $doc->documentElement;
    $cats = parseCats($root);
    return $cats;
}

function foundCat($site,$title) {
    $title = str_replace("Горячие блюда", "Горячее",$title);
    $title = str_replace("Лапша WOK", "Вок",$title);
    $title = str_replace("Запеченые роллы", "Запеченные роллы",$title);
    $title = str_replace("Черные роллы", "Жареные роллы",$title);
    $title = str_replace("Соуса", "Соусы",$title);
    
    $title = trim(mb_strtoupper($title));
    foreach($site as $cat) {
        $site_title = trim(preg_replace("/[\d\+]/Usi","",mb_strtoupper($cat['title'])));
        if ($site_title==$title) {
            return $cat;
        }
    }
    return false;
}


function foundItem($cat,$title) {
    $title = preg_replace('/  /Usi'," ",$title);
    $title = str_replace('Бургер "', "",$title);
    $title = str_replace(" Бургер", "",$title);
    $title = preg_replace('/[\d+"]/Usi',"",$title);
    $title = trim(mb_strtoupper($title));
    foreach($cat['items'] as $item) {
        $site_title = trim(preg_replace('/[\d+]/Usi',"",mb_strtoupper($item['title'])));
        if ($site_title==$title) {
            return $item;
        }
    }
    return false;
}

$section_foid_field_id = 0;
$rsData = CUserTypeEntity::GetList( array(), array("ENTITY_ID" => "IBLOCK_".$iblock_id."_SECTION","FIELD_NAME" => "UF_SECTION_FOID") );
if($arRes = $rsData->Fetch())
{
    $section_foid_field_id = $arRes["ID"];
}

function first_update_site_foids() {
    global $USER_FIELD_MANAGER,$base_url,$iblock_id,$offers_iblock_id,$city_id,$cities;
    $site = getSiteCats();
    $fo = getMenu(1);
    foreach($fo as $focat) {
        $cat_title = $focat['title'];
        echo "<h3>$cat_title</h3>";
        $found_site_cat = foundCat($site,$cat_title);
        if ($found_site_cat) {
            $found_cat_id = $found_site_cat["id"];
            echo "!!! section_id: $found_cat_id";
            $USER_FIELD_MANAGER->Update( 'IBLOCK_'.$iblock_id.'_SECTION', $found_cat_id, array(
                'UF_SECTION_FOID'  => $focat['code']
            ) );
            echo "<br/>";
            foreach($focat['items'] as $foitem) {
                echo "{$foitem['title']}";
                $found_site_item = foundItem($found_site_cat,$foitem['title']);
                if ($found_site_item) {
                    $found_item_id = $found_site_item["id"];
                    echo " - $found_item_id";
                    $rres = CIBlockElement::GetList(
                                    array(),
                                    array('IBLOCK_ID' => $offers_iblock_id, '=PROPERTY_CML2_LINK' => $found_item_id),
                                    false,
                                    false,
                                    array("ID","PROPERTY_CITY","CATALOG_GROUP_1")
                            );
                    $offers = array();
                    while($ob = $rres->GetNextElement()) {
                        $offer = $ob->GetFields();
                        $offers[ $cities[$offer["PROPERTY_CITY_VALUE"]]["ID"] ] = $offer["ID"];
                    }            
                    $offer_id = $offers[$city_id];
                    if ($offer_id>0) {
                        CIBlockElement::SetPropertyValues($offer_id, $offers_iblock_id, $foitem['code'], "FOID");
                    }

                }
                echo "<br/>";
            }
        }
        echo "<br/>";
    }
}


first_update_site_foids();


?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>