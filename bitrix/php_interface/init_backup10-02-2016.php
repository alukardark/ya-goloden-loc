<?php
/*
if (isset($_SERVER['HTTPS'])) {
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: http://www.ya-goloden.ru/");
  die;
}
*/

define('SUSHMAN_MAIN_PATH', '/bitrix/templates/sushman');
global $wok_section_ids, $pizza_section_ids, $ignored_ids, $hidden_sections;
$wok_section_ids = array(36, 37, 47, 48);
$pizza_section_ids = array(33, 49, 58, 64);
$ignored_ids = array_merge($wok_section_ids, $pizza_section_ids);
$hidden_sections = array(65, 66);

$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (stripos($user_agent, 'MSIE 6.0') !== false && stripos($user_agent, 'MSIE 8.0') === false && stripos($user_agent,
        'MSIE 7.0') === false
) {
    header("Location: /ie67/ie6.html");
}
if (stripos($user_agent, 'MSIE 6.0') === false && stripos($user_agent, 'MSIE 8.0') === false && stripos($user_agent,
        'MSIE 7.0') !== false
) {
    header("Location: /ie67/ie7.html");
}
if (stripos($user_agent, 'MSIE 6.0') === false && stripos($user_agent, 'MSIE 8.0') !== false && stripos($user_agent,
        'MSIE 7.0') === false
) {
    header("Location: /ie67/ie8.html");
}

if (defined('ADMIN_SECTION')) {

    $APPLICATION->SetAdditionalCSS(SUSHMAN_MAIN_PATH . '/admin.css');
}

function is_not_order_time()
{
    global $city, $cities;

    $t = getdate();

    $res = CIBlockElement::GetList(
        Array("SORT" => "ASC"),
        Array(
            "IBLOCK_CODE" => "days_off",
            "ACTIVE_DATE" => "Y",
            "!DATE_ACTIVE_FROM" => false,
            "!DATE_ACTIVE_TO" => false,
            'PROPERTY_DAYOFF_CITIES' => $cities[$city]["CODE"]
        ),
        false,
        false,
        Array("ID", "NAME")
    );

    if (intval($res->SelectedRowsCount()) > 0) {

        $arElem = $res->Fetch();
        return $arElem['NAME'];
    }

    $text = 'Пн-Пт: с 10-00 до 24-00 (заказы принимаются до 23-30)<br/>Сб-Вс: с 10-00 до 01-00 (заказы принимаются до 00-30)';

    if ($t['wday'] >= 0 && $t['wday'] <= 4) {
        return !(($t['hours'] >= 10 && ($t['hours'] < 23 || ($t['hours'] == 23 && $t['minutes'] <= 30))) || ($t['wday'] == 0 && ($t['hours'] == 0 && $t['minutes'] <= 30))) ? $text : false;
    } else {
        return !($t['hours'] >= 10 || ($t['wday'] == 6 && $t['hours'] == 0 && $t['minutes'] <= 30)) ? $text : false;
    }

    return $text;
}

global $APPLICATION;
if ('/cart/' == $APPLICATION->GetCurDir()) // урл страницы оформления заказа
{
    if ((!isset($_POST['ORDER_PROP_2']) || empty($_POST['ORDER_PROP_2']))) {
        $_POST['ORDER_PROP_2'] = time() . "@default-email.ru";
    }

    $hide_address = false;
    $order_discount_id = (int)$_REQUEST['ORDER_PROP_9'];
    if ($order_discount_id > 0) {
        CModule::IncludeModule('highloadblock');
        $hlblock_id = 6;
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $main_query = new Bitrix\Main\Entity\Query($entity);
        $main_query->setFilter(array('ID' => $order_discount_id));
        $main_query->setSelect(array('*'));
        $result = $main_query->exec();
        $result = new CDBResult($result);
        $prc = 0;
        if ($row = $result->Fetch()) {
            if (strpos("Самовывоз", $row["UF_NAME"]) !== false) {
                $hide_address = true;
            }
        }
    }
    if ($hide_address) {
        if ((!isset($_POST['ORDER_PROP_8']) || empty($_POST['ORDER_PROP_8']))) {
            $_POST['ORDER_PROP_8'] = 'Самовывоз';
        }
        if ((!isset($_POST['ORDER_PROP_10']) || empty($_POST['ORDER_PROP_10']))) {
            $_POST['ORDER_PROP_10'] = 'Самовывоз';
        }
        if ((!isset($_POST['ORDER_PROP_11']) || empty($_POST['ORDER_PROP_11']))) {
            $_POST['ORDER_PROP_11'] = '-';
        }
    }

}

function check_captcha($key, $arrValues)
{
    $captcha = explode('/', $_SESSION["form_" . $key]);
    return (count($captcha) == 2 && $arrValues['txt_' . $captcha[0]] == $captcha[1]);
}


function my_onBeforeResultAdd($WEB_FORM_ID, $arFields, $arrValues)
{
    global $APPLICATION;
    // действие обработчика распространяется только на форму с ID=1
    if (in_array($WEB_FORM_ID, array(1))) {
        if (!check_captcha($WEB_FORM_ID, $arrValues)) {
            $APPLICATION->ThrowException('Произошла ошибка во время обработки формы, попробуйте ещё раз');
        }
    }
}

