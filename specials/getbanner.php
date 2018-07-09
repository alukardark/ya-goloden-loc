<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
SetGlobalVars();
global  $city, $cities;
$cur_city = $cities[$city];

CModule::IncludeModule('iblock');
CModule::IncludeModule('main');
$iblocks = array(1 => 1, 2 => 7, 3 => 5);
$filter = array('IBLOCK_ID' => $iblocks[$city], 'PROPERTY_POPUP' => 1);
$actions = CIBlockElement::getList(array(), $filter,false, false, array('DETAIL_TEXT', 'DETAIL_PAGE_URL', 'PROPERTY_MIN_CHECK'));
$action = $actions->GetNext();
if (!$action) {die();}
//preg_match('#\<img (.*)\>#', $action['DETAIL_TEXT'], $image);
preg_match('#(\<img (.*)\"\>)#', $action['DETAIL_TEXT'], $image);
$image = str_replace('<br>', '', $image[0]);

?>

<? if ((empty($_REQUEST['summ']) || $action['PROPERTY_MIN_CHECK'] < $_REQUEST['summ'])): ?>
<a href="<?= $action['DETAIL_PAGE_URL']; ?>"><?= $image; ?></a><br>
<div class="bannerButtonContainer">
<a class="buy bx_bt_button bx_medium" href="<?= $action['DETAIL_PAGE_URL']; ?>">Подробнее об акции</a>
<a class="buy bx_bt_button bx_medium second" href="<?= $action['DETAIL_PAGE_URL']; ?>">Подробнее об акции</a>
</div>
<? endif; ?>