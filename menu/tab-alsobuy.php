<?php 
    require_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/utils.php");
    $arVariables = getCatalogVariables();
    if ($arVariables["ELEMENT_CODE"]) {    
        $arFilter = array(
            "IBLOCK_CODE" => 'menu',
            "SECTION_CODE" => $arVariables["SECTION_CODE"],
            "CODE" => $arVariables["ELEMENT_CODE"]
        );
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array("ID","IBLOCK_ID"));
        if($ob = $res->GetNextElement()){ 
            $element_id = $ob->fields["ID"];
            $APPLICATION->IncludeComponent("bitrix:sale.recommended.products", "menu", array(
                        "ID" => $element_id,
                        "IBLOCK_ID" => 2,
                        "MIN_BUYES" => 1,
                        "ELEMENT_COUNT" => 50,
                        "DETAIL_URL" => "",
                        "BASKET_URL" => "/cart/",
                        "ACTION_VARIABLE" => "action",
                        "CACHE_TYPE" => "N",
                        "CACHE_TIME" => "36000000",
                        "PRICE_CODE" => array(
                                0 => "BASE",
                        ),
                        //"CATALOG_PRICES" => array(array( "SELECT" => "CATALOG_GROUP_1", "CAN_VIEW" => 1, "CAN_BUY" => 1)),
                        "USE_PRICE_COUNT" => "N",
                        "SHOW_PRICE_COUNT" => "1",
                        "PRICE_VAT_INCLUDE" => "N",
                        "CONVERT_CURRENCY" => "N",
                        "CURRENCY_ID" => "RUB",
                        "HIDE_NOT_AVAILABLE" => "N",
                        "SHOW_PRODUCTS_2" => "Y",
                        "SHOW_PRODUCTS_3" => "Y",
                        "PROPERTY_CODE_3" => array("CITY")
                    ),
                    $component
            );
        }
    }?>