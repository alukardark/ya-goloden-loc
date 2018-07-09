<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>


<? if (!empty($arResult)): ?>
    <ul id="multilevel-menu">

    <?
    $previousLevel = 0;

foreach ($arResult as $arItem): ?>

    <?

    //$hideArr = array('Половинки американских пицц (сладкие)');
    //if(in_array($arItem['TEXT'], $hideArr) and !in_array($_SERVER['REMOTE_ADDR'], array('158.46.22.198', '46.149.226.65', '158.46.18.231', '176.197.241.81'))) continue;

    if ($arItem['TEXT'] == 'Роллы для сетов') {
        continue;
    }

    ?>

    <? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
        <?= str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"])); ?>
    <? endif ?>

    <? if ($arItem["IS_PARENT"]): ?>

    <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
    <li class="<? if ($arItem["SELECTED"]):?>root-item-selected<? else:?>root-item<? endif ?>"><a
        href="<?= $arItem["LINK"] ?>"><span><?= $arItem["TEXT"] ?></span></a>
    <ul class="root-item">
    <? else: ?>
    <li class="parent<? if ($arItem["SELECTED"]):?> item-selected<? endif ?>"><a
        href="<?= $arItem["LINK"] ?>"><span><?= $arItem["TEXT"] ?></span></a>
    <ul>
    <? endif ?>

    <? else:?>

        <? if ($arItem["PERMISSION"] > "D"):?>

            <? if ($arItem["DEPTH_LEVEL"] == 1):?>
                <li class="<? if ($arItem["SELECTED"] && !isset($_GET['cheap_rolls'])):?>root-item-selected<? else:?>root-item<? endif ?>">
                    <a href="<?= $arItem["LINK"] ?>"><span><?= $arItem["TEXT"] ?></span></a></li>
                <? if ($arItem["LINK"] == '/menu/grilled-roll/') { ?>
                    <li class="<? if (isset($_GET['cheap_rolls'])): ?>root-item-selected<? else: ?>root-item<? endif ?>">
                        <a href="/menu/roll/?cheap_rolls"><span>Эконом роллы</span></a></li>
                <? } ?>
                <? if ($arItem["LINK"] == '/menu/grilled-roll/' && $_GET[d] == 1) { ?>
                    <li class="<? if (isset($_GET['j_menu'])): ?>root-item-selected<? else: ?>root-item<? endif ?>"><a
                            href="/menu/?j_menu"><span>Всё японское меню</span></a></li>
                <? } ?>
            <? else:?>
                <li <? if ($arItem["SELECTED"]):?> class="item-selected"<? endif ?>><a
                        href="<?= $arItem["LINK"] ?>"><span><?= $arItem["TEXT"] ?></span></a></li>
            <? endif ?>

        <? else:?>

            <? if ($arItem["DEPTH_LEVEL"] == 1):?>
                <li class="<? if ($arItem["SELECTED"]):?>root-item-selected<? else:?>root-item<? endif ?>"><a href=""
                                                                                                              title="<?= GetMessage("MENU_ITEM_ACCESS_DENIED") ?>"><span><?= $arItem["TEXT"] ?></span></a>
                </li>
            <? else:?>
                <li class="denied"><a href=""
                                      title="<?= GetMessage("MENU_ITEM_ACCESS_DENIED") ?>"><span><?= $arItem["TEXT"] ?></span></a>
                </li>
            <? endif ?>

        <? endif ?>

    <? endif ?>

    <? $previousLevel = $arItem["DEPTH_LEVEL"]; ?>

<? endforeach ?>

    <? if ($previousLevel > 1)://close last item tags?>
        <?= str_repeat("</ul></li>", ($previousLevel - 1)); ?>
    <? endif ?>

    <?
    global $city, $cities;
$selected = substr_count($_SERVER['SCRIPT_URL'], 'vse-yaponskoe-menu') ? '-selected' : '';
    ?>

    </ul>
<? endif ?>