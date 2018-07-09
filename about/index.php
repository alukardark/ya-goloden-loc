<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О компании");
?><?
    global $city,$cities;
    $APPLICATION->IncludeFile("/include/about_{$cities[$city]["CODE"]}.php", Array(), Array(
        "MODE"      => "html",                   
        "NAME"      => "О нас",
    ));
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>