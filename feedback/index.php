<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<div class="popup-form">
<h2>Написать директору</h2>
<?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new",
	"popup",
	Array(
		"SEF_MODE" => "N",
		"WEB_FORM_ID" => "1",
		"RESULT_ID" => $_REQUEST[RESULT_ID],
		"START_PAGE" => "new",
		"SHOW_LIST_PAGE" => "N",
		"SHOW_EDIT_PAGE" => "N",
		"SHOW_VIEW_PAGE" => "N",
		"SUCCESS_URL" => "",
		"SHOW_ANSWER_VALUE" => "N",
		"SHOW_ADDITIONAL" => "N",
		"SHOW_STATUS" => "N",
		"EDIT_ADDITIONAL" => "N",
		"EDIT_STATUS" => "N",
		"NOT_SHOW_FILTER" => array(),
		"NOT_SHOW_TABLE" => array(),
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"USE_EXTENDED_ERRORS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
                "AJAX_MODE" => "Y",  // режим AJAX
                "AJAX_OPTION_SHADOW" => "N", // затемнять область
                "AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
                "AJAX_OPTION_STYLE" => "Y", // подключать стили
                "AJAX_OPTION_HISTORY" => "N",                
		"AJAX_OPTION_ADDITIONAL" => "",
		"VARIABLE_ALIASES" => Array(
			"action" => "action",
                        "SMS_PHONE" => '79832261345' //WS_PSettings::getFieldValue('sms_phone_service', false) ? WS_PSettings::getFieldValue('sms_phone_service', false) : WS_PSettings::getFieldValue('sms_phone', false)
		)
	)
);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>