<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->IncludeLangFile('template.php');
global $city, $cities;
$cityCode = $cities[$city]["CODE"];
?>

    <a onclick="ga('send', 'event', 'Cart <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>', 'Visit <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>')" href="<?= $arParams['PATH_TO_BASKET'] ?>"
       class="bx_small_cart"<? if (!$arResult['NUM_PRODUCTS']) { ?> style="opacity:1 !important;filter:Alpha(opacity=100);background-image:url(/bitrix/templates/sushman/img/icon-cart-disabled.png);" onclick="javascript:return false;"<? } ?>>
   
    <span class="cart-inner">
	<span class="basket-link"><?= GetMessage('TSB1_CART') ?>:</span>
        <? if ($arParams['SHOW_NUM_PRODUCTS'] == 'Y' && ($arResult['NUM_PRODUCTS'] > 0 || $arParams['SHOW_EMPTY_VALUES'] == 'Y')): ?>
            <?
            $cnt = 0;
            foreach ($arResult["CATEGORIES"] as $category => $items) {
                if (empty($items)) {
                    continue;
                }
                foreach ($items as $v) {
                    $cnt += $v["QUANTITY"];
                }
            }
            ?>


            <? $productS = $component->BasketNumberWordEndings($cnt); ?>
            <span class="basket-quantity"><?= $cnt . ' товар' . $productS ?></span>
        <? endif ?>
        <? if ($arParams['SHOW_TOTAL_PRICE'] == 'Y'): ?>
            <? if ($arResult['NUM_PRODUCTS'] > 0 || $arParams['SHOW_EMPTY_VALUES'] == 'Y'): ?>
                <br/><span class="basket-price"><?= $arResult['TOTAL_PRICE'] ?></span>
            <? endif ?>
        <? endif ?>
    </span>

        <span class="arrow-down"></span>
        <?
        global $city, $cities;
        //echo $city ? $city : 't';
        $SECTION_ID = 9999;
        require($_SERVER['DOCUMENT_ROOT'] . '/include/show-discount-prices.php');
        if ($discount['ID']) {
            echo '<span class="cart-note">* Сумма без учета скидки</span>';
        }
        ?>

    </a>
<? if ($arParams["SHOW_PRODUCTS"] == "Y" && $arResult['NUM_PRODUCTS'] > 0): ?>
    <div class="bx_item_listincart<?
    $topNumber = 3;
    if ($arParams['SHOW_TOTAL_PRICE'] == 'N') {
        $topNumber--;
    }
    if ($arParams['SHOW_PERSONAL_LINK'] == 'N') {
        $topNumber--;
    }
    if ($topNumber < 3)
        echo " top$topNumber" ?>">

        <? if ($arParams["POSITION_FIXED"] == "Y"): ?>
            <div id="bx_cart_block_status" class="status"
                 onclick="sbbl.toggleExpandCollapseCart()"><?= GetMessage("TSB1_EXPAND") ?></div>
        <? endif ?>

        <div id="bx_cart_block_products" class="bx_itemlist_container">
            <? foreach ($arResult["CATEGORIES"] as $category => $items):
                if (empty($items)) {
                    continue;
                }
                ?>
                <? foreach ($items as $v): ?>
                <div class="bx_itemincart">
                    <div class="bx_item_delete" onclick="sbbl.removeItemFromCart(<?= $v['ID'] ?>)"
                         title="<?= GetMessage("TSB1_DELETE") ?>"></div>
                    <? if ($arParams["SHOW_IMAGE"] == "Y"): ?>
                        <div class="bx_item_img_container">
                            <? if ($v["PICTURE_SRC"]): ?>
                                <? if ($v["DETAIL_PAGE_URL"]): ?>
                                    <a href="<?= $v["DETAIL_PAGE_URL"] ?>"><img src="<?= $v["PICTURE_SRC"] ?>"
                                                                                alt="<?= $v["NAME"] ?>"></a>
                                <? else: ?>
                                    <img src="<?= $v["PICTURE_SRC"] ?>" alt="<?= $v["NAME"] ?>"/>
                                <? endif ?>
                            <? endif ?>
                        </div>
                    <? endif ?>
                    <div class="bx_item_title">
                        <? if ($v["DETAIL_PAGE_URL"]): ?>
                            <a href="<?= $v["DETAIL_PAGE_URL"] ?>"><?= $v["NAME"] ?></a>
                        <? else: ?>
                            <?= $v["NAME"] ?>
                        <? endif ?>
                    </div>
                    <? if (true):/*$category != "SUBSCRIBE") TODO */
                        ?>
                        <? if ($arParams["SHOW_PRICE"] == "Y"): ?>
                        <div class="bx_item_price">
                            <strong><?= $v["PRICE_FMT"] ?></strong>
                            <? if ($v["FULL_PRICE"] != $v["PRICE_FMT"]): ?>
                                <span class="bx_item_oldprice"><?= $v["FULL_PRICE"] ?></span>
                            <? endif ?>
                        </div>
                    <? endif ?>
                        <? if ($arParams["SHOW_SUMMARY"] == "Y"): ?>
                        <div class="bx_item_col_quantity">
                            <?= $v["QUANTITY"] ?>
                        </div>
                        <div class="bx_item_col_summ">
                            <?= str_replace("руб.", "<span class='ico-rub'></span>", $v["SUM"]) ?>
                        </div>
                    <? endif ?>
                    <? endif ?>
                </div>
            <? endforeach ?>
            <? endforeach ?>
            <? if ($cnt > 0 && $arResult['TOTAL_PRICE'] > 0) { ?>
                <div class="bx_itemincart total-row">
                    <div class="bx_item_title"></div>
                    <div class="bx_item_col_quantity">
                        <?= $cnt ?>
                    </div>
                    <div class="bx_item_col_summ total">
                        <?= str_replace("руб.", "<span class='ico-rub'></span>", $arResult['TOTAL_PRICE']) ?>
                    </div>
                </div>

            <? } ?>

        </div>

        <?

        if (
            $_SERVER['REMOTE_ADDR'] == '158.46.22.198' or
            $_SERVER['REMOTE_ADDR'] == '46.149.226.65'
        ) {
            require($_SERVER['DOCUMENT_ROOT'] . '/include/show-discount-prices.php');
            if ($discount['ID']) {
                echo '<div class="sum-note">* Сумма указана без учета скидки</div>';
            }
        }

        ?>

        <? if ($arParams["PATH_TO_ORDER"] && $arResult["CATEGORIES"]["READY"]): ?>
            <div class="bx_button_container">

                <a href="<?= $arParams["PATH_TO_ORDER"] ?>" class="bx_bt_button_type_2 bx_medium">
                    <?= GetMessage("TSB1_2ORDER") ?>
                </a>
            </div>
        <? endif ?>

    </div>

    <? if ($arParams["POSITION_FIXED"] == "Y"): ?>
        <script>sbbl.fixCartAfterAjax()</script>
    <? endif ?>

<? endif ?>