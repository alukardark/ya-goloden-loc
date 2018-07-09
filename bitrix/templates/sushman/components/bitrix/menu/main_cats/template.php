<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <ul<?= $arParams["MENU_CLASS"] ? " class='{$arParams["MENU_CLASS"]}'" : " id='cats'" ?>>

        <?
        global $city;
        CModule::includeModule('ws.projectsettings');

        foreach ($arResult as $arItem):?>
            <?

            if ($city == 1 and WS_PSettings::getFieldValue('hide_rolli50', false) and $arItem['TEXT'] == 'Роллы -50%') continue;

//$hideArr = array('Половинки американских пицц (сладкие)');
//if(in_array($arItem['TEXT'], $hideArr) and !in_array($_SERVER['REMOTE_ADDR'], array('158.46.22.198', '46.149.226.65', '158.46.18.231', '176.197.241.81'))) continue;

            $alotoftextSections = array(
                'Пицца американская (на пышном тесте)',
                'Половинки американских пицц (на пышном тесте)',
                'Пицца итальянская (на тонком тесте)',
                'Половинки итальянских пицц (на тонком тесте)',
                'Кесадильи и самосы'
            );

            if ($arItem['TEXT'] == 'Роллы для сетов') continue;

            $strAlt = $arItem["PARAMS"]["alt"] ? $arItem["PARAMS"]["alt"] : $arItem["TEXT"];
            $strTitle = $arItem["PARAMS"]["title"] ? $arItem["PARAMS"]["title"] : $arItem["TEXT"];
            ?>
            <? $small_text = substr_count($arItem["TEXT"], ' ') ? 'small_text' : ''; ?>
            <li class="<? if ($arItem["SELECTED"]): ?>active<? else: ?>root-item e<? endif ?>">
                <a title="<?= $strTitle ?>" href="<?= $arItem["LINK"] ?>" class="overlay"></a>
                <a title="<?= $strTitle ?>"
                   href="<?= $arItem["LINK"] ?>"<?= ($arItem["PARAMS"]["CODE"] ? " class='item-{$arItem["PARAMS"]["CODE"]}'" : "") ?><?= ($arItem["PARAMS"]["TARGET"] ? " target='{$arItem["PARAMS"]["TARGET"]}'" : "") ?>>
                    <span class="title <? echo $small_text; ?>"><?= $arItem["TEXT"] ?></span>
                    <span
                        class="pic <? if (in_array($arItem['TEXT'], $alotoftextSections)) { ?>alotoftext<? } ?>"><? if ($arItem["PARAMS"]["IMAGE"]) { ?>
                            <img src="<?= $arItem["PARAMS"]["IMAGE"] ?>" alt="<?= $strAlt ?>"/><? } ?></span>
                </a>
            </li>

        <? endforeach ?>

    </ul>
<? endif ?>