<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/personal/order/#",
		"RULE" => "",
		"ID" => "bitrix:sale.personal.order",
		"PATH" => "/personal/order/index.php",
	),
	array(
		"CONDITION" => "#^/photos/manage/#",
		"RULE" => "",
		"ID" => "bitrix:photogallery",
		"PATH" => "/photos/manage/index.php",
	),
	array(
		"CONDITION" => "#^/specials/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/specials/index.php",
	),
	array(
		"CONDITION" => "#^/search/#",
		"RULE" => "",
		"ID" => "bitrix:catalog.section",
		"PATH" => "/search/index.php",
	),
	array(
		"CONDITION" => "#^/store/#",
		"RULE" => "",
		"ID" => "bitrix:catalog.store",
		"PATH" => "/store/index.php",
	),
	array(
		"CONDITION" => "#^/menu/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/menu/index.php",
	),
	array(
		"CONDITION" => "#^/menu/rolly_dlya_setov/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/",
	),
	array(
		"CONDITION" => "#^/([a-zA-Z_-]+)/#",
		"RULE" => "CODE=\$1",
		"ID" => "",
		"PATH" => "/menu/stay_pages.php",
	)
);

?>