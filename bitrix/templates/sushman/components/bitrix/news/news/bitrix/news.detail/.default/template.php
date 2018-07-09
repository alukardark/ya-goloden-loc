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
?>
<style>
.description img {
max-width:100%;
border-radius:32px;
}
</style>
<div class="news-detail<?=($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"]) ? "" : "noimage")?>">
 <!--
            <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
                <div class="image">
                    <?php
                        $detail = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"],array("width" => 376,"height" => 9999),BX_RESIZE_IMAGE_PROPORTIONAL_ALT );
                    ?>
                    <a href="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" class="galery" title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"><img
                            class="detail_picture"
                            src="<?=$detail["src"]?>"
                            alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
                            title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
                            /></a>
                </div>
            <?endif?>
-->
    <div class="description static">
        <?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
            <div class="date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></div>
        <?endif?>
	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?endif;?>
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
    </div>
    <div style="clear:both"></div>
</div>