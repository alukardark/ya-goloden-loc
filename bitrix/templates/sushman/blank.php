<?
    CModule::IncludeModule('iblock');    
    CModule::IncludeModule('catalog');    
    $auto_city = getCity();
    global $cities;
    if (!$auto_city) {
        $auto_city=1;
    }
    $json_cities = array();
    $host = $_SERVER["SERVER_NAME"];
    $host= str_replace("test2.","",str_replace("www.","",$host));
    foreach($cities as $c) {
        $host= str_replace(str_replace("nkz","nk",$c["CODE"]).".","",$host);
    }
    foreach($cities as $k => $c) {
        $json_cities[$k] = "http://".str_replace("nkz","nk",$c["CODE"]).".".$host;
    }
?>
<!DOCTYPE html>
<html lang="ru" id="blank-page">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="robots" content="nofollow,noindex"/> 
        <link rel="shortcut icon" href="<?=SUSHMAN_MAIN_PATH ?>/favicon.ico" type="image/x-icon">
        <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
        <link media="all" rel="stylesheet" href="<?=SUSHMAN_MAIN_PATH ?>/reset.css?v=1" />
        <link media="all" rel="stylesheet" href="<?=SUSHMAN_MAIN_PATH ?>/jquery.selectBox.css?v=1" />
	<?$APPLICATION->ShowHead();?>
	<title>Сушман & Пиццман - выбор города</title>
        <link rel="stylesheet" href="<?=SUSHMAN_MAIN_PATH ?>/js/libs/fancybox/jquery.fancybox.css?v=2.1.5.1" type="text/css" media="screen" />
            

        <script type="text/javascript" src="<?=SUSHMAN_MAIN_PATH ?>/js/libs/jquery-1.11.0.min.js?v=1"></script>           
        <script type="text/javascript" src="<?=SUSHMAN_MAIN_PATH ?>/js/libs/jquery.placeholder.js?v=1"></script>
        <script type="text/javascript" src="<?=SUSHMAN_MAIN_PATH ?>/js/libs/jquery.mb.browser.min.js?v=1"></script>
        <script type="text/javascript" src="<?=SUSHMAN_MAIN_PATH ?>/js/libs/jquery.selectBox.js?v=1"></script>
        <script type="text/javascript" src="<?=SUSHMAN_MAIN_PATH ?>/js/libs/fancybox/jquery.fancybox.js?v=2.1.5.1"></script> 
        <script type="text/javascript" src="<?=SUSHMAN_MAIN_PATH ?>/js/libs/jquery.cookie.js?v=1"></script>
        <script type="text/javascript" src="<?=SUSHMAN_MAIN_PATH ?>/js/libs/URI.js"></script>
        
        <script type="text/javascript" src="<?=SUSHMAN_MAIN_PATH ?>/js/blank.js"></script>
        <script type="text/javascript">
            var json_cities = <?=json_encode($json_cities)?>;
        </script>
    </head>
    <body>
        <div id="mainbg"></div>
        <div class='popup-form' data-auto-city="<?=$auto_city?>" style='color:#fff;position:absolute;left:50%;top:50%;z-index:200;margin-left:-325px;margin-top:-130px;height:260px;'>
            <h2>Выбор города</h2>
            <form style='height:160px;'>
                <p style='text-align:center;color:#fff;font-size:22px;line-height:28px;padding:20px 50px;'>Ваш город определен как <span class='city-name'><?=$cities[$auto_city]["NAME"]?></span></p>
                <div class='fields'>
                    <span style="margin-right:15px;">Выберите город:</span>
                    <div class="city-block" style="display:inline-block;position:relative;">
                            <?
                                $_GET['sort_id'] = "UF_SORT";
                                $_GET['sort_type'] = "ASC";
                                $APPLICATION->IncludeComponent("bitrix:highloadblock.list","cities",Array(
                                    "BLOCK_ID" => "4",
                                )
                            );?>
                    </div><br/>
                    <input type='submit' value='Перейти'/>
                </div>
            </form>
        </div>
        <div id="wrapper"><div id="inner-wrapper">
                <div id="sitebg"></div>

                <header>
                    <div class="center-col">
                        <a id="logo" href="/" title="На главную"><span>Сушман&Пиццман</span></a>
                        <div class="info-block">
                            <div class="vk-block"><span class="semi">Официальная группа Вконтакте</span><br/>
                            <?
                                $APPLICATION->IncludeFile("/include/vk_nkz.php", Array(), Array(
                                    "MODE"      => "text",                   
                                    "NAME"      => "Редактирование ссылки ВКонтакте",
                                ));
                            ?>
                            <div class="phone-block"><span class="semi">Доставка по тел.:</span><br/><span class="phone"><?
    $APPLICATION->IncludeFile("/include/phone_nkz.php", Array(), Array(
        "MODE"      => "text",                   
        "NAME"      => "Редактирование телефона",
    ));