AddEventHandler('form', 'onBeforeResultUpdate', 'my_onBeforeResultAdd');

//-- Добавление обработчика события

AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("MyClass", "OnAfterIBlockElementAddHandler"));

class MyClass
{
    // создаем обработчик события "OnAfterIBlockElementAdd"
    function OnAfterIBlockElementAddHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == 2) {
            $rres = CIBlockElement::GetList(
                array(),
                array('IBLOCK_ID' => 3, '=PROPERTY_CML2_LINK' => $arFields["ID"]),
                false,
                false,
                array("ID", "PROPERTY_CITY", "CATALOG_GROUP_1")
            );
            if (!$rres->GetNextElement()) {

                Cmodule::IncludeModule('catalog');


                $base_price = $_POST["CAT_BASE_PRICE"];
                CModule::IncludeModule('highloadblock');
                $hlblock_id = 4;
                $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
                $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                $main_query = new Bitrix\Main\Entity\Query($entity);
                $main_query->setSelect(array(
                    'ID',
                    'UF_XML_ID',
                    'UF_NAME',
                    'UF_ACTIVE',
                    'UF_DELIVERY_PIC',
                    'UF_DELIVERY',
                    'UF_TIME_DELIVERY'
                ));
                $result = $main_query->exec();
                $result = new CDBResult($result);
                $cities = array();
                while ($row = $result->Fetch()) {

                    if ($_GET[d]) {
                        var_dump($row);
                        die;
                    }

                    $cities[$row["ID"]] = array(
                        "CODE" => $row["UF_XML_ID"],
                        "NAME" => $row["UF_NAME"],
                        'ACTIVE' => $row['UF_ACTIVE'],
                        "PHONE" => $row['UF_PHONE'],
                        "DELIVERY_PIC" => $row['UF_DELIVERY_PIC'],
                        "DELIVERY" => $row['UF_DELIVERY'],
                        "TIME_DELIVERY" => $row['UF_TIME_DELIVERY']
                    );
                }
                foreach ($cities as $c) {
                    $offer = new CIBlockElement();
                    $offer_id = $offer->Add(array(
                        "IBLOCK_ID" => 3,
                        'NAME' => $arFields["NAME"],
                        'ACTIVE' => 'Y',
                        'PROPERTY_VALUES' => array(
                            "CML2_LINK" => $arFields["ID"],
                            "CITY" => $c["CODE"]
                        ),
                    ), false, false, false);
                    CCatalogProduct::Add(array(
                        "ID" => $offer_id,
                        "MEASURE" => $_POST["CAT_MEASURE"],
                        "WEIGHT" => $_POST["CAT_BASE_WEIGHT"]
                    ), false);
                    CPrice::SetBasePrice($offer_id, $base_price, "RUB");
                }
            }
        }
    }
}

AddEventHandler("sale", "OnOrderNewSendEmail", "bxModifySaleMails");
AddEventHandler("sale", "OnSaleCalculateOrder", "bxSaleCalculateOrder");

function getNonDiscountSections()
{
    $res = CIBlockSection::GetList(Array("SORT" => "ASC"),
        Array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "!UF_NOT_DISCOUNT" => ""), false, Array("ID", "UF_NOT_DISCOUNT"));
    $sections = array();
    while ($arSection = $res->GetNext()) {
        $sections[] = $arSection["ID"];
    }
    return $sections;
}

function bxSaleCalculateOrder(&$arOrder)
{
    $order_discount_id = (int)$arOrder['ORDER_PROP'][9];
    CModule::IncludeModule('highloadblock');
    $hlblock_id = 6;
    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $main_query = new Bitrix\Main\Entity\Query($entity);
    $main_query->setFilter(array('ID' => $order_discount_id));
    $main_query->setSelect(array('*'));
    $result = $main_query->exec();
    $result = new CDBResult($result);
    $prc = 0;
    if ($row = $result->Fetch()) {
        $prc = (int)$row["UF_DISCOUNT"] / 100;
    }
    $sum = 0;
    $non_discount = getNonDiscountSections();
    foreach ($arOrder["BASKET_ITEMS"] as $arItem) {
        if ($arItem["CAN_BUY"] == "Y" && $arItem["DELAY"] == "N") {
            $mxResult = CCatalogSku::GetProductInfo(
                $arItem["PRODUCT_ID"]
            );

            if (is_array($mxResult)) {
                $rres = CIBlockElement::GetList(
                    array(),
                    array('IBLOCK_ID' => $mxResult['IBLOCK_ID'], 'ID' => $mxResult['ID']),
                    false,
                    false,
                    array()
                );

                if ($ob = $rres->GetNextElement()) {
                    $info = $ob->GetFields();
                    if (in_array($info['IBLOCK_SECTION_ID'], $non_discount)) {
                        continue;
                    }
                }
            }
            $sum += ($arItem["PRICE"] * $arItem["QUANTITY"]);
        }
    }
    $arOrder["DISCOUNT_PRICE"] = round(($prc) * $sum);
}

