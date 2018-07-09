<?php

require_once 'class.JavaScriptPacker.php';

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}


function put_captcha($key, $name)
{
    $name = $name;
    $orig_value = rand() . time();
    $value = rand() . uniqid('', true);
    $_SESSION["form_" . $key] = "$name/$value";
    $packer = new JavaScriptPacker("$('form[name=SIMPLE_FORM_$key] input[name=txt_$name]').val('$value');", 'Normal',
        true, false);
    $jscode = $packer->pack();
    $captcha = <<<EOF
        <input type="hidden" name="txt_$name" value='$orig_value'/>
        <script type="text/javascript">{$jscode}</script>
EOF;
    return $captcha;
}


if ($arResult["isFormErrors"] != "Y" && $arResult["isFormNote"] == "Y") {
    if (CModule::IncludeModule('form')) {
//        CModule::includeModule('ws.projectsettings');
//        $RESULT_ID = (int)$_REQUEST["RESULT_ID"];
//        $arAnswer = CFormResult::GetDataByID(
//            $RESULT_ID,
//            array(),
//            $arResult2,
//            $arAnswer2);
//        $title = $arResult2['NAME'];
//        $name = $arAnswer['NAME'][0]['USER_TEXT'];
//        $phone = $arAnswer['PHONE'][0]['USER_TEXT'];
//        require_once($_SERVER[DOCUMENT_ROOT] . "/bitrix/templates/.default/smsPHPClass/transport.php");
//        $api = new Transport();
//        $phone = str_replace(array(" ", "-", "+", "(", ")"), "", trim($phone));
//        if ($phone) {
//            $params = array(
//                "onlydelivery" => "0",
//                "text" => "Ваше сообщение принято. я-голоден.рф"
//            );
//
//            $send = $api->send($params, explode(",", $phone));
//        }
        if(SMS_PROVIDER == 'smsphpclass'){
            CModule::includeModule('ws.projectsettings');
            $RESULT_ID = (int)$_REQUEST["RESULT_ID"];
            $arAnswer = CFormResult::GetDataByID(
                $RESULT_ID,
                array(),
                $arResult2,
                $arAnswer2);
            $title = $arResult2['NAME'];
            $name = $arAnswer['NAME'][0]['USER_TEXT'];
            $phone = $arAnswer['PHONE'][0]['USER_TEXT'];
            require_once($_SERVER[DOCUMENT_ROOT] . "/bitrix/templates/.default/smsPHPClass/transport.php");
            $api = new Transport();
            $phone = str_replace(array(" ", "-", "+", "(", ")"), "", trim($phone));
            if ($phone) {
                $params = array(
                    "onlydelivery" => "0",
                    "text" => "Ваше сообщение принято. я-голоден.рф"
                );

                $send = $api->send($params, explode(",", $phone));
            }
        }
        if(SMS_PROVIDER == 'smsimple'){
            CModule::includeModule('ws.projectsettings');
            $RESULT_ID = (int)$_REQUEST["RESULT_ID"];
            $arAnswer = CFormResult::GetDataByID(
                $RESULT_ID,
                array(),
                $arResult2,
                $arAnswer2);
            $title = $arResult2['NAME'];
            $name = $arAnswer['NAME'][0]['USER_TEXT'];
            $phone = $arAnswer['PHONE'][0]['USER_TEXT'];
            $phone = str_replace(array(" ", "-", "+", "(", ")"), "", trim($phone));
            if ($phone) {
                $msg = "Ваше сообщение принято. я-голоден.рф";
                $send = simpleSMS($phone, $msg);
            }
        }
    } else {
        ShowError('Нет такого модуля');
    }
} else {
    $name = md5($arResult['arForm']['NAME']);
    $id = $arResult['arForm']['ID'];
    $captcha = put_captcha($id, $name);
    $arResult['CAPTCHA_HTML_CODE'] = $captcha;
    foreach ($arResult["QUESTIONS"] as $k => &$item) {
        $fclass = "";
        if (strpos($k, "PHONE") !== false) {
            $item["HTML_CODE"] = str_replace("<input ", "<input class='phone' ", $item["HTML_CODE"]);
            $fclass = "phone";
        }
        $item["HTML_CODE"] = str_replace("<input ", "<input maxlength='200' placeholder='{$item['CAPTION']}' ",
            $item["HTML_CODE"]);
        $item["HTML_CODE"] = str_replace("<textarea ", "<textarea maxlength='2500' placeholder='{$item['CAPTION']}' ",
            $item["HTML_CODE"]);
        $item["HTML_CODE"] = preg_replace("/<select (.+)>/Usi",
            '<select title="' . $item['CAPTION'] . '" $1><option></option>', $item["HTML_CODE"]);
        $item["HTML_CODE"] = "<div class='f f{$fclass}'>{$item["HTML_CODE"]}</div>";
    }
}

SetGlobalVars();
global $city, $cities;

$arResult['QUESTIONS']['CITY']['HTML_CODE'] = '<div class="f fcity"><div class="corner"></div><select title="Город" selected="" name="form_dropdown_CITY" id="form_dropdown_CITY">';

$value = 5;
foreach ($cities as $_city) {

    $selected = $cities[$city]['NAME'] == $_city['NAME'] ? 'selected' : '';
    $arResult['QUESTIONS']['CITY']['HTML_CODE'] .= '<option ' . $selected . ' value="' . $value . '">' . $_city['NAME'] . '</option>';
    $value++;
}

$arResult['QUESTIONS']['CITY']['HTML_CODE'] .= '</select></div>';