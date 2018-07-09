<?
SetGlobalVars();
$wtitle = $APPLICATION->GetDirProperty("title");
$is_root = $APPLICATION->GetCurDir() == "/";
global $city, $cities;
if (!$city) {
    include("blank.php");
    die;
}

include_once($_SERVER['DOCUMENT_ROOT'] . '/modal.php');

$_city = $cities[$city]["CODE"] == 'nk' ? 'Новокузнецке' : ($cities[$city]["CODE"] == 'nsk' ? 'Новосибирске' : 'Кемерове');
$_city2 = $cities[$city]["CODE"] == 'nk' ? 'Новокузнецк' : ($cities[$city]["CODE"] == 'nsk' ? 'Новосибирск' : 'Кемерове');

$_city3 = $cities[$city]["CODE"] == 'nkz' ? 'Новокузнецке' : ($cities[$city]["CODE"] == 'nsk' ? 'Новосибирске' : 'Кемерове');

$_SEO = array(
    'title' => 'Дешевые суши в ' . $_city . '. Недорогие суши.',
    'description' => '«SUSHMAN & PIZZMAN» предлагает недорогие суши в ' . $_city . '. Сделать заказ дешевых суши можно на сайте.',
    'keywords' => 'суши, недорого, дешево, эконом роллы, купить, заказать, доставка, ' . $_city2
);
?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<!--		<meta name="viewport" content="width=device-width, maximum-scale=1.0">-->
        <link rel="shortcut icon" href="<?= SUSHMAN_MAIN_PATH ?>/favicon.ico" type="image/x-icon">
        <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE"/>
        <link media="all" rel="stylesheet" href="<?= SUSHMAN_MAIN_PATH ?>/reset.css?v=1"/>
        <link media="all" rel="stylesheet" href="<?= SUSHMAN_MAIN_PATH ?>/idangerous.swiper.css?v=1"/>
        <link media="all" rel="stylesheet" href="<?= SUSHMAN_MAIN_PATH ?>/chosen.css?v=1"/>
        <link media="all" rel="stylesheet" href="<?= SUSHMAN_MAIN_PATH ?>/animate.css?v=1"/>
        <link media="all" rel="stylesheet" href="<?= SUSHMAN_MAIN_PATH ?>/flipclock.css"/>
        <link media="all" rel="stylesheet" href="<?= SUSHMAN_MAIN_PATH ?>/jquery.selectBox.css?v=1"/>
        <? $APPLICATION->ShowHead(); ?>
        <title><? if (!isset($_GET['cheap_rolls'])) {
                $APPLICATION->ShowTitle();
            } else {
                echo $_SEO['title'];
                $APPLICATION->SetPageProperty("description", $_SEO['description']);
                $APPLICATION->SetPageProperty("keywords", $_SEO['keywords']);
            } ?></title>
        <link rel="stylesheet" href="<?= SUSHMAN_MAIN_PATH ?>/js/libs/fancybox/jquery.fancybox.css?v=2.1.5.1"
              type="text/css" media="screen"/>

        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery-1.11.0.min.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.scrollTo.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.placeholder.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.mb.browser.min.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.imgpreload.min.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.selectBox.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/idangerous.swiper.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.form.min.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.validate.min.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.imageMask.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.maskedInput.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.inputmask.bundle.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.noty.packaged.js?v=1"></script>
        <script type="text/javascript"
                src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/fancybox/jquery.fancybox.js?v=2.1.5.1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/swfobject.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/jquery.cookie.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/chosen.jquery.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/libs/URI.js"></script>

        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/jccatalogelement.js?v=1"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/snowfall.jquery.min.js"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/flipclock.min.js"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/main.js?v=1.3.3"></script>
        <script type="text/javascript" src="<?= SUSHMAN_MAIN_PATH ?>/js/actions_banner.js?v=1.3.3"></script>

       

        <!--[if lt IE 9]>
        <script type="text/javascript" src="<?=SUSHMAN_MAIN_PATH ?>/js/libs/html5shiv.js"></script>
        <script type="text/javascript" src="<?=SUSHMAN_MAIN_PATH ?>/js/libs/css3-mediaqueries.js"></script>
        <![endif]-->
        <!--- <script src="//api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script> -->

    </head>
<?
CModule::includeModule('ws.projectsettings');
$ips = array('91.204.200.214', '184.22.40.93');
if (in_array($_SERVER['REMOTE_ADDR'], $ips) or WS_PSettings::getFieldValue("NEW_YEAR_DESIGN")) {
    $nyClass = 'new-year-design';
}
?>
<body
    class="<?= $bodyModalClass ?> <?= $nyClass ?> <?= ($body_class ? $body_class : 'inner') ?><?= ($cities[$city]["ORDER_ENABLED"] == 1 ? "" : " no-order") ?>">
<? $APPLICATION->ShowPanel(); ?>
<?
$APPLICATION->IncludeFile("/include/privacy-agreement.php", array(), array("SHOW_BORDER" => false));
?>

