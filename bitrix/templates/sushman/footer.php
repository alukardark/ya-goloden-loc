<?php
$browser_title = $APPLICATION->GetProperty("title");
global $cities, $city;
$arProp = $APPLICATION->GetPagePropertyList();
$arProp["title"] = $APPLICATION->GetProperty("title");
foreach ($arProp as $key => $value) {

    $doDecline = $city != 2;
    //$doDecline = true;

    $arr = array(
        'e' => $doDecline ? 'е' : '',
        'a' => $doDecline ? 'а' : '',
        'y' => $doDecline ? 'у' : '',
    );

    $value = html_entity_decode($value);
    $value = str_replace("#CITY#", $cities[$city]["NAME"], $value);
    $value = str_replace("#CITY2#", $cities[$city]["NAME"] . $arr['e'], $value);
    $value = str_replace("#CITY3#", $cities[$city]["NAME"] . $arr['a'], $value);
    $value = str_replace("#CITY4#", $cities[$city]["NAME"] . $arr['y'], $value);
    $value = str_replace("#LCCITY#", strtolower($cities[$city]["NAME"]), $value);
    $value = str_replace("#LCCITY2#", strtolower($cities[$city]["NAME"] . $arr['e']), $value);
    $value = str_replace("#LCCITY3#", strtolower($cities[$city]["NAME"] . $arr['a']), $value);
    $APPLICATION->SetPageProperty(strtolower($key), $value);
}
?>
<? if($APPLICATION->GetCurDir()!="/" || ERROR_404=="Y"):?>
                        <div class="clear"></div>
                    </div>
                </section>