function bxModifySaleMails($orderID, &$eventName, &$arFields)
{
    CSaleOrder::DeliverOrder($orderID, "Y");

    global $city, $cities;
    $eventName .= "_" . strtoupper($cities[$city]["CODE"]);
    $arOrder = CSaleOrder::GetByID($orderID);

    //-- получаем телефоны и адрес
    $order_props = CSaleOrderPropsValue::GetOrderProps($orderID);


    $arBasketList = array();
    $dbBasketItems = CSaleBasket::GetList(
        array("ID" => "ASC"),
        array("ORDER_ID" => $orderID, "CAN_BUY" => "Y", "DELAY" => "N", "SUBSCRIBE" => "N"),
        false,
        false,
        array("ID", "PRODUCT_ID", "NAME", "QUANTITY", "PRICE", "CURRENCY", "TYPE", "SET_PARENT_ID")
    );

    while ($arItem = $dbBasketItems->Fetch()) {
        if (CSaleBasketHelper::isSetItem($arItem)) {
            continue;
        }

        $db_res = CSaleBasket::GetPropsList(
            array(
                "SORT" => "ASC",
                "NAME" => "ASC"
            ),
            array("BASKET_ID" => $arItem["ID"], "CODE" => array("cid", "cid_type"))
        );
        $props = array();

        while ($ar_res = $db_res->Fetch()) {
            $props[$ar_res["CODE"]] = $ar_res;
        }
        $arItem["props"] = $props;

        $arBasketList[] = $arItem;
    }

    $strOrderList = "";
    $cids = array();
    foreach ($arBasketList as $arItem) {
        if (!$arItem['props']["cid"]["VALUE"]) {
            continue;
        }
        $cids[$arItem['props']["cid"]["VALUE"]] = $arItem['props']["cid_type"]["VALUE"];
    }
    foreach ($arBasketList as $arItem) {
        if ($arItem['props']["cid"]["VALUE"]) {
            continue;
        }
        $strOrderList .= $arItem["NAME"] . " - " . $arItem["QUANTITY"] . " " . $measureText . ": " . SaleFormatCurrency($arItem["PRICE"],
                $arItem["CURRENCY"]);
        //$strOrderList .= "CID: ".$arItem['props']["cid"]["VALUE"]." CID_TYPE:".$arItem['props']["cid_type"]["VALUE"];
        $strOrderList .= "<br/>";
    }
    foreach ($cids as $cid => $cid_type) {

        $strOrderList .= ($cid_type == 'wok' ? 'Вок' : 'Половинки пицц') . ":<br/>";
        foreach ($arBasketList as $arItem) {
            if ($arItem['props']["cid"]["VALUE"] == $cid) {
                $strOrderList .= "&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;" . $arItem["NAME"] . " - " . $arItem["QUANTITY"] . " " . $measureText . ": " . SaleFormatCurrency($arItem["PRICE"],
                        $arItem["CURRENCY"]);
                //$strOrderList .= "CID: ".$arItem['props']["cid"]["VALUE"]." CID_TYPE:".$arItem['props']["cid_type"]["VALUE"];
                $strOrderList .= "<br/>";
            }
        }
    }
    $arFields["ORDER_LIST"] = $strOrderList;

    $phone = "";
    $index = "";
    $country_name = "";
    $city_name = "";
    $address = $street = $dom = $kvartira = "";
    $fio = $district = $comment = "";
    while ($arProps = $order_props->Fetch()) {
        if ($arProps["CODE"] == "PHONE") {
            $phone = htmlspecialchars($arProps["VALUE"]);
        }
        if ($arProps["CODE"] == "LOCATION") {
            //$arLocs = CSaleLocation::GetByID($arProps["VALUE"]);
            //$country_name =  $arLocs["COUNTRY_NAME_ORIG"];
            //$city_name = $arLocs["CITY_NAME_ORIG"];
        }


        if ($arProps["CODE"] == "INDEX") {
            $index = $arProps["VALUE"];
        }

        if ($arProps["CODE"] == "ADDRESS") {
            $address = $arProps["VALUE"];
        }

        // Улица, дом квартира
        if ($arProps["CODE"] == "STREET") {
            $street = $arProps["VALUE"];
        }

        if ($arProps["CODE"] == "BUILDING") {
            $dom = $arProps["VALUE"] ?: "-";
        }

        if ($arProps["CODE"] == "APARTMENT") {
            $kvartira = $arProps["VALUE"] ?: "-";
        }
        //\\\\\\\\\\\\\\\\\\\\

        if ($arProps["CODE"] == "FIO") {
            $fio = $arProps["VALUE"];
        }
        if ($arProps["CODE"] == "CITY") {
            $city_name = $arProps["VALUE"];
        }
        if ($arProps["CODE"] == "DISTRICT") {
            $district = $arProps["VALUE"];
        }

        if ($arProps["CODE"] == "COMMENT") {
            $comment = $arProps["VALUE"];
        }
        if ($arProps["CODE"] == "PERSON_COUNT") {
            $person_count = $arProps["VALUE"];
        }
        if ($arProps["CODE"] == "ORDER_DISCOUNT_ID") {
            $order_discount_id = $arProps["VALUE"];
        }
    }
    $order_discount_name = "";
    if ($order_discount_id > 0) {
        CModule::IncludeModule('highloadblock');
        $hlblock_id = 6;
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $main_query = new Bitrix\Main\Entity\Query($entity);
        $main_query->setFilter(array('ID' => $order_discount_id));
        $main_query->setSelect(array('*'));
        $result = $main_query->exec();
        $result = new CDBResult($result);
        $prc = 0;
        if ($row = $result->Fetch()) {
            $order_discount_name = $row["UF_NAME"];
        }
    }


    //-- получаем название службы доставки
    $arDeliv = CSaleDelivery::GetByID($arOrder["DELIVERY_ID"]);
    $delivery_name = "";
    if ($arDeliv) {
        $delivery_name = $arDeliv["NAME"];
    }

    //-- получаем название платежной системы
    $arPaySystem = CSalePaySystem::GetByID($arOrder["PAY_SYSTEM_ID"]);
    $pay_system_name = "";
    if ($arPaySystem) {
        $pay_system_name = $arPaySystem["NAME"];
    }

    //-- добавляем новые поля в массив результатов
    $arFields["ORDER_DESCRIPTION"] = $arOrder["USER_DESCRIPTION"];
    $arFields["PHONE"] = $phone;
    $arFields["DELIVERY_NAME"] = $delivery_name;
    $arFields["PAY_SYSTEM_NAME"] = $pay_system_name;
    $arFields["FIO"] = $fio;
    $arFields["CITY"] = $city_name;
    $arFields["DISTRICT"] = $district;
    $arFields["COMMENT"] = $comment;
    $arFields["PERSON_COUNT"] = $person_count;
    $arFields["TOTAL_CASH"] = trim($_REQUEST['total_cash']) ? "Купюра: " . $_REQUEST['total_cash'] : "";
    $arFields["ORDER_DISCOUNT"] = $order_discount_name;
    // $arFields["ADDRESS"] = $address;
    $arFields["ADDRESS"] = "Улица: {$street}, дом: {$dom}, кв.: {$kvartira}";

    $data = array_merge(array(), $arFields);
    $data["STREET"] = $street;
    $data["HOUSE"] = $dom;
    $data["FLAT"] = $kvartira;
    $data["TOTAL_CASH"] = trim($_REQUEST['total_cash']) ? $_REQUEST['total_cash'] : "";
    $data["ORDER_DISCOUNT_PRICE"] = $arOrder["DISCOUNT_VALUE"];
    $data["ORDER_DELIVERY_PRICE"] = $arOrder["PRICE_DELIVERY"];
    $data["PRICE"] = $arOrder["PRICE"];
    $data["EMAIL"] = $email;
    //ws_add_order($data,$arBasketList);

    // Отправка СМС тут же
    require_once($_SERVER[DOCUMENT_ROOT] . "/bitrix/templates/.default/smsPHPClass/transport.php");
    CModule::includeModule('ws.projectsettings');
    $api = new Transport();
    $admin_phone = $cities[$city]["SMS_PHONE"];
    $admin_phone = str_replace(array(" ", "-", "+", "(", ")"), "", trim($admin_phone));
    if ($admin_phone) {
        $params = array(
            "onlydelivery" => "0",
            "text" => "Новый заказ на сайте. Телефон: {$phone}"
        );

        $send = $api->send($params, explode(",", $admin_phone));

        $params = array(
            "onlydelivery" => "0",
            "text" => "Ваш заказ принят. Вам перезвонят в течение 10 минут. ya-goloden.ru"
        );

        $phone = str_replace(array(" ", "-", "+", "(", ")"), "", trim($phone));
        $send = $api->send($params, explode(",", $phone));

        if ($send[code] == 1) {
            AddMessage2Log("SMS успешно отправлено для заказа. smsid = {$send[smsid]}");
        } else {
            AddMessage2Log("Заказ: ошибка отправки SMS (Код: {$send[code]}, описание: {$send[descr]})");
        }
    }
}

