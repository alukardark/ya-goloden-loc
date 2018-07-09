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
                <div id="main-slider">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
<?foreach($arResult["ITEMS"] as $k => $arItem):?>
    <div class="swiper-slide slide-right">
        <?php
            if ($arItem["PROPERTY_REAL_PICTURE_VALUE"]) {
                $arPhoto = CFile::GetFileArray($arItem["PROPERTY_REAL_PICTURE_VALUE"]);
                $pic = CFile::ResizeImageGet($arPhoto,array("width" => 721,"height" => 377),BX_RESIZE_IMAGE_EXACT );
            } else {
                $pic = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"],array("width" => 721,"height" => 377),BX_RESIZE_IMAGE_EXACT );
            }
        ?>
        <div class="pic" style="background-image:url(<?=$pic['src']?>)">
            <?
                $target = '';
                if($arItem["PROPERTY_HREF_TARGET_VALUE"] == "Y") {
                    $target = 'target="_blank"';
                }
            ?>
            <a href="<?=trim($arItem["PROPERTY_HREF_VALUE"])?>" <?=$target?> title='<?=trim($arItem[NAME])?>' class="div-link"></a>
            <?
                if($arItem["PROPERTY_ADD_BTN_VALUE"] == "Y") {
                    echo "<div class='more-btn'>Узнать подробнее</div>";
                }
            ?>
        </div>
        <? if ($arItem["PREVIEW_TEXT"]) {
                    $txt = "<span>".str_replace("\n","</span><br/><span>",trim($arItem["PREVIEW_TEXT"],"\r\n "))."</span>";
        ?>
        <div class="desc valigned"><div class="vi">
            <span class="title"><?=$txt?></span>                                                    
            <? if (trim($arItem["PROPERTY_HREF_VALUE"])) { ?>
            <a href="<?=trim($arItem["PROPERTY_HREF_VALUE"])?>" class="more">Узнать подробнее</a>
            <? } ?>
            </div>
        </div>
        
        <? } ?>
    </div>
<?endforeach;?>
                    </div>
                </div>
                <div class="swiper-pagination-wrapper"><div class="swiper-pagination"></div></div>
            </div>
<? } ?>