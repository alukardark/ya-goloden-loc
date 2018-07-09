<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/props_format.php");
?>
<div class="bx_section">
    <input type="hidden" name="showProps" id="showProps" value="Y"/>

    <div id="sale_order_props" <?= ($bHideProps && $_POST["showProps"] != "Y") ? "style='display:none;'" : '' ?>>
        <?
        PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"]);
        PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"]);
        ?>

        <?
        //        echo "<div style='display:none'>";
        //        print_r($arResult["ORDER_PROP"]["USER_PROPS_Y"][8]["VALUE"]);
        //        echo "</div>"
        ?>
        <?
        global $city, $cities;
        $cityCode = $cities[$city]["CODE"];

        if ($city == 3) {

            $street = $arResult["ORDER_PROP"]["USER_PROPS_Y"][10]["VALUE"];
            $building = $arResult["ORDER_PROP"]["USER_PROPS_Y"][11]["VALUE"];

            if ($street and $building) {
                $showError = true;

//                $uri = 'https://geocode-maps.yandex.ru/1.x/?format=json&geocode=' . $cities[$city['ID']]['NAME'] . ' ' . $street . ' ' . $building;
//                $geoResponse = json_decode(file_get_contents($uri));
//                $point = $geoResponse->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
                $point = getCoordinates($cities[$city['ID']]['NAME'] . ' ' . $street . ' ' . $building);
                echo '<pre style="display: none;">';
                var_dump($point);
                echo '</pre>';
                if ($point) {
                    $point = explode(' ', $point);
                    if (count($point) == 2) {
                        $point = array('x' => $point[0], 'y' => $point[1]);

                        CModule::IncludeModule('highloadblock');
                        $hlblock_id = 9;
                        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
                        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                        $main_query = new Bitrix\Main\Entity\Query($entity);
                        $main_query->setFilter(array('UF_CITY' => $city['ID']));
                        $main_query->setSelect(array('*'));
                        $result = $main_query->exec();
                        $result = new CDBResult($result);
                        $zones = array();
                        while ($row = $result->Fetch()) {
                            $zones[] = $row;
                        }

                        if (count($zones)) {
                            // TODO : 1. может быть на границе; 2. может быть несколько полигонов в одной зоне
                            include_once($_SERVER['DOCUMENT_ROOT'] . '/polygon.php');

                            $realZone = false;
                            foreach ($zones as $zone) {
                                eval("\$zonePolygon = $zone[UF_POLYGON];");
                                $polygon = new Polygon;
                                $polygon->set_polygon($zonePolygon[0]);
                                $inPolygon = $polygon->calc($point);
                                if ($inPolygon) {
                                    $realZone = $zone;
                                    break;
                                }
                            }

                            if ($realZone) {

                                $orderPrice = (int)str_replace(' ', '', $arResult['ORDER_TOTAL_PRICE_FORMATED']);
                                $showError = false;

                                if ($orderPrice >= $realZone['UF_MIN_PRICE']) {

                                    ?>
                                    <div class="bx_ordercart_order_pay_center"
                                         style="text-align:left;float:right;margin-right: 8px;margin-top:30px;"><a
                                            href="javascript:void();"
                                            onclick="submitForm('Y'); ga('send', 'event', 'Order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>', 'To order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>'); return false;"
                                            class="make-order"><?= GetMessage("SOA_TEMPL_BUTTON") ?></a>
                                    </div>
                                    <?
                                } else {
                                    if ($_SERVER['REMOTE_ADDR'] != '46.149.225.204') {

                                        echo <<<EOL
		<div class="bx_ordercart_order_pay_center" style="text-align:left;float:right;margin-right: 8px;margin-top:30px;">
			<a href="javascript:void();" class="make-order btn-disabled">Оформить заказ</a>
			<div class='min-mess'>Минимальная сумма для доставки по данному адресу {$realZone['UF_MIN_PRICE']} руб.</div>
		</div>
EOL;
                                    } else {
                                        ?>
                                        <div class="bx_ordercart_order_pay_center"
                                             style="text-align:left;float:right;margin-right: 8px;margin-top:30px;"><a
                                                href="javascript:void();"
                                                onclick="submitForm('Y'); ga('send', 'event', 'Order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>', 'To order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>'); return false;"
                                                class="make-order"><?= GetMessage("SOA_TEMPL_BUTTON") ?></a>
                                        </div>
                                        <?
                                    }
                                }
                            }
                        }
                    }
                }

                if ($showError) { ?>

		<div class="bx_ordercart_order_pay_center" style="text-align:left;float:right;margin-right: 8px;margin-top:30px;">
			<a onclick="ga('send', 'event', 'Order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>', 'To order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>')" href="javascript:void();" class="make-order btn-disabled">Оформить заказ</a>
			<div class="min-mess">К сожалению, в настоящий момент не работает доставка по данному адресу</div>
		</div>
                <? }

            } else { ?>

		<div class="bx_ordercart_order_pay_center" style="text-align:left;float:right;margin-right: 8px;margin-top:30px;">
			<a onclick="ga('send', 'event', 'Order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>', 'To order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>')" href="javascript:void();" class="make-order btn-disabled">Оформить заказ</a>
			<div class='min-mess'>Необходимо выбрать улицу и номер дома для рассчета стоимости доставки</div>
		</div>

            <? }

        } else {

            $orderPrice = (int)str_replace(' ', '', $arResult['ORDER_TOTAL_PRICE_FORMATED']);

            /*if (($cities[$city]["CODE"] == "nkz" && $orderPrice < 400) ||
                ($cities[$city]["CODE"] == "nsk" && (($arResult["ORDER_PROP"]["USER_PROPS_Y"][8]["VALUE"] == "Кировский" ||
                            $arResult["ORDER_PROP"]["USER_PROPS_Y"][8]["VALUE"] == "Ленинский") && $orderPrice < 500))) {
            */
            $kludge = ($cities[$city]["CODE"] == 'nsk' and (($arResult["ORDER_PROP"]["USER_PROPS_Y"][8]["VALUE"] == "Кировский" ||
                    $arResult["ORDER_PROP"]["USER_PROPS_Y"][8]["VALUE"] == "Ленинский")) and $orderPrice < 1500);
            $kludgeExtraText = $kludge ? 'для ' . str_replace('кий', 'кого',
                    $arResult["ORDER_PROP"]["USER_PROPS_Y"][8]["VALUE"]) . ' района ' : '';
            if (
                ($cities[$city]["CODE"] == 'nkz' and $orderPrice < 400) or
                ($cities[$city]["CODE"] == 'kem' and $orderPrice < 300) or
                ($cities[$city]["CODE"] == 'nsk' and $orderPrice < 400) or
                $kludge
            ) {
                $mm = ($cities[$city]["CODE"] == "kem" ? 300 : 400);
                $mm = $kludge ? 1500 : $mm;

                if ($_SERVER['REMOTE_ADDR'] != '46.149.225.204') { ?>

		<div class="bx_ordercart_order_pay_center" style="text-align:left;float:right;margin-right: 8px;margin-top:30px;">
			<a onclick="submitForm('Y'); ga('send', 'event', 'Order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>', 'To order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>')" href="javascript:void();" class="make-order btn-disabled">Оформить заказ</a>
			<div class='min-mess'>Минимальная сумма заказа на нашем сайте <?=$kludgeExtraText?> составляет <?=$mm?> рублей</div>
		</div>
                <? } else {
                    ?>
                    <div class="bx_ordercart_order_pay_center"
                         style="text-align:left;float:right;margin-right: 8px;margin-top:30px;"><a
                            href="javascript:void();"
                            onclick="submitForm('Y'); ga('send', 'event', 'Order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>', 'To order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>'); return false;"
                            class="make-order"><?= GetMessage("SOA_TEMPL_BUTTON") ?></a>
                    </div>
                    <?
                }
            } else {
                ?>
                <div class="bx_ordercart_order_pay_center"
                     style="text-align:left;float:right;margin-right: 8px;margin-top:30px;"><a href="javascript:void();"
                                                                                               onclick="submitForm('Y'); ga('send', 'event', 'Order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>', 'To order <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>'); return false;"
                                                                                               class="make-order"><?= GetMessage("SOA_TEMPL_BUTTON") ?></a>
                </div>
                <?
            }

        }

        ?>
    </div>
</div>