function getCity()
{
    global $city;
    require_once($_SERVER[DOCUMENT_ROOT] . "/bitrix/templates/.default/geo.php");
    $geo = new Geo(array('charset' => 'utf-8'));
    $city_try = $geo->get_value('city', true);

    $availCities = array("Новокузнецк" => 1, "Новосибирск" => 3);

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    // AddMessage2Log("Определен город: {$city_try}, IP клиента: {$ip}");
    if ($availCities[$city_try]) {
        $city = $availCities[$city_try];
    }
}


function GetCityDistricts()
{
    global $city, $cities;
    CModule::IncludeModule('highloadblock');
    $hlblock_id = 5;
    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $main_query = new Bitrix\Main\Entity\Query($entity);
    $main_query->setFilter(array('UF_CITY_ID' => $city));
    $main_query->setSelect(array('*'));
    $result = $main_query->exec();
    $result = new CDBResult($result);
    $districts = array();
    while ($row = $result->Fetch()) {
        $districts[$row["ID"]] = array(
            "CITY_ID" => $row["UF_CITY_ID"],
            "NAME" => $row["UF_NAME"],
            "PRICE" => $row["UF_PRICE"],
            "MINSUM" => $row["UF_MINSUM"]
        );
    }
    return $districts;
}

function GetCityStreets()
{
    global $city;
    CModule::IncludeModule('highloadblock');
    $hlblock_id = 7;
    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $main_query = new Bitrix\Main\Entity\Query($entity);
    $main_query->setFilter(array('UF_CITY_ID' => $city));
    $main_query->setSelect(array('*'));
    $main_query->setOrder(array('UF_NAME'));
    $result = $main_query->exec();
    $result = new CDBResult($result);
    $streets = array();
    while ($row = $result->Fetch()) {
        $streets[] = array(
            "NAME" => $row["UF_NAME"],
        );
    }
    return $streets;
}