<?endif ?>
<footer>
    <div class="inside-cont center-col">
        <div class="mainmenu">
            <? $APPLICATION->IncludeComponent("bitrix:menu", "main", Array(
                "ROOT_MENU_TYPE" => "bottom",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MAX_LEVEL" => "1",
                "DELAY" => "N",
            ),
                false
            ); ?>
        </div>
    </div>
    <div class="outside-cont">
        <ul class="footer-menu" style="padding-top:20px;width: 980px;margin: 0 auto;">
            <li style="float:left; margin-right:40px; margin-left: 40px;"><a href="/">Главная</a></li>
            <li style="float:left; margin-right:40px; margin-left: 40px;"><a href="/delivery/">Бесплатная доставка</a>
            </li>
            <li style="float:left; margin-right:40px; margin-left: 40px;"><a href="/menu/pizza/">Заказ пиццы</a></li>
            <li style="float:left; margin-right:40px; margin-left: 40px;"><a href="/menu/sushi/">Заказ суши</a></li>
            <li style="float:left; margin-right:40px; margin-left: 40px;"><a href="/menu/roll/?cheap_rolls">Эконом
                    роллы</a></li>
            <li style="float:left; margin-right:40px; margin-left: 40px;"><a href="/contacts/">Контакты</a></li>
        </ul>
        <div class="center-col">
            <div class="card-block paysystems" style="float:left;">
                <span>Принимаем к оплате: </span>

                <div id="paymentInfo" style="display:none;">
                    <p><b>Оплата</b></p>

                    <p>
                        Прием платежей на сайте обеспечивает
                        <!--noindex--><a rel="nofollow" target="_blank" href="http://www.ubrr.ru/">ПАО КБ «УБРиР»</a><!--/noindex-->
                        .<br>
                    </p>

                    <p>
                        Технологии
                        <!--noindex--><a rel="nofollow" target="_blank" href="http://www.ubrr.ru/">ПАО КБ «УБРиР»</a><!--/noindex-->
                        соответствуют международным стандартам безопасности данных индустрии платёжных карт, что
                        подтверждает сертификат PCI DSS первого уровня.
                    </p>

                    <p><b>Оплата с помощью банковской карты</b></p>

                    <p>
                        Для онлайн-оплаты можно использовать банковские карты Visa, VisaElectron, MasterCard и Maestro.
                        Если ваша карта подписана на 3D-Secure, авторизация вашего платежа будет проведена с помощью
                        одноразового пароля.
                    </p>

                    <p>
                        Ввод и обработка конфиденциальных платежных данных производится на стороне процессингового
                        центра. Никто, даже продавец, не может получить введенные клиентом реквизиты банковской карты,
                        что гарантирует полную безопасность его денежных средств и персональных данных.
                    </p>

                    <p>
                        <b>Для оплаты банковской картой необходимо заполнить короткую платежную форму:</b>
                    </p>
                    <ol>
                        <li>
                            указать номер карты (16 цифр на лицевой стороне карты);<br>
                        </li>
                        <li>
                            ввести CVC / CVV номер (3 цифры, которые напечатаны на обратной стороне карты, на полосе
                            с подписью);
                        </li>
                        <li>
                            имя и фамилию владельца карты (в точности так же, как они написаны на лицевой стороне
                            карты) и другие необходимые персональные данные;
                        </li>
                        <li>
                            срок действия карты, который написан на лицевой стороне карты.
                        </li>
                    </ol>

                </div>

                <a href="#paymentInfo" class="_galery">
                    <img src="/bitrix/templates/sushman/img/paysystems/visa_new.jpg" alt="Платежная система Visa"
                         title="Платежная система Visa">
                </a>
                <a href="#paymentInfo" class="_galery">
                    <img src="/bitrix/templates/sushman/img/paysystems/mc_new.jpg" alt="Платежная система MasterCard"
                         title="Платежная система MasterCard">
                </a>

                <!--                <a href="#paymentInfo" class="_galery">-->
                <!--                    <img src="/bitrix/templates/sushman/img/paysystems/visa.png" alt="Платежная система Visa"-->
                <!--                         title="Платежная система Visa">-->
                <!--                </a>-->
                <!--                <a href="#paymentInfo" class="_galery">-->
                <!--                    <img src="/bitrix/templates/sushman/img/paysystems/mastercard.png"-->
                <!--                         alt="Платежная система MasterCard" title="Платежная система MasterCard">-->
                <!--                </a>-->
                <!--                <a href="#paymentInfo" class="_galery">-->
                <!--                    <img src="/bitrix/templates/sushman/img/paysystems/mir.png" alt="Платежная система Мир"-->
                <!--                         title="Платежная система Мир">-->
                <!--                </a>-->
                <!--                <a href="#paymentInfo" class="_galery">-->
                <!--                    <img style="display:none;" src="/bitrix/templates/sushman/img/paysystems/visa_el.jpg"-->
                <!--                         alt="Visa Electron" title="Visa Electron">-->
                <!--                </a>-->
                <!--                <a href="#paymentInfo" class="_galery">-->
                <!--                    <img src="/bitrix/templates/sushman/img/paysystems/maestro.png" alt="Maestro" title="Maestro">-->
                <!--                </a>-->
                <!--                <a href="#paymentInfo" class="_galery">-->
                <!--                    <img style="display:none;" src="/bitrix/templates/sushman/img/paysystems/ae.jpg"-->
                <!--                         alt="Международная платежная система American Express"-->
                <!--                         title="Международная платежная система American Express">-->
                <!--                </a>-->
                <!--                <a href="#paymentInfo" class="_galery">-->
                <!--                    <img style="display:none;" src="/bitrix/templates/sushman/img/paysystems/jcb.png"-->
                <!--                         alt="Международная платежная система JCB" title="Международная платежная система JCB">-->
                <!--                </a>-->
                <!--                <a href="#paymentInfo" class="_galery">-->
                <!--                    <img style="display:none;" src="/bitrix/templates/sushman/img/paysystems/dc.png"-->
                <!--                         alt="Международная платежная система Diners Club"-->
                <!--                         title="Международная платежная система Diners Club">-->
                <!--                </a>-->
                <!--                <img src="/bitrix/templates/sushman/img/paysystems/yad.png"-->
                <!--                     style="background-color: rgb(77, 76, 76);" alt="Электронная платежная система Яндекс.Деньги"-->
                <!--                     title="Электронная платежная система Яндекс.Деньги">-->
                <!--                <img src="/bitrix/templates/sushman/img/paysystems/wm.png"-->
                <!--                     alt="Электронная платежная система WebMoney" title="Электронная платежная система WebMoney">-->
                <!--                <img src="/bitrix/templates/sushman/img/paysystems/qiwi.png" alt="Электронный кошелек Qiwi"-->
                <!--                     title="Электронный кошелек Qiwi">-->
            </div>
            <div style="float:right;padding-top: 50px;">
                <?