?></span></div>
                        </div>
                            
                        <div class="center-block city-block">
                            <form method="post">
                            <?
                                $_GET['sort_id'] = "UF_SORT";
                                $_GET['sort_type'] = "ASC";
                                $APPLICATION->IncludeComponent("bitrix:highloadblock.list","cities",Array(
                                    "BLOCK_ID" => "4",
                                )
                            );?>
                            </form>
                            <br/><br/>
                            <a id="promo-link" href="/delivery/" style="margin-top:0;"><span class="bg"></span><span class="t">Модная доставка</span></a>
                        </div>
                        <div class="head-line"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="center-col" style="z-index:5;">
                        <div class="mainmenu">
                                <?$APPLICATION->IncludeComponent("bitrix:menu", "main", Array(
                                            "ROOT_MENU_TYPE" => "top",
                                            "MENU_CACHE_TYPE" => "N",
                                            "MENU_CACHE_TIME" => "3600",
                                            "MENU_CACHE_USE_GROUPS" => "Y",
                                            "MAX_LEVEL" => "1",
                                            "DELAY" => "N",
                                        ),
                                        false
                                );?>
                                <div class="clear"></div>
                        </div>
                        
                    </div>
                </header>
                <section>
                    <h2 style="display:none;">A</h2>
                    <div class="center-col">
                        <div class="left-col">
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/promo.php"), false);?>
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/recommends.php"), false);?>
                        </div>
                        <div class="main-col">
                        
                         <?$arrFilter = Array(
"SECTION_CODE" => "main_slider_{$cities[$city]["CODE"]}"
);?>  
                            <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"main_slider", 
	array(
		"IBLOCK_TYPE" => "photos",
		"IBLOCK_ID" => "sliders",
		"SECTION_CODE" => "main_slider_{$cities[$city]["CODE"]}",
		"SET_TITLE" => "N",
		"NEWS_COUNT" => "",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ACTIVE_FROM",
		"SORT_ORDER2" => "ASC",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "DETAIL_TEXT",
			4 => "DETAIL_PICTURE",
			5 => "PROPERTY_HREF",
			6 => "PROPERTY_REAL_PICTURE",
			7 => "PROPERTY_HREF_TARGET",
			8 => "PROPERTY_ADD_BTN",
			9 => "",
		),
		"FILTER_NAME" => "arrFilter",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "Y",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>
                            <?$APPLICATION->IncludeComponent("bitrix:menu", "main_cats", Array(
                                        "ROOT_MENU_TYPE" => "left",
                                        "MENU_CACHE_TYPE" => "N",
                                        "MENU_CACHE_TIME" => "3600",
                                        "MENU_DD" => true,
                                        "MENU_CACHE_USE_GROUPS" => "Y",
                                        "MAX_LEVEL" => "1",
                                        "DELAY" => "N",
                                        "USE_EXT" => "Y"
                                    ),
                                    false
                                    );?><div class="clear"></div>

                            
                        </div>
                        <div class="clear"></div>
                    </div>
                </section>
                <footer>
                    <div class="inside-cont center-col">
                        <div class="mainmenu">
                                <?$APPLICATION->IncludeComponent("bitrix:menu", "main", Array(
                                            "ROOT_MENU_TYPE" => "bottom",
                                            "MENU_CACHE_TYPE" => "N",
                                            "MENU_CACHE_TIME" => "3600",
                                            "MENU_CACHE_USE_GROUPS" => "Y",
                                            "MAX_LEVEL" => "1",
                                            "DELAY" => "N",
                                        ),
                                        false
                                );?>
                        </div>
                    </div>
                    <div class="outside-cont">
                        <div class="center-col">
                            <div class="card-block">
                                <span>оплата по карте</span><br/>
                                <span class="icon-card icon-visa"></span><span class="icon-card icon-mastercard"></span>
                            </div>
                            <div class="right-block valigned">
                                <span><?
// включаемая область для раздела
$APPLICATION->IncludeFile("/include/ogrn.php", Array(), Array(
    "MODE"      => "text",                                           // будет редактировать в веб-редакторе
    "NAME"      => "Редактирование телефона",      // текст всплывающей подсказки на иконке
    ));
?></span>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </footer>
                
        </div></div>
        <div class="btnUp"></div>
        
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter29393590 = new Ya.Metrika({id:29393590,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
                    });
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/29393590?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-61370767-1', 'auto');
  ga('send', 'pageview');

</script>
        
    </body>
</html>