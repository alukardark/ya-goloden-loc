<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
if(empty($arResult["BRAND_BLOCKS"]))
	return;

?>
<?

$container_class = "";
foreach ($arResult["BRAND_BLOCKS"] as $blockId => $arBB)
{
	$brandID = 'brand_'.$arResult['ID'].'_'.$this->randString();

	$add_class = "";

	if($arBB[NAME] == "Острые") {
		$add_class = "ingr-pepper";
		$container_class = "ingr-visible";
	}
	if($arBB[LINK] == "new") {
		$add_class = "ingr-pepper";
		$container_class = "ingr-visible";
	}
	$html .= "<span class='ingr-icon {$add_class} {$arBB[CODE]}' style='".($arBB['PICT'] != false && strlen($arBB['PICT']['SRC']) > 0 ? "background-image:url({$arBB['PICT']['SRC']});" : "")."'><span style='".($arBB['COLOR'] ? "color:{$arBB['COLOR']};" : "")."'>{$arBB['NAME']}</span></span>";
}
?>
<div class="ingr-icons <?=$container_class?>">
    <? echo $html; ?>
</div>