<!-- BEGIN JIVOSITE INTEGRATION WITH ROISTAT -->
<script>
(function(w, d, s, h) {
var p = d.location.protocol == "https:" ? "https://" : "http://";
var u = "/static/marketplace/JivoSite/script.js";
var js = d.createElement(s); js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
})(window, document, 'script', 'cloud.roistat.com');
</script>
<!-- END JIVOSITE INTEGRATION WITH ROISTAT -->

<?

$APPLICATION->IncludeFile("/include/btn_vk_{$cities[$city]["CODE"]}.php", Array(), Array(
    "MODE" => "text",
    "NAME" => "Редактирование кнопки ВКонтакте",
));
$APPLICATION->IncludeFile("/include/btn_instagram.php", Array(), Array(
    "MODE" => "text",
    "NAME" => "Редактирование кнопки Instagram",
));
?>
<?
//$APPLICATION->IncludeFile("/include/btn_flamp_{$cities[$city]["CODE"]}.php", Array(), Array(
//    "MODE" => "text",
//    "NAME" => "Редактирование кнопки Flamp",
//));
?>

    <!-- BEGIN JIVOSITE CODE {literal} -->
    <script type='text/javascript'>
        (function () {
            var widget_id = 'Kc4QqEeBbu';
            var d = document;
            var w = window;

            function l() {
                var s = document.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = '//code.jivosite.com/script/widget/' + widget_id;
                var ss = document.getElementsByTagName('script')[0];
                ss.parentNode.insertBefore(s, ss);
            }

            if (d.readyState == 'complete') {
                l();
            } else {
                if (w.attachEvent) {
                    w.attachEvent('onload', l);
                } else {
                    w.addEventListener('load', l, false);
                }
            }
        })();</script>
    <!-- {/literal} END JIVOSITE CODE -->

    <a href="#feedback" data-popup_href="/feedback/" id="feedback-btn" class="ajaxform"></a>
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/utils.php");
$arVariables = getCatalogVariables();
if ($arVariables["SECTION_CODE"]) {
    $bg_class = '';
    $res = CIBlockSection::GetList(array(), array(
        "IBLOCK_ID" => 2,
        "CODE" => $arVariables["SECTION_CODE"],
    ), false, array("ID", "IBLOCK_ID", "NAME", "UF_BG"));
    if ($row = $res->GetNext()) {
        $rsBg = CUserFieldEnum::GetList(array(), array(
            "ID" => $row["UF_BG"],
        ));
        if ($arBg = $rsBg->GetNext()) {
            $bg_class = $arBg["XML_ID"];
        }
    }
}
?>
<div id="wrapper"<?= ($bg_class ? " class='bg-{$bg_class}'" : "") ?>>
    <div id="inner-wrapper">
    <div id="sitebg"></div>

    <header>
        <div class="ny-header">
            <div class="title">До Нового 2018 года осталось:</div>
            <div class="timer-wrapper">
                <div class="clock"></div>
                <script type="text/javascript">
                    var clock;

                    $(document).ready(function () {

                        // Grab the current date
                        var currentDate = new Date();

                        // Set some date in the future. In this case, it's always Jan 1
                        var futureDate = new Date(currentDate.getFullYear() + 1, 0, 1);

                        // Calculate the difference in seconds between the future and current date
                        var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;

                        // Instantiate a coutdown FlipClock
                        clock = $('.clock').FlipClock(diff, {
                            clockFace: 'DailyCounter',
                            countdown: true,
                            language: 'ru-ru',
                        });
                    });

                    (function ($) {

                        /**
                         * FlipClock Russian Language Pack
                         *
                         * This class will used to translate tokens into the Russian language.
                         *
                         */

                        FlipClock.Lang.Russian = {

                            'years': 'лет',
                            'months': 'месяцев',
                            'days': 'дней',
                            'hours': 'часов',
                            'minutes': 'минут',
                            'seconds': 'секунд'

                        };

                        /* Create various aliases for convenience */

                        FlipClock.Lang['ru'] = FlipClock.Lang.Russian;
                        FlipClock.Lang['ru-ru'] = FlipClock.Lang.Russian;
                        FlipClock.Lang['russian'] = FlipClock.Lang.Russian;

                    }(jQuery));
                </script>
            </div>
        </div>
        <div class="center-col">

            <a id="logo" href="/" title="На главную"></a>

            <div class="info-block">
                <a href="#feedback" data-popup_href="/feedback/" id="feedback-btn2" class="ajaxform"
                   title="Написать директору"></a>

                <div class="vk-block"><span class="semi">Официальная группа Вконтакте</span><br/>
                    <?
                    $APPLICATION->IncludeFile("/include/vk_{$cities[$city]["CODE"]}.php", Array(), Array(
                        "MODE" => "text",
                        "NAME" => "Редактирование ссылки ВКонтакте",
                    ));
                    ?>
                    <?
                    $a = 'в ' . ($cities[$city]["CODE"] == 'kem' ? 'Кемерове' : $cities[$city]["NAME"] . 'е');
                    ?>
                    <div class="phone-block"><span class="semi">Доставка <?= $a ?> по тел.:
							<span class="mobile" style="color: white !important;padding-left: 15px;">
								<?
                                //                                $APPLICATION->IncludeFile("/phone_subst/subst.php",
                                //                                    array('phone_key' => "mobile_phone_{$cities[$city]["CODE"]}"));

                                ?>
							</span></span><br/><span class="phone"><?


                            //                            $APPLICATION->IncludeFile("/phone_subst/subst.php",
                            //                                array('phone_key' => "phone_{$cities[$city]["CODE"]}"));

                            switch ($cities[$city]["CODE"]) {
                                case 'kem':
                                    echo '<a href="tel:83842900007">+7 (3842) 900-007</a>';
                                    break;
                                case 'nsk':
                                    echo '<a href="tel:83833195555">+7 (383) 319-55-55</a>';
                                    break;
                                default:
                                    echo '<a href="tel:83843200999">+7 (3843) 200-999</a>';
                                    break;
                            }
                            ?></span></div>
                    <? if (!class_exists('Recall')) {
                        $APPLICATION->IncludeFile("/include/recall.php", array(), array("SHOW_BORDER" => false));
                    } ?>
                </div>
                <div class="center-block city-block">
                    <div class="header-form-wrapper">
                        <form method="post">
                            <?
                            $_GET['sort_id'] = "UF_SORT";
                            $_GET['sort_type'] = "ASC";
                            $APPLICATION->IncludeComponent("bitrix:highloadblock.list", "cities", Array(
                                    "BLOCK_ID" => "4",
                                )
                            ); ?>
                        </form>
                    </div>

                    <? if ($cities[$city]["CODE"] == 'nkz') { ?>

                        <div class="header-payment" style="display:none;">
                            <div class="text">Принимаем к оплате карты:</div>
                            <img src="<?= SUSHMAN_MAIN_PATH ?>/img/header-visa.png" title="Visa" alt="Visa"/>
                            <img src="<?= SUSHMAN_MAIN_PATH ?>/img/header-mc.png" title="MasterCard" alt="MasterCard"/>
                        </div>
                        <div class="clear"></div>

                    <? } ?>

                    <?php


                    /*if ($_GET[d]) {*/
                    echo '
                            <a id="promo-link" href="/delivery/"><span class="bg"></span><span class="t">Модная доставка суши в ' . $_city3 . '</span></a>';
                    echo '<div class="delivery_time">' . $cities[$city]['TIME_DELIVERY'] . '</div>';
                    /*}else {echo '

                    <a id="promo-link" href="/delivery/" style="margin-top:0;"><span class="bg"></span><span class="t">Модная доставка</span></a>';}*/
                    ?>

                </div>
                <div class="head-line"></div>
                <div class="clear"></div>
            </div>
            <div class="center-col" style="z-index:5;">
                <div class="mainmenu">
                    <? $APPLICATION->IncludeComponent("bitrix:menu", "main", Array(
                        "ROOT_MENU_TYPE" => "top",
                        "MENU_CACHE_TYPE" => "N",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MAX_LEVEL" => "1",
                        "DELAY" => "N",
                    ),
                        false
                    ); ?>
                    <div class="clear"></div>
                </div>
                <?
                Cmodule::IncludeModule("catalog");
                if ($cities[$city]["ORDER_ENABLED"] == 1) { ?>
                    <? $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "basket", array(
                        "PATH_TO_BASKET" => "/cart/",
                        "PATH_TO_ORDER" => "/cart/",
                        "SHOW_IMAGE" => "N",
                        "SHOW_PRICE" => "N",
                        "SHOW_PERSONAL_LINK" => "N",
                        "SHOW_NUM_PRODUCTS" => "Y",
                        "SHOW_TOTAL_PRICE" => "Y",
                        "SHOW_PRODUCTS" => "Y",
                        "POSITION_FIXED" => "N",
                        "SHOW_DELAY" => "N",
                        "SHOW_NOTAVAIL" => "N",
                        "SHOW_SUBSCRIBE" => "N",
                    ),
                        false,
                        array()
                    ); ?>
                <? } ?>

            </div>
    </header>

<? if ($APPLICATION->GetCurDir() != "/" || ERROR_404 == "Y"): ?>
    <?
    global $is_catalog_page;
    if ($is_catalog_page) { ?>
        <section class="menu">
        <div class="center-col">
    <? } else { ?>
        <? global $inner_section_class; ?>
        <section id="inner-page"<?= ($inner_section_class ? " class='{$inner_section_class}'" : "") ?>>
        <? if (ERROR_404 == "Y") { ?>
            <div id="breadcrumbs" class="center-col">&nbsp;</div>
        <? } else { ?>
            <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", "", Array(
                "START_FROM" => "0",
                // Номер пункта, начиная с которого будет построена навигационная цепочка
                "PATH" => "",
                // Путь, для которого будет построена навигационная цепочка (по умолчанию, текущий путь)
                "SITE_ID" => "",
                // Cайт (устанавливается в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
            ),
                false
            ); ?>
        <? } ?>
        <div class="center-col page-content static">
        <h2<?= $header_class ? " class='{$header_class} line-append'" : " class='line-append'" ?>><? $APPLICATION->ShowTitle(false); ?></h2>
    <? } ?>
<? endif ?>