//                $APPLICATION->IncludeFile("/include/btn_vk_{$cities[$city]["CODE"]}.php", Array(), Array(
//                    "MODE" => "text",
//                    "NAME" => "Редактирование кнопки ВКонтакте",
//                ));
//                $APPLICATION->IncludeFile("/include/btn_instagram.php", Array(), Array(
//                    "MODE" => "text",
//                    "NAME" => "Редактирование кнопки Instagram",
//                ));
                ?>
                <a href="/copyright/" class="footer-privacy" target="_blank">Информация для правообладетелей</a><br>
                <a href="/privacy/" class="footer-privacy" target="_blank">Политика конфиденциальности</a>
            </div>
            <div class="clear"></div>
            <div class="phone"><?
                global $city, $cities;
                /*    $APPLICATION->IncludeFile("/include/phone_{$cities[$city]["CODE"]}.php", Array(), Array(
                        "MODE"      => "text",
                        "NAME"      => "Редактирование телефона",
                    ));*/

                ?>

                <span class="mobile" style="line-height:40px;">
								<?
                                $APPLICATION->IncludeFile("/phone_subst/subst.php", array('phone_key' => "mobile_phone_{$cities[$city]["CODE"]}"));
                                ?>
							</span>
                    <span class="phone"
                          style="line-height: 40px;position: relative;margin-top: 10px"><?


                        //$APPLICATION->IncludeFile("/phone_subst/subst.php", array('phone_key' => "phone_{$cities[$city]["CODE"]}"));
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

                        ?></span>

            </div>

            <div class="right-block valigned">
                                <span><?
                                    // включаемая область для раздела
                                    $APPLICATION->IncludeFile("/include/ogrn.php", Array(), Array(
                                        "MODE" => "text",                                           // будет редактировать в веб-редакторе
                                        "NAME" => "Редактирование телефона",      // текст всплывающей подсказки на иконке
                                    ));
                                    ?></span>
            </div>
            <!--                        <a id="sitedesign" href="http://www.web-axioma.ru/" target="_blank" title="Создание, продвижение и администрирование сайтов">Создание сайта — AXIOMA</a> -->
            <div class="clear"></div>
        </div>
    </div>
</footer>

</div></div>
<div class="btnUp"></div>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function () {
            try {
                w.yaCounter29393590 = new Ya.Metrika({
                    id: 29393590,
                    webvisor: true,
                    clickmap: true,
                    trackLinks: true,
                    accurateTrackBounce: true
                });
            } catch (e) {
            }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () {
                n.parentNode.insertBefore(s, n);
            };
        s.type = "text/javascript";
        s.async = true;
        s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else {
            f();
        }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript>
    <div><img src="//mc.yandex.ru/watch/29393590?ut=noindex" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-61370767-1', 'auto');
    ga('send', 'pageview');

</script>
<? if (isset($_GET['cheap_rolls'])) {
    $APPLICATION->SetPageProperty("description", $_SEO['description']);
    $APPLICATION->SetPageProperty("keywords", $_SEO['keywords']);
} ?>
<script>
    (function (w, d, s, h, id) {
        w.roistatProjectId = id;
        w.roistatHost = h;
        var p = d.location.protocol == "https:" ? "https://" : "http://";
        var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/" + id + "/init";
        var js = d.createElement(s);
        js.async = 1;
        js.src = p + h + u;
        var js2 = d.getElementsByTagName(s)[0];
        js2.parentNode.insertBefore(js, js2);
    })(window, document, 'script', 'cloud.roistat.com', 'c1c450b9beecf5e11c4867d54494ab6a');
</script>

<!-- BEGIN CALLBACKHUNTER CODE -->
<script type="text/javascript" src="//cdn.callbackhunter.com/cbh.js?hunter_code=d996fba179e2707eb631cbaec481518d" charset="UTF-8"></script>
<!-- END CALLBACKHUNTER CODE -->

</body>
</html>