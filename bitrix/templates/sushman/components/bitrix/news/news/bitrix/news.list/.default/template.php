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
<?if (count($arResult["ITEMS"])>0) { ?>
            <ul id="news-list">
<?foreach($arResult["ITEMS"] as $k => $arItem):?>
                <? if($k>0 && $k%2==0) { ?>
                <li class="clear"><hr/></li>
                <? } ?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
                <li class="<?=($arParams["DISPLAY_PICTURE"]=="N" || !is_array($arItem["PREVIEW_PICTURE"]) ? "noimage" : "")?>">
                    <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
                    <div class="image">
                        <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?else:?><?endif;?>
                        <?php
                            $preview = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"],array("width" => 355,"height" => 155),BX_RESIZE_IMAGE_EXACT );
                        ?>
                            <img src="<?=$preview['src']?>" title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"/>
                        <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?></a><?else:?><?endif;?>
                    </div>
                    <?endif?>
                    <div class="inner">
                        <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                            <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                <a class="title" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a>
                            <?else:?>
                                <span class="title"><?echo $arItem["NAME"]?></span>
                            <?endif;?>
                        <?endif;?>
                        <div class="short-text">
                        <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                            <?echo $arItem["PREVIEW_TEXT"];?>
                        <?endif;?>
                        </div>
                        <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                            <div class="date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
                        <?endif?>
                    </div>
                    <div class="clear"></div>
                </li>
<?endforeach;?>
            </ul><div class="clear"></div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>

<? } ?>