CModule::IncludeModule("sale");

Class CDeliveryPlain
{

    /**
     * Описние обработчика
     */
    function Init()
    {
//настройки
        return array(
            "SID" => "Plain", // Идентификатор службы доставки
            "NAME" => "Пример обработчика службы доставки",
            "DESCRIPTION" => "Описание его для клиентов сайта",
            "DESCRIPTION_INNER" => "Описание для администраторов сайта",
            "BASE_CURRENCY" => "RUB",
            "HANDLER" => __FILE__,
            /* Определение методов */
            "DBGETSETTINGS" => array("CDeliveryPlain", "GetSettings"),
            "DBSETSETTINGS" => array("CDeliveryPlain", "SetSettings"),
            "GETCONFIG" => array("CDeliveryPlain", "GetConfig"),
            "COMPABILITY" => array("CDeliveryPlain", "Compability"),
            "CALCULATOR" => array("CDeliveryPlain", "Calculate"),
            /* Список профилей */
            "PROFILES" => array(
                "all" => array(
                    "TITLE" => "Без ограничений",
                    "DESCRIPTION" => "Профиль доставки без каких-либо ограничений",
                    "RESTRICTIONS_WEIGHT" => array(0),
                    "RESTRICTIONS_SUM" => array(0),
                ),
            )
        );
    }

    /* Установка параметров */

    function SetSettings($arSettings)
    {
        foreach ($arSettings as $key => $value) {
            if (strlen($value) > 0) {
                $arSettings[$key] = doubleval($value);
            } else {
                unset($arSettings[$key]);
            }
        }

        return serialize($arSettings);
    }

    /* Запрос параметров */

    function GetSettings($strSettings)
    {
        return unserialize($strSettings);
    }

    /* Запрос конфигурации службы доставки */

    function GetConfig()
    {
        $arConfig = array(
            "CONFIG_GROUPS" => array(
                "all" => "Параметры",
            ),
            "CONFIG" => array(
                "DELIVERY_PRICE" => array(
                    "TYPE" => "STRING",
                    "DEFAULT" => "200",
                    "TITLE" => "Стоимость доставки",
                    "GROUP" => "all"
                )
            ),
        );
        return $arConfig;
    }

    /* Проверка соответствия профиля доставки заказу */

    function Compability($arOrder, $arConfig)
    {
        return array("all");
    }

    /* Калькуляция стоимости доставки */

    function Calculate($profile, $arConfig, $arOrder, $STEP, $TEMP = false)
    {
        $order_discount_id = (int)$_REQUEST['ORDER_PROP_9'];
        if ($order_discount_id > 0) {
            CModule::IncludeModule('highloadblock');
            $hlblock_id = 6;
            $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
            $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
            $main_query = new Bitrix\Main\Entity\Query($entity);
            $main_query->setFilter(array('ID' => $order_discount_id));
            $main_query->setSelect(array('*'));
            $result = $main_query->exec();
            $result = new CDBResult($result);
            $prc = 0;
            if ($row = $result->Fetch()) {
                if (strpos("Самовывоз", $row["UF_NAME"]) !== false) {
                    return array(
                        "RESULT" => "OK",
                        "VALUE" => 0
                    );
                }
            }
        }


        $delivery_price = $arConfig["DELIVERY_PRICE"];
        $districts = GetCityDistricts();
        $vs = array_values($districts);
        if (count($vs) > 0) {
            $delivery_price = $vs[0]["PRICE"];
        }

        $price = (int)$arOrder[PRICE];

        foreach ($districts as $d) {
            if ($d["NAME"] == $_REQUEST["ORDER_PROP_8"]) {
                $minsum = (int)$d[MINSUM];

                if ($price < $minsum) {
                    $delivery_price = $d["PRICE"];
                } else {
                    $delivery_price = 0;
                }
                break;
            }
        }

        return array(
            "RESULT" => "OK",
            "VALUE" => $delivery_price
        );
    }

}

AddEventHandler("sale", "onSaleDeliveryHandlersBuildList", array("CDeliveryPlain", "Init"));
AddEventHandler("sale", "OnBeforeBasketDelete", "OnBeforeBasketDeleteWithUndelete");


function OnBeforeBasketDeleteWithUndelete($ID)
{

    $res = CSaleBasket::Update($ID, array(
        "SUBSCRIBE" => "Y",
        "DELAY" => "Y"
    ));

    // Если возвращает false - невозможно удалить заказы в админке, если true - нет возможности восстановить в корзине
    if ($res) {
        return true;
    } else {
        return false;
    }
}

