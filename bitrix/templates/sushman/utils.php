<?php

function getCatalogVariables() {
    $arVariables = array();

    $arParams = array(
            'SEF_URL_TEMPLATES' => array(
                    'section' => '#SECTION_CODE#/',
        'element' => '#SECTION_CODE#/#ELEMENT_CODE#/'
            ),
            'SEF_FOLDER' => '/menu/'
    );

    //шаблоны по умолчанию
    $arDefaultUrlTemplates404 = array(
            "sections" => "",
            "section" => "#SECTION_CODE#/",
            "element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
            "compare" => "compare.php?action=COMPARE",
    );

    $engine = new CComponentEngine($this);
    if (CModule::IncludeModule('iblock'))
    {
            $engine->addGreedyPart("#SECTION_CODE_PATH#");
            $engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
    }
    $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);

    $componentPage = $engine->guessComponentPath(
            $arParams["SEF_FOLDER"],
            $arUrlTemplates,
            $arVariables
    );
    return $arVariables;
}

function getRecommendsList($arVariables) {
    $section_id = $arVariables["SECTION_CODE"];
    $element_id = $arVariables["ELEMENT_CODE"];
    $arSelect = Array("ID","IBLOCK_ID", "NAME","SORT","PREVIEW_PICTURE","DETAIL_PAGE_URL", "PROPERTY_ARTNUMBER", "CATALOG_MEASURE_RATIO", "CATALOG_WEIGHT","CATALOG_GROUP_1" );
    $subFilter = array(
          "IBLOCK_CODE" => 'menu',
          "INCLUDE_SUBSECTIONS" => "Y",
        "ACTIVE" => 'Y'
        );
    if ($section_id)
      $subFilter["SECTION_CODE"] = $section_id;
    if ($element_id)
      $subFilter["CODE"] = $element_id;
    
    $arFilter = array(
        "IBLOCK_CODE" => 'menu',
        "ACTIVE" => 'Y',
        "ID" => CIBlockElement::SubQuery("PROPERTY_RECOMMEND", $subFilter)    
    );
    return CIBlockElement::GetList(Array("RAND" => "ASC"), $arFilter, false, Array(), $arSelect);
}
