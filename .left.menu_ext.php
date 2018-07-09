<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
 
global $APPLICATION,$cities,$city;
 
if(CModule::IncludeModule("iblock")) {
 
    $arOrder = Array("SORT"=>"ASC");
    $arSelect = Array("ID", "NAME", "CODE", "IBLOCK_ID", "DETAIL_PAGE_URL","PICTURE","ELEMENT_CNT","UF_HIDE_CITIES");
    $arFilter = Array("IBLOCK_CODE"=> "menu","IBLOCK_ID" => 2, "ACTIVE"=>"Y","DEPTH_LEVEL" => 1,'CNT_ACTIVE'=>true);
    $res = CIBlockSection::GetList($arOrder, $arFilter, array("CNT_ACTIVE" => "Y","CNT_ALL" => "N"), $arSelect);
    $i=0;
    while($arFields = $res->GetNext()) // наполняем массив меню пунктами меню
    {
        $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arFields["IBLOCK_ID"], $arFields["ID"]);
        $vals = $ipropValues->getValues();
        foreach($vals as $key=>$value) {
            $value = str_replace("#CITY#",$cities[$city]["NAME"],$value);
            $value = str_replace("#CITY2#",$cities[$city]["NAME"]."е",$value);
            $value = str_replace("#LCCITY#",  strtolower($cities[$city]["NAME"]),$value);
            $value = str_replace("#LCCITY2#",strtolower($cities[$city]["NAME"]."е"),$value);
            $vals[$key]=$value;
        }
        if ($arFields['ELEMENT_CNT']==0)
            continue;
        if (is_array($arFields["UF_HIDE_CITIES"]) && in_array($cities[$city]["CODE"],$arFields["UF_HIDE_CITIES"])) {
            continue;
        }
        $pic = "";
        if ($arFields['PICTURE']) {
            $arPhoto = CFile::GetFileArray($arFields['PICTURE']);
            $pic = CFile::ResizeImageGet($arPhoto,array("width" => 234,"height" => 226),BX_RESIZE_IMAGE_EXACT );
        }
    
        $add = $i==0 ? array("/menu/") : array();
        $aMenuLinksExt[] = Array(
            $arFields['NAME'],
            "/menu/{$arFields['CODE']}/",
            $add,
            Array("CODE" => $arFields['CODE'], "IMAGE" => $pic["src"],"alt" => $vals["SECTION_PICTURE_FILE_ALT"],"title" => $vals["SECTION_PICTURE_FILE_TITLE"]),
            ""
        );
        $i++;
    }   
}
 
$aMenuLinks = array_merge($aMenuLinksExt, $aMenuLinks); // меню сформировано
?>