function SetGlobalVars()
{
    global $city, $cities;
    $city = 0;
    $host = $_SERVER["SERVER_NAME"];
    CModule::IncludeModule('highloadblock');
    $hlblock_id = 4;
    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $main_query = new Bitrix\Main\Entity\Query($entity);
    $main_query->setSelect(array(
        'ID',
        'UF_XML_ID',
        'UF_NAME',
        'UF_ACTIVE',
        'UF_DELIVERY_PIC',
        'UF_DELIVERY',
        'UF_TIME_DELIVERY',
        'UF_ORDER_ENABLED',
        'UF_SMS_PHONE',
        'UF_EMAIL'
    ));
    $result = $main_query->exec();
    $result = new CDBResult($result);
    $cities = array();
    while ($row = $result->Fetch()) {
        if ($row['UF_ACTIVE'] == 17) {
            continue;
        }
        $city_code = $row["UF_XML_ID"];
        $cities[$row["ID"]] = array(
            "CODE" => $city_code,
            "NAME" => $row["UF_NAME"],
            'ACTIVE' => $row['UF_ACTIVE'],
            "PHONE" => $row['UF_PHONE'],
            "DELIVERY_PIC" => $row['UF_DELIVERY_PIC'],
            "DELIVERY" => $row['UF_DELIVERY'],
            "TIME_DELIVERY" => $row['UF_TIME_DELIVERY'],
            'ORDER_ENABLED' => $row['UF_ORDER_ENABLED'],
            'SMS_PHONE' => $row['UF_SMS_PHONE'],
            'EMAIL' => $row['UF_EMAIL']
        );
        $city_code = str_replace("nkz", "nk", $city_code);
        if (substr($host, 0, strlen($city_code) + 1) == $city_code . ".") {
            $city = $row["ID"];
        }
    }

    $host = str_replace("www.", "", $host);
    foreach ($cities as $c) {
        $host = str_replace(str_replace("nkz", "nk", $c["CODE"]) . ".", "", $host);
    }

    $city_changed = false;
    if (!$city) {
        global $APPLICATION;
        if ('/' == $APPLICATION->GetCurDir()) {
            return;
        }
        header("HTTP/1.1 301 Moved Permanently");
    }


    if ((int)$_POST['city'] > 0 && $city != (int)$_POST['city']) {

        $city = (int)$_POST['city'];
        $city_changed = true;
        setcookie('city2', $city, time() + 3600 * 24, "/", $host);
    }
    $cookie_city = (int)$_COOKIE['city2'];
    if ($cookie_city != $city && !$city_changed) {
        recalcBasket();
    }
    if ($city != 1 && $city != 3 && $city != 2) {
        if ($cookie_city == 1 || $cookie_city == 3 || $cookie_city == 2) {
            $city = $cookie_city;
        } else {
            getCity();
            if ((int)$city != 1 && (int)$city != 3 && (int)$city != 2) {
                $city = 1;
            }
            setcookie('city_first', 1, time() + 3600 * 24, "/", $host);
            setcookie('city2', $city, time() + 3600 * 24, "/", $host);
        }
        if ($city) {
            $city_changed = true;
        }
    }

    if ($city_changed) {
        recalcBasket();
        global $APPLICATION;
        $uri = $APPLICATION->GetCurDir();
        $host = str_replace("nkz", "nk", $cities[$city]["CODE"]) . "." . $host;

        header("Location: http://$host$uri");
        die;

    }

    if ($cities[$city]["CODE"] == 'nsk') {
        date_default_timezone_set('Asia/Dhaka');
    } else {
        date_default_timezone_set('Asia/Novokuznetsk');
    }
}


