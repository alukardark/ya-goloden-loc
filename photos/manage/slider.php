<?
global $hide_title,$hide_center_col;
$wrapper_class = "about-wrapper";
$hide_title = true;
$hide_center_col = true;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Управление");
global $USER;
if ($USER->IsAdmin()) {
?>
<?
$APPLICATION->IncludeComponent(
	"bitrix:photogallery", 
	".default", 
	array(
		"USE_LIGHT_VIEW" => "Y",
		"IBLOCK_TYPE" => "photos",
		"IBLOCK_ID" => 4,
		"PATH_TO_USER" => "",
		"DRAG_SORT" => "Y",
		"USE_COMMENTS" => "N",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/photos/manage/",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"SET_TITLE" => "N",
		"ALBUM_PHOTO_SIZE" => "600",
		"THUMBNAIL_SIZE" => "0",
		"ORIGINAL_SIZE" => "0",
		"PHOTO_LIST_MODE" => "Y",
		"SHOWN_ITEMS_COUNT" => "4",
		"USE_RATING" => "N",
		"SHOW_TAGS" => "N",
		"UPLOADER_TYPE" => "form",
		"UPLOAD_MAX_FILE_SIZE" => "50",
		"USE_WATERMARK" => "N",
		"SHOW_LINK_ON_MAIN_PAGE" => array(
		),
		"SECTION_SORT_BY" => "UF_DATE",
		"SECTION_SORT_ORD" => "DESC",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "desc",
		"DATE_TIME_FORMAT_DETAIL" => "d.m.Y",
		"DATE_TIME_FORMAT_SECTION" => "d.m.Y",
		"SECTION_PAGE_ELEMENTS" => "15",
		"ELEMENTS_PAGE_ELEMENTS" => "50",
		"PAGE_NAVIGATION_TEMPLATE" => "",
		"JPEG_QUALITY1" => "100",
		"JPEG_QUALITY" => "100",
		"ADDITIONAL_SIGHTS" => array(
		),
		"SHOW_NAVIGATION" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"section" => "#SECTION_ID#/",
			"section_edit" => "#SECTION_ID#/action/#ACTION#/",
			"section_edit_icon" => "#SECTION_ID#/icon/action/#ACTION#/",
			"upload" => "#SECTION_ID#/action/upload/",
			"detail" => "#SECTION_ID#/#ELEMENT_ID#/",
			"detail_edit" => "#SECTION_ID#/#ELEMENT_ID#/action/#ACTION#/",
			"detail_list" => "list/",
			"search" => "search/",
		)
	),
	false
);
}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>