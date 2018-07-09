<?
    require_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/utils.php");
###    $res = getRecommendsList();
    $res = getRecommendsList(getCatalogVariables());
    $res->NavStart();
    if ($res->NavRecordCount>0) {
        global $has_main_promo;
?>
<div class="inner-block<?=($has_main_promo ? "" : " nopromo")?>">
    <div class="title">Рекомендуем попробовать</div>
    <div class="bcont">
        <ul class="recommends">
<?            
    $i = 0;
    while($ob = $res->GetNextElement()){ 
        $arItem = $ob->GetFields();  
        $strTitle = htmlspecialcharsEx($arItem["NAME"]);
        $preview = "";
        if ($arItem["PREVIEW_PICTURE"]) {
            $arUserPhoto = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]);
            $preview = CFile::ResizeImageGet($arUserPhoto,array("width" => 65,"height" => 65),BX_RESIZE_IMAGE_EXACT );
        } else {
            //$preview['src'] = '/bitrix/templates/sushman/img/no-photo.jpg';
        }
?>
            <li><a href="<?=$arItem['DETAIL_PAGE_URL']; ?>" data-popup_href="/menu/detail.php?ELEMENT_ID=<?=$arItem["ID"]?>">
                <? if ($preview['src']) { ?><span class="item-pic" style="background-image:url(<?=$preview['src']?>);"></span><? } ?>
                <? if ($arItem['SORT']) { ?><span class="item-num"><?=str_pad($arItem['SORT'],4,'0',STR_PAD_LEFT)?></span><? } //PROPERTY_ARTNUMBER_VALUE ?> 
                <span class="valigned"><span class="item-title"><?=$arItem["NAME"]?></span></span>
            </a></li>

<?
        $i++;
        if ($i>3)
        break;
    }
?>
        </ul>
    </div>
</div>
<? } ?>