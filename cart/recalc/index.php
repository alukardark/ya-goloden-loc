<?
$header_class="basket-header";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
?>

<?php
$arID = array();

$arBasketItems = array();

$dbBasketItems = CSaleBasket::GetList(
     array(
                "NAME" => "ASC",
                "ID" => "ASC"
             ),
     array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
             ),
     false,
     false,
     array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "PRODUCT_PROVIDER_CLASS")
             );
while ($arItems = $dbBasketItems->Fetch())
{
     if ('' != $arItems['PRODUCT_PROVIDER_CLASS'] || '' != $arItems["CALLBACK_FUNC"])
     {
          CSaleBasket::UpdatePrice($arItems["ID"],
                                 $arItems["CALLBACK_FUNC"],
                                 $arItems["MODULE"],
                                 $arItems["PRODUCT_ID"],
                                 $arItems["QUANTITY"],
                                 "N",
                                 $arItems["PRODUCT_PROVIDER_CLASS"]
                                 );
          $arID[] = $arItems["ID"];
     }
}
if (!empty($arID))
     {
     $dbBasketItems = CSaleBasket::GetList(
     array(
          "NAME" => "ASC",
          "ID" => "ASC"
          ),
     array(
        "ID" => $arID,
        "ORDER_ID" => "NULL"
          ),
        false,
        false,
        array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "PRODUCT_PROVIDER_CLASS", "NAME")
    );
    CModule::IncludeModule('catalog');
    CModule::IncludeModule('iblock');
    while ($arItem = $dbBasketItems->Fetch())
    {
        global $city,$cities,$USER;
        //$arItem["discount"] = CCatalogDiscount::GetDiscountByProduct($arItem["PRODUCT_ID"],$USER->GetUserGroupArray(),"N",1);
        $mxResult = CCatalogSku::GetProductInfo(
            $arItem["PRODUCT_ID"]
        );
        
        if (is_array($mxResult))
        {
            $elementId = $mxResult['ID'];
            $iblockId = $mxResult['IBLOCK_ID'];
            $arSkuInfo = CCatalogSKU::GetInfoByProductIBlock($iblockId);
            $arItem["OFFERS"] = array();
            $rres = CIBlockElement::GetList(
                            array(),
                            array('IBLOCK_ID' => $arSkuInfo['IBLOCK_ID'], '=PROPERTY_'.$arSkuInfo['SKU_PROPERTY_ID'] => $elementId),
                            false,
                            false,
                            array("ID","PROPERTY_CITY","CATALOG_GROUP_1")
                    );
            
            while($ob = $rres->GetNextElement()) {
                $offer = $ob->GetFields();
                $arItem["OFFERS"][ $offer["PROPERTY_CITY_VALUE"] ] = $offer;
            }
            if (count($arItem["OFFERS"])==0) {
                CSaleBasket::Update($arItem["ID"],array("DELAY" => "N"));
            } else {
                if (!$arItem["OFFERS"][ $cities[$city]["CODE"] ]["ID"]) {
                    CSaleBasket::Update($arItem["ID"],array("DELAY" => "Y"));
                } else if ($arItem["PRODUCT_ID"]!=$arItem["OFFERS"][ $cities[$city]["CODE"] ]["ID"]) {
                    $newOffer = $arItem["OFFERS"][ $cities[$city]["CODE"] ];
                    $offer_id = $newOffer["ID"];
                    $price = $newOffer["CATALOG_PRICE_1"];
                    CSaleBasket::Update($arItem["ID"],array(
                        "DELAY" => "N",
                        "PRODUCT_ID" => $offer_id,
                        "PRICE" => $price
                    ));
                            
                }
            }
        }
/*        $arItem["PRODUCT"] = CCatalogProduct::GetByID($arItem["PRODUCT_ID"]);
        $arOptPrices = CCatalogProduct::GetByIDEx($arItem["PRODUCT_ID"]);
        $price = $arOptPrices['PRICES'][$city]['PRICE'];        
        $catalog_group_id = $city;
        $arItem["discount"] = CCatalogDiscount::GetDiscountByProduct($arItem["PRODUCT_ID"],$USER->GetUserGroupArray(),"N",$catalog_group_id);
        $arItem["PRICES"] = $arOptPrices['PRICES'];*/
    
        $arBasketItems[] = $arItem;
    }
}
// Печатаем массив, содержащий актуальную на текущий момент корзину
echo "<pre>";
print_r($arBasketItems);
echo "</pre>";
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>