function recalcBasket()
{
    $arID = array();
    CModule::IncludeModule('catalog');
    CModule::IncludeModule('sale');
    CModule::IncludeModule('iblock');

    $arBasketItems = array();

    $dbBasketItems = CSaleBasket::GetList(
        array(
            "NAME" => "ASC",
            "ID" => "ASC"
        ),
        array(
            "FUSER_ID" => CSaleBasket::GetBasketUserID(),
            "LID" => SITE_ID,
            "ORDER_ID" => "NULL",
            "CAN_BUY" => "Y"
        ),
        false,
        false,
        array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "PRODUCT_PROVIDER_CLASS")
    );
    while ($arItems = $dbBasketItems->Fetch()) {
        if ('' != $arItems['PRODUCT_PROVIDER_CLASS'] || '' != $arItems["CALLBACK_FUNC"]) {
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
    if (!empty($arID)) {
        $dbBasketItems = CSaleBasket::GetList(
            array(
                "NAME" => "ASC",
                "ID" => "ASC"
            ),
            array(
                "ID" => $arID,
                "ORDER_ID" => "NULL",
                "CAN_BUY" => "Y"
            ),
            false,
            false,
            array(
                "ID",
                "CALLBACK_FUNC",
                "MODULE",
                "PRODUCT_ID",
                "QUANTITY",
                "DELAY",
                "CAN_BUY",
                "PRICE",
                "WEIGHT",
                "PRODUCT_PROVIDER_CLASS",
                "NAME",
                "SUBSCRIBE"
            )
        );
        while ($arItem = $dbBasketItems->Fetch()) {
            if ($arItem["SUBSCRIBE"] == "Y") {
                continue;
            }
            global $city, $cities, $USER;
            $mxResult = CCatalogSku::GetProductInfo(
                $arItem["PRODUCT_ID"]
            );

            if (is_array($mxResult)) {
                $elementId = $mxResult['ID'];
                $iblockId = $mxResult['IBLOCK_ID'];
            } else {
                $elementId = $arItem["PRODUCT_ID"];
                $iblockId = 2;
            }
            $arSkuInfo = CCatalogSKU::GetInfoByProductIBlock($iblockId);
            $arItem["OFFERS"] = array();
            $rres = CIBlockElement::GetList(
                array(),
                array(
                    'IBLOCK_ID' => $arSkuInfo['IBLOCK_ID'],
                    '=PROPERTY_' . $arSkuInfo['SKU_PROPERTY_ID'] => $elementId
                ),
                false,
                false,
                array("ID", "PROPERTY_CITY", "CATALOG_GROUP_1")
            );

            while ($ob = $rres->GetNextElement()) {
                $offer = $ob->GetFields();
                $arItem["OFFERS"][$offer["PROPERTY_CITY_VALUE"]] = $offer;
            }
            if (count($arItem["OFFERS"]) == 0) {
                CSaleBasket::Update($arItem["ID"], array("DELAY" => "N"));
            } else {
                if (!$arItem["OFFERS"][$cities[$city]["CODE"]]["ID"]) {
                    CSaleBasket::Update($arItem["ID"], array("DELAY" => "Y"));
                } else {
                    if ($arItem["PRODUCT_ID"] != $arItem["OFFERS"][$cities[$city]["CODE"]]["ID"]) {
                        $newOffer = $arItem["OFFERS"][$cities[$city]["CODE"]];
                        $offer_id = $newOffer["ID"];
                        $price = $newOffer["CATALOG_PRICE_1"];
                        CSaleBasket::Update($arItem["ID"], array(
                            "DELAY" => "N",
                            "PRODUCT_ID" => $offer_id,
                            "PRICE" => $price
                        ));

                    }
                }
            }
//            }
        }
    }
}


require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/php_interface/phpmailer/class.phpmailer.php");

function custom_mail($to, $subject, $message, $additionalHeaders = '')
{
    $smtpServerHost = 'smtp.yandex.ru';
    $smtpServerHostPort = 465;
    $smtpServerUser = 'pizzmanzakaznvkz@yandex.ru';
    $smtpServerUserPassword = 'pizzman200999';

    $mailer = new PHPMailer();
    $mailer->IsSMTP();
    $mailer->CharSet = 'UTF-8';
    $mailer->SMTPAuth = true;
    $mailer->Username = $smtpServerUser;//Kernel::getVar('smtp_user');
    $mailer->Password = $smtpServerUserPassword;//Kernel::getVar('smtp_pass');
    $mailer->SMTPSecure = 'ssl';

    $mailer->Host = $smtpServerHost;//Kernel::getVar('smtp_server_addr');
    $mailer->Port = $smtpServerHostPort;//Kernel::getVar('smtp_server_port');


    preg_match('/From: (.+)\n/i', $additionalHeaders, $matches);
    list(, $from) = $matches;

    $mailer->From = $smtpServerUser;
    $mailer->FromName = $smtpServerUser;
    foreach (explode(",", $to) as $toa) {
        $mailer->AddAddress($toa);
    }
    $mailer->Subject = $subject;
    $mailer->Body = $message;
    $mailer->IsHTML(true);
    $mailer->SMTPDebug  = 1;
    $result = $mailer->send();


    return true;
}


AddEventHandler('main', 'OnEpilog', '_Check404Error', 1);
function _Check404Error()
{
    if (defined('ERROR_404') && ERROR_404 == 'Y' && !defined('ADMIN_SECTION')) {
        GLOBAL $APPLICATION;
        $APPLICATION->RestartBuffer();
        require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/templates/sushman/header.php';
        require $_SERVER['DOCUMENT_ROOT'] . '/404.php';
        require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/templates/sushman/footer.php';
    }
}


require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/iblock/lib/template/functions/fabric.php');

use Bitrix\Main;

$eventManager = Main\EventManager::getInstance();
$eventManager->addEventHandler("iblock", "OnTemplateGetFunctionClass", "myOnTemplateGetFunctionClass");

function myOnTemplateGetFunctionClass(Bitrix\Main\Event $event)
{
    $arParam = $event->getParameters();
    $functionClass = $arParam[0];
    if (is_string($functionClass) && class_exists($functionClass) && $functionClass == 'splitted') {
        $result = new Bitrix\Main\EventResult(1, $functionClass);
        return $result;
    }
}

class splitted extends Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
        /** @var \Bitrix\Iblock\Template\NodeBase $parameter */
        foreach ($parameters as $parameter) {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }

    public function calculate(array $parameters)
    {
        if (isset($parameters[0]) && $parameters[0]) {
            $parts = explode(" ", $parameters[0]);
            foreach ($parts as $k => $v) {
                $parts[$k] = trim(strtolower($v));
            }
            return implode(", ", $parts);
        }
        return "";
    }
}

function convert_tpl($value)
{
    $value = str_replace("#CITY#", $cities[$city]["NAME"], $value);
    $value = str_replace("#CITY2#", $cities[$city]["NAME"] . "е", $value);
    $value = str_replace("#LCCITY#", strtolower($cities[$city]["NAME"]), $value);
    $value = str_replace("#LCCITY2#", strtolower($cities[$city]["NAME"] . "е"), $value);
    return $value;
}

function ws_add_order($data, $products_data)
{
    global $city;
    $cids = array();
    foreach ($products_data as $arItem) {
        if (!$arItem['props']["cid"]["VALUE"]) {
            continue;
        }
        $cids[$arItem['props']["cid"]["VALUE"]] = $arItem['props']["cid_type"]["VALUE"];
    }
    $products = array();
    foreach ($products_data as $arItem) {
        if ($arItem['props']["cid"]["VALUE"]) {
            continue;
        }
        $res = CIBlockElement::GetByID($arItem["PRODUCT_ID"]);
        if ($ar_res = $res->GetNext()) {
            $db_props = CIBlockElement::GetProperty($ar_res["IBLOCK_ID"], $ar_res["ID"], array(),
                Array("CODE" => "FOID"));
            if ($ar_props = $db_props->Fetch()) {
                $foid = trim($ar_props["VALUE"]);
                if ($foid) {
                    $products[] = array("code" => $foid, "quantity" => $arItem["QUANTITY"]);
                }
            }
        }
    }
    foreach ($cids as $cid => $cid_type) {

        $strOrderList .= ($cid_type == 'wok' ? 'Вок' : 'Половинки пицц') . ":<br/>";
        foreach ($products_data as $arItem) {
            if ($arItem['props']["cid"]["VALUE"] == $cid) {
                $strOrderList .= "&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;" . $arItem["NAME"] . " - " . $arItem["QUANTITY"] . " " . $measureText . ": " . SaleFormatCurrency($arItem["PRICE"],
                        $arItem["CURRENCY"]);
                //$strOrderList .= "CID: ".$arItem['props']["cid"]["VALUE"]." CID_TYPE:".$arItem['props']["cid_type"]["VALUE"];
                $strOrderList .= "<br/>";
            }
        }
    }
    $data["ORDER_TEXT"] = <<<EOF

Состав заказа:
{$data["ORDER_LIST"]}

Стоимость заказа: {$data["PRICE"]}
Город: {$data["CITY"]}
Имя: {$data["FIO"]}
Контактный телефон: {$data["PHONE"]}
Стоимость доставки: {$data["ORDER_DELIVERY_PRICE"]}
Способ оплаты: {$data["PAY_SYSTEM_NAME"]}
Адрес доставки: {$data["CITY"]}, {$data["DISTRICT"]}, {$data["ADDRESS"]}
Персон: {$data["PERSON_COUNT"]}
Комментарий: {$data["COMMENT"]}
Выбранная скидка: {$data["ORDER_DISCOUNT"]}
Величина скидки: {$data["ORDER_DISCOUNT_PRICE"]}
Купюра: {$data["TOTAL_CASH"]}
EOF;

    $paymethod_id = "100000001";
    $source_id = "100000001";
    $dp = (int)$data["ORDER_DISCOUNT_PRICE"];
    $person_count = (int)$data['PERSON_COUNT'];
    if (!$person_count) {
        $person_count = 1;
    }
    $order_xml_string = <<<EOF
<Order AmountNight="" DepartmentID="{$source_id}" DiscountAmount="{$dp}" PayMethod="{$paymethod_id}" QtyPerson="{$person_count}" Type="1" Remark="" RemarkMoney="" TimePlan="">
       <Customer Login="" FIO=""></Customer>
       <Address CityID="" StationName="" StreetName="" House="" Corpus="" Building="" Flat="" Porch="" Floor="" DoorCode=""></Address>
       <Phone Code="" Number=""></Phone>
       <Products>
       </Products>
        <Remark></Remark>
</Order>
EOF;
    $xml = simplexml_load_string($order_xml_string);
    $xml['RemarkMoney'] = $data['TOTAL_CASH'];
    //$xml['AmountNight'] = $data['ORDER_DELIVERY_PRICE'];
    $xml->Customer['FIO'] = $data["FIO"];
    $xml->Customer['Login'] = $data["EMAIL"];
    //$xml->Address['CityID'] = 1;
    $xml->Address['CityID'] = $city;
    $xml->Address['ZoneID'] = '100000000';
    $xml->Address['ZoneName'] = 'Центральный район';
    $xml->Address['StreetName'] = $data["STREET"];
    $xml->Address['House'] = $data["HOUSE"];
    $xml->Address['Flat'] = $data["FLAT"];
    $xml->Phone['Number'] = preg_replace("/[\(\)\+\- ]/Usi", "", str_replace("+7", "", $data["PHONE"]));
    $xml->Remark->{0} = str_replace("<br />", "\r\n", str_replace("<br/>", "\r\n", $data["ORDER_TEXT"]));
    foreach ($products as $p) {
        $pnode = $xml->Products->addChild("Product");
        $pnode['Code'] = $p['code'];
        $pnode['Qty'] = $p['quantity'];
    }
    $order = $xml->asXML();
    $order_text = "Тестовый заказ через веб-сервис";

    global $cities, $city;
    $urls = array(
        'nkz' => "158.46.252.166:1860",
        'nsk' => "80.89.151.158:1860"
    );
    $url = $urls[$cities[$city]["CODE"]];

    $url = "http://{$url}/FastOperator.asmx/AddOrder";
    $fields = array(
        'Order' => urlencode($order),
        'OrderText' => urlencode($order_text)
    );
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpcode == 200) {
            $xml = simplexml_load_string($result);
            $code = (int)$xml['Code'];
            if ($code > 0) {
                echo "Success!!!! OrderID: $code";
            }
        }
        echo $httpcode;
        curl_close($ch);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    /*
    echo "----";
    echo "<pre>";
    echo $order;
    echo "</pre>";
    die("----");
    */
}