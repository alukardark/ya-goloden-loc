<?php
$header_class = "basket-header";
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
global $city, $cities;
recalcBasket();
if ($cities[$city]["ORDER_ENABLED"] == 1) {

    if ((int)$_GET["ORDER_ID"] == 0) {
        ?><? $APPLICATION->IncludeComponent(
            "bitrix:sale.basket.basket",
            "basket",
            array(
                "COUNT_DISCOUNT_4_ALL_QUANTITY" => "Y",
                "COLUMNS_LIST" => array(
                    0 => "NAME",
                    1 => "WEIGHT",
                    2 => "DELETE",
                    3 => "QUANTITY",
                    4 => "SUM",
                    5 => "PROPERTY_ARTNUMBER",
                    6 => "PROPERTY_CUSTOM_WEIGHT",
                ),
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "PATH_TO_ORDER" => "/cart/order/",
                "HIDE_COUPON" => "Y",
                "QUANTITY_FLOAT" => "N",
                "PRICE_VAT_SHOW_VALUE" => "N",
                "SET_TITLE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "OFFERS_PROPS" => array(),
                "USE_PREPAYMENT" => "N",
                "ACTION_VARIABLE" => "action"
            ),
            false
        );
    } else {
        $APPLICATION->SetTitle("Оформление заказа");
    }
    ?>
    <?
    global $is_order_time;

    $notWorkingText = is_not_order_time();

    if (!$notWorkingText or $_SERVER['REMOTE_ADDR'] == '158.46.22.198' or $_SERVER['REMOTE_ADDR'] == '46.149.225.204') {
        $APPLICATION->IncludeComponent(
            "bitrix:sale.order.ajax",
            "sushman",
            array(
                "PAY_FROM_ACCOUNT" => "N",
                "COUNT_DELIVERY_TAX" => "N",
                "COUNT_DISCOUNT_4_ALL_QUANTITY" => "Y",
                "ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
                "ALLOW_AUTO_REGISTER" => "Y",
                "SEND_NEW_USER_NOTIFY" => "N",
                "DELIVERY_NO_AJAX" => "Y",
                "TEMPLATE_LOCATION" => ".default",
                "PROP_1" => array(),
                "PATH_TO_PERSONAL" => "/order/",
                "PATH_TO_PAYMENT" => "/order/payment/",
                "PATH_TO_ORDER" => "/cart/order/",
                "SET_TITLE" => "N",
                "DELIVERY_TO_PAYSYSTEM" => "p2d",
                "DISABLE_BASKET_REDIRECT" => "Y",
                "SHOW_ACCOUNT_NUMBER" => "N",
                "DELIVERY_NO_SESSION" => "N",
                "USE_PREPAYMENT" => "N",
                "ALLOW_NEW_PROFILE" => "Y",
                "SHOW_PAYMENT_SERVICES_NAMES" => "Y",
                "SHOW_STORES_IMAGES" => "N",
                "PATH_TO_BASKET" => "basket.php",
                "PATH_TO_AUTH" => "/auth/",
                "PRODUCT_COLUMNS" => array()
            ),
            false
        );
    } else {

        echo '<p>' . $notWorkingText . '</p>';
    }
} else { ?>
    <p>Корзина временно не работает.</p>
<? } ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>