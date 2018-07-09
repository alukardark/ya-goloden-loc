<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
SetGlobalVars();

function deleteBasketItem($basketItemId) {
    
}

$basketItemId = (int)$_REQUEST["BASKET_ITEM_ID"];
$cid = $_REQUEST["CID"];
if ($cid) {
     $dbBasketItems = CSaleBasket::GetList(
     array(
          "NAME" => "ASC",
          "ID" => "ASC"
          ),
     array(
        "ORDER_ID" => "NULL"
          ),
        false,
        false,
        array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "PRODUCT_PROVIDER_CLASS", "NAME")
    );
    while ($arItem = $dbBasketItems->Fetch())
    {
        $db_res = CSaleBasket::GetPropsList(
                array(
                        "SORT" => "ASC",
                        "NAME" => "ASC"
                    ),
                array("BASKET_ID" => $arItem["ID"],"CODE" => "cid")
        );
        $props = array();

        while ($ar_res = $db_res->Fetch())
        {
           $props[$ar_res["CODE"]] = $ar_res;
        }
        
        if ($props['cid']["VALUE"]==$cid) {
            CSaleBasket::Update($arItem["ID"],array(
                "SUBSCRIBE" => "N",
                "DELAY" => "N"
            ));
        }
    }
    
} else if ($basketItemId>0) {
    $dbBasketItems = CSaleBasket::GetList(
         array(
                    "NAME" => "ASC",
                    "ID" => "ASC"
                 ),
         array(
                    "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                    "LID" => SITE_ID,
                    //"ORDER_ID" => "NULL",
                    //"SUBSCRIBE" => "Y",
                    "ID" => $basketItemId
                 ),
         false,
         false,
         array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "PRODUCT_PROVIDER_CLASS")
    );
    $result = array();
    $a = $row = $dbBasketItems->Fetch();
//    var_dump(CSaleBasket::GetBasketUserID());
//    var_dump($a);
    if ($a) {
        CSaleBasket::Update($basketItemId,array(
            "SUBSCRIBE" => "N",
            "DELAY" => "N"
        ));
    }
}
echo "OK";

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>