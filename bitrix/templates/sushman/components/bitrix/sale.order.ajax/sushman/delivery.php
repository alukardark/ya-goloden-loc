<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<script type="text/javascript">
    function fShowStore(id, showImages, formWidth, siteId) {
        var strUrl = '<?=$templateFolder?>' + '/map.php';
        var strUrlPost = 'delivery=' + id + '&showImages=' + showImages + '&siteId=' + siteId;

        var storeForm = new BX.CDialog({
            'title': '<?=GetMessage('SOA_ORDER_GIVE')?>',
            head: '',
            'content_url': strUrl,
            'content_post': strUrlPost,
            'width': formWidth,
            'height': 450,
            'resizable': false,
            'draggable': false
        });

        var button = [
            {
                title: '<?=GetMessage('SOA_POPUP_SAVE')?>',
                id: 'crmOk',
                'action': function () {
                    GetBuyerStore();
                    BX.WindowManager.Get().Close();
                }
            },
            BX.CDialog.btnCancel
        ];
        storeForm.ClearButtons();
        storeForm.SetButtons(button);
        storeForm.Show();
    }

    function GetBuyerStore() {
        BX('BUYER_STORE').value = BX('POPUP_STORE_ID').value;
        //BX('ORDER_DESCRIPTION').value = '<?=GetMessage("SOA_ORDER_GIVE_TITLE")?>: '+BX('POPUP_STORE_NAME').value;
        BX('store_desc').innerHTML = BX('POPUP_STORE_NAME').value;
        BX.show(BX('select_store'));
    }

    function showExtraParamsDialog(deliveryId) {
        var strUrl = '<?=$templateFolder?>' + '/delivery_extra_params.php';
        var formName = 'extra_params_form';
        var strUrlPost = 'deliveryId=' + deliveryId + '&formName=' + formName;

        if (window.BX.SaleDeliveryExtraParams) {
            for (var i in window.BX.SaleDeliveryExtraParams) {
                strUrlPost += '&' + encodeURI(i) + '=' + encodeURI(window.BX.SaleDeliveryExtraParams[i]);
            }
        }

        var paramsDialog = new BX.CDialog({
            'title': '<?=GetMessage('SOA_ORDER_DELIVERY_EXTRA_PARAMS')?>',
            head: '',
            'content_url': strUrl,
            'content_post': strUrlPost,
            'width': 500,
            'height': 200,
            'resizable': true,
            'draggable': false
        });

        var button = [
            {
                title: '<?=GetMessage('SOA_POPUP_SAVE')?>',
                id: 'saleDeliveryExtraParamsOk',
                'action': function () {
                    insertParamsToForm(deliveryId, formName);
                    BX.WindowManager.Get().Close();
                }
            },
            BX.CDialog.btnCancel
        ];

        paramsDialog.ClearButtons();
        paramsDialog.SetButtons(button);
        //paramsDialog.adjustSizeEx();
        paramsDialog.Show();
    }

    function insertParamsToForm(deliveryId, paramsFormName) {
        var orderForm = BX("ORDER_FORM"),
            paramsForm = BX(paramsFormName);
        wrapDivId = deliveryId + "_extra_params";

        var wrapDiv = BX(wrapDivId);
        window.BX.SaleDeliveryExtraParams = {};

        if (wrapDiv)
            wrapDiv.parentNode.removeChild(wrapDiv);

        wrapDiv = BX.create('div', {props: {id: wrapDivId}});

        for (var i = paramsForm.elements.length - 1; i >= 0; i--) {
            var input = BX.create('input', {
                    props: {
                        type: 'hidden',
                        name: 'DELIVERY_EXTRA[' + deliveryId + '][' + paramsForm.elements[i].name + ']',
                        value: paramsForm.elements[i].value
                    }
                }
            );

            window.BX.SaleDeliveryExtraParams[paramsForm.elements[i].name] = paramsForm.elements[i].value;

            wrapDiv.appendChild(input);
        }

        orderForm.appendChild(wrapDiv);

        BX.onCustomEvent('onSaleDeliveryGetExtraParams', [window.BX.SaleDeliveryExtraParams]);
    }
</script>

<input type="hidden" name="BUYER_STORE" id="BUYER_STORE" value="<?= $arResult["BUYER_STORE"] ?>"/>
<div class="bx_section">
    <?php
    global $city, $cities;

    if ($city == 3) {

        if ($_SERVER['REMOTE_ADDR'] == '158.46.22.198' or $_SERVER['REMOTE_ADDR'] == '46.149.225.204' or true) {
            $delivery_price = 0;

        } else {
            $minsum = 400;

            // get current coords
            $street = $arResult["ORDER_PROP"]["USER_PROPS_Y"][10]["VALUE"];
            $building = $arResult["ORDER_PROP"]["USER_PROPS_Y"][11]["VALUE"];

            if ($street and $building) {
//                $uri = 'https://geocode-maps.yandex.ru/1.x/?format=json&geocode=' . $cities[$city['ID']]['NAME'] . ' ' . $street . ' ' . $building;
//                $geoResponse = json_decode(file_get_contents($uri));
//                $point = $geoResponse->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
                $point = getCoordinates($cities[$city['ID']]['NAME'] . ' ' . $street . ' ' . $building);
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
                                $minPrice = $zone['UF_MIN_PRICE'];
                                $deliveryPrice = $zone['UF_PRICE'];
                                //$desc = ' в зону <span class="city-district-title">' . $cities[$city['ID']]['NAME'] . ': ' . $zone['UF_NAME'] . '</span>';
                                $minsum = $zone['UF_MIN_PRICE'];
                                $delivery_price = $price < $minsum ? $zone['UF_PRICE'] : 0;
                            }
                        }
                    }
                }
            }
        }
    } else {

        $districts = GetCityDistricts();
        $vs = array_values($districts);
        $desc = "";
        if (count($vs) > 0) {
            $delivery_price = $vs[0]["PRICE"];
            $desc = " в <span class='city-district-title'>{$vs[0]["NAME"]}</span> район";
        }

        $minsum = 400;
        if ($_REQUEST["ORDER_PROP_8"]) {
            foreach ($districts as $d) {
                if ($d["NAME"] == $_REQUEST["ORDER_PROP_8"]) {

                    $minsum = (int)$d[MINSUM];

                    if ($price < $minsum) {
                        $delivery_price = $d["PRICE"];
                    } else {
                        $delivery_price = 0;
                    }

                    // $desc = " в <span class='city-district-title'>{$d["NAME"]}</span> район";

                    $rn = $d[NAME];
                    if (substr($rn, -1) == "й") {
                        $desc = " в <span class='city-district-title'>{$d["NAME"]}</span> район";
                    } else {
                        $desc = " в район <span class='city-district-title'>{$d["NAME"]}</span>";
                    }

                    break;
                }
            }
        }
    }

    $arResult[DELIVERY][ORDER_PRICE_FROM] = $minsum;

    if (!empty($arResult["DELIVERY"])) {
        $width = ($arParams["SHOW_STORES_IMAGES"] == "Y") ? 850 : 700;

        foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery) {

//            echo '<pre>';
//            var_dump($delivery_id);
//            echo '</pre>';

            if ($arDelivery["PRICE"] > 0) {
                $arDelivery["PRICE"] = $delivery_price;
                $arDelivery["PRICE_FORMATED"] = $delivery_price . " <span class='ico-rub'></span>";
            }
            if ($delivery_id !== 0 && intval($delivery_id) <= 0) {
//                var_dump($arDelivery["PROFILES"]);
//                echo '<pre>';
//                var_dump($arResult);
//                echo '</pre>';
                foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile) {
                    if ($arResult["DELIVERY_PRICE"] == 0) {
                        continue;
                    }
                    ?>
                    <div class="bx_block w100 vertical">
                        <div class="bx_element">
                            <input
                                type="hidden"
                                id="ID_DELIVERY_<?= $delivery_id ?>_<?= $profile_id ?>"
                                name="<?= htmlspecialcharsbx($arProfile["FIELD_NAME"]) ?>"
                                value="<?= $delivery_id . ":" . $profile_id; ?>"
                                <?= $arProfile["CHECKED"] == "Y" ? "checked=\"checked\"" : ""; ?>
                                onclick="submitForm();"
                                />

                            <label for="ID_DELIVERY_<?= $delivery_id ?>_<?= $profile_id ?>">

                                <div class="bx_description">

									<span class="bx_result_price"><!-- click on this should not cause form submit -->
                                        <?
                                        if ($arProfile["CHECKED"] == "Y" && doubleval($arResult["DELIVERY_PRICE"]) >= 0):
                                            ?>
                                            <?= str_replace(" руб.", " <span class='ico-rub'></span>",
                                            $arResult["DELIVERY_PRICE_FORMATED"]) ?>
                                            <?
                                            if ((isset($arResult["PACKS_COUNT"]) && $arResult["PACKS_COUNT"]) > 1):
                                                echo GetMessage('SALE_PACKS_COUNT') . ': <b>' . $arResult["PACKS_COUNT"] . '</b>';
                                            endif;

                                        else:
                                            $APPLICATION->IncludeComponent('bitrix:sale.ajax.delivery.calculator', '',
                                                array(
                                                    "NO_AJAX" => $arParams["DELIVERY_NO_AJAX"],
                                                    "DELIVERY" => $delivery_id,
                                                    "PROFILE" => $profile_id,
                                                    "ORDER_WEIGHT" => $arResult["ORDER_WEIGHT"],
                                                    "ORDER_PRICE" => $arResult["ORDER_PRICE"],
                                                    "LOCATION_TO" => $arResult["USER_VALS"]["DELIVERY_LOCATION"],
                                                    "LOCATION_ZIP" => $arResult["USER_VALS"]["DELIVERY_LOCATION_ZIP"],
                                                    "CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
                                                    "ITEMS" => $arResult["BASKET_ITEMS"],
                                                    "EXTRA_PARAMS_CALLBACK" => $extraParams
                                                ), null, array('HIDE_ICONS' => 'Y'));
                                        endif;
                                        ?>
									</span>

                                    <div
                                        onclick="BX('ID_DELIVERY_<?= $delivery_id ?>_<?= $profile_id ?>').checked=true;<?= $extraParams ?>submitForm();">
                                        <?
                                        if ($minsum == -1) {
                                            $arDelivery[DESCRIPTION] = 'Необходимо выбрать улицу и номер дома для рассчета стоимости доставки';
                                        } else {
                                            $arDelivery[DESCRIPTION] = str_replace("##MIN_SUM##", $minsum,
                                                $arDelivery[DESCRIPTION]);
                                        }
                                        ?>
                                        <?= $arDelivery["DESCRIPTION"] ?><?= $desc ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                            </label>

                        </div>
                    </div>
                    <?
                } // endforeach
            } else // stores and courier
            {
                if (count($arDelivery["STORE"]) > 0) {
                    $clickHandler = "onClick = \"fShowStore('" . $arDelivery["ID"] . "','" . $arParams["SHOW_STORES_IMAGES"] . "','" . $width . "','" . SITE_ID . "')\";";
                } else {
                    $clickHandler = "onClick = \"BX('ID_DELIVERY_ID_" . $arDelivery["ID"] . "').checked=true;submitForm();\"";
                }
                ?>
                <div
                    class="bx_block w100 vertical"<?= $arDelivery['PRICE'] > 0 ? " style='display:block;'" : " style='display:none;'" ?>>

                    <div class="bx_element">

                        <input type="hidden"
                               id="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>"
                               name="<?= htmlspecialcharsbx($arDelivery["FIELD_NAME"]) ?>"
                               value="<?= $arDelivery["ID"] ?>"<? if ($arDelivery["CHECKED"] == "Y") {
                            echo " checked";
                        } ?>
                               onclick="submitForm();"
                            />

                        <label for="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>">

                            <div class="bx_description">
									<span class="bx_result_price">
										<?= str_replace(" руб.", " <span class='ico-rub'></span>",
                                            $arDelivery["PRICE_FORMATED"]) ?>
									</span>

                                <div class="name"><?= $arDelivery["DESCRIPTION"] ?><?= $desc ?></div>
                                <div class="clear"></div>

                            </div>

                        </label>

                        <div class="clear"></div>
                    </div>
                </div>
                <?
            }
        }
    }
    ?>
    <div class="clear"></div>
</div>