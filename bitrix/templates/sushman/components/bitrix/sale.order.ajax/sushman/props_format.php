<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?
if (!function_exists("showFilePropertyField")) {
    function showFilePropertyField($name, $property_fields, $values, $max_file_size_show = 50000)
    {
        $res = "";

        if (!is_array($values) || empty($values)) {
            $values = array(
                "n0" => 0,
            );
        }

        if ($property_fields["MULTIPLE"] == "N") {
            $res = "<label for=\"\"><input type=\"file\" size=\"" . $max_file_size_show . "\" value=\"" . $property_fields["VALUE"] . "\" name=\"" . $name . "[0]\" id=\"" . $name . "[0]\"></label>";
        } else {
            $res = '
			<script type="text/javascript">
				function addControl(item)
				{
					var current_name = item.id.split("[")[0],
						current_id = item.id.split("[")[1].replace("[", "").replace("]", ""),
						next_id = parseInt(current_id) + 1;

					var newInput = document.createElement("input");
					newInput.type = "file";
					newInput.name = current_name + "[" + next_id + "]";
					newInput.id = current_name + "[" + next_id + "]";
					newInput.onchange = function() { addControl(this); };

					var br = document.createElement("br");
					var br2 = document.createElement("br");

					BX(item.id).parentNode.appendChild(br);
					BX(item.id).parentNode.appendChild(br2);
					BX(item.id).parentNode.appendChild(newInput);
				}
			</script>
			';

            $res .= "<label for=\"\"><input type=\"file\" size=\"" . $max_file_size_show . "\" value=\"" . $property_fields["VALUE"] . "\" name=\"" . $name . "[0]\" id=\"" . $name . "[0]\"></label>";
            $res .= "<br/><br/>";
            $res .= "<label for=\"\"><input type=\"file\" size=\"" . $max_file_size_show . "\" value=\"" . $property_fields["VALUE"] . "\" name=\"" . $name . "[1]\" id=\"" . $name . "[1]\" onChange=\"javascript:addControl(this);\"></label>";
        }

        return $res;
    }
}

if (!function_exists("PrintPropsForm")) {
    function PrintPropsForm($arSource = array(), $locationTemplate = ".default")
    {
        global $cities, $city;

        $order_discount_id = (int)$_REQUEST['ORDER_PROP_9'];
        $hide_address = false;
        if ($order_discount_id > 0) {
            CModule::IncludeModule('highloadblock');
            $hlblock_id = 6;
            $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
            $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
            $main_query = new Bitrix\Main\Entity\Query($entity);
            $main_query->setFilter(array('ID' => $order_discount_id));
            $main_query->setSelect(array('*'));
            $result = $main_query->exec();
            $result = new CDBResult($result);
            $prc = 0;
            if ($row = $result->Fetch()) {
                if (strpos("Самовывоз", $row["UF_NAME"]) !== false) {
                    $hide_address = true;
                }
            }
        }

        if (!empty($arSource)) {

            foreach ($arSource as $arProperties) {

                if ($hide_address && in_array($arProperties["FIELD_ID"], array(
                        'ORDER_PROP_STREET',
                        'ORDER_PROP_DISTRICT',
                        'ORDER_PROP_APARTMENT',
                        'ORDER_PROP_BUILDING'
                    ))
                ) {
                    continue;
                }
                if ($arProperties["FIELD_ID"] == 'ORDER_PROP_CITY') {
                    ?>
                    <input id="<?= $arProperties["FIELD_ID"] ?>" type="hidden" name="<?= $arProperties["FIELD_NAME"] ?>"
                           value="<?= $cities[$city]["NAME"] ?>">
                    <?
                    continue;
                }
                if ($arProperties["FIELD_ID"] == 'ORDER_PROP_ORDER_DISCOUNT_ID') {
                    ?>
                    <input id="<?= $arProperties["FIELD_ID"] ?>" type="hidden" name="<?= $arProperties["FIELD_NAME"] ?>"
                           value="<?= $arProperties["VALUE"] ?>">
                    <?
                    continue;
                }
                if ($arProperties["FIELD_ID"] == 'ORDER_PROP_STREET') {
                    $streets = GetCityStreets();
                    if (count($streets) > 0) {
                        ?>
                        <div class="order_field">
                            <div class="bx_block">
                                <?= $arProperties["NAME"] ?>
                                <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                    <span class="bx_sof_req">*</span>
                                <? endif; ?>
                            </div>

                            <div class="bx_block">
                                <select data-placeholder="Улица" name="<?= $arProperties["FIELD_NAME"] ?>"
                                        id="<?= $arProperties["FIELD_NAME"] ?>" class="city-street chosen">
                                    <option/>
                                    <?
                                    foreach ($streets as $arVariants):
                                        ?>
                                        <option
                                            value="<?= $arVariants["NAME"] ?>"<? if ($arProperties["VALUE"] == $arVariants["NAME"]) {
                                            echo " selected";
                                        } ?>><?= $arVariants["NAME"] ?></option>
                                        <?
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?
                        continue;
                    }
                }
                if ($arProperties["FIELD_ID"] == 'ORDER_PROP_DISTRICT' && !$hide_address) {
                    $districts = GetCityDistricts();

                    ?>
                    <div class="order_field">
                        <div class="bx_block">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block">
                            <select name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>"
                                    class="city-district">
                                <?
                                foreach ($districts as $arVariants):
                                    ?>
                                    <option value="<?= $arVariants["NAME"] ?>"
                                            data-price="<?= $arVariants['PRICE'] ?>"<? if ($arProperties["VALUE"] == $arVariants["NAME"]) {
                                        echo " selected";
                                    } ?>><?= $arVariants["NAME"] ?></option>
                                    <?
                                endforeach;
                                ?>
                            </select>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>
                    </div>
                    <?
                    continue;
                }

                if (false && $arProperties["IS_EMAIL"] == "Y") {
                    ?>
                    <input type="hidden" name="<?= $arProperties["FIELD_NAME"] ?>"
                           value="<?= $arProperties["VALUE"] ?>">
                    <?
                    continue;
                }
                ?>


                <?
                $field_class = "";
                if ($arProperties["TYPE"] == "TEXTAREA") {
                    $field_class = 'field-text';
                }
                ?>

                <div class="order_field <?= $field_class ?>">
                    <?

                    if ($arProperties["TYPE"] == "CHECKBOX") {
                        ?>
                        <input type="hidden" name="<?= $arProperties["FIELD_NAME"] ?>" value="">

                        <div class="bx_block">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block">
                            <input type="checkbox" name="<?= $arProperties["FIELD_NAME"] ?>"
                                   id="<?= $arProperties["FIELD_NAME"] ?>"
                                   value="Y"<? if ($arProperties["CHECKED"] == "Y") {
                                echo " checked";
                            } ?>>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>
                        <?
                    } elseif ($arProperties["TYPE"] == "TEXT") {
                        if ($arProperties["IS_EMAIL"] == "Y" && strpos($arProperties["VALUE"], "@default-email.ru")) {
                            $arProperties["VALUE"] = "";
                        }
                        ?>
                        <div class="bx_block">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): //&& $arProperties["IS_EMAIL"] != "Y" ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block">

                            <?
                            $h = "";
                            if ($arProperties["NAME"] == "Телефон") {
                                global $city, $cities;
                                switch ($cities[$city]["CODE"]) {
                                    case 'nsk':
                                        $a = 383;
                                        break;
                                    case 'kem':
                                        $a = 3842;
                                        break;
                                    case 'nkz':
                                        $a = 3843;
                                }
                                $h .=
                                    "<select name='phone_type' class='phone-type-select'>
											<option value='mob'>+7</option>
											<option value='dom'>(" . $a . ")</option>
										</select>";
                                echo $h;
                            }

                            ?>


                            <input type="text" maxlength="250" size="<?= $arProperties["SIZE1"] ?>"
                                   value="<?= $arProperties["VALUE"] ?>" name="<?= $arProperties["FIELD_NAME"] ?>"
                                   id="<?= $arProperties["FIELD_NAME"] ?>" <?= ($arProperties["FIELD_ID"] == 'ORDER_PROP_COMMENT' && $hide_address ? " style='width:778px;height:38px;'" : "") ?> <?= ($arProperties["FIELD_ID"] == 'ORDER_PROP_ADDRESS' ? " style='width:568px;'" : "") ?>>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>
                        <?
                    } elseif ($arProperties["TYPE"] == "SELECT") {
                        ?>
                        <div class="bx_block">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block">
                            <select name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>"
                                    size="<?= $arProperties["SIZE1"] ?>">
                                <?
                                foreach ($arProperties["VARIANTS"] as $arVariants):
                                    ?>
                                    <option value="<?= $arVariants["VALUE"] ?>"<? if ($arVariants["SELECTED"] == "Y") {
                                        echo " selected";
                                    } ?>><?= $arVariants["NAME"] ?></option>
                                    <?
                                endforeach;
                                ?>
                            </select>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>
                        <?
                    } elseif ($arProperties["TYPE"] == "MULTISELECT") {
                        ?>
                        <div class="bx_block">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block">
                            <select multiple name="<?= $arProperties["FIELD_NAME"] ?>"
                                    id="<?= $arProperties["FIELD_NAME"] ?>" size="<?= $arProperties["SIZE1"] ?>">
                                <?
                                foreach ($arProperties["VARIANTS"] as $arVariants):
                                    ?>
                                    <option value="<?= $arVariants["VALUE"] ?>"<? if ($arVariants["SELECTED"] == "Y") {
                                        echo " selected";
                                    } ?>><?= $arVariants["NAME"] ?></option>
                                    <?
                                endforeach;
                                ?>
                            </select>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>
                        <?
                    } elseif ($arProperties["TYPE"] == "TEXTAREA") {
                        $rows = ($arProperties["SIZE2"] > 10) ? 4 : $arProperties["SIZE2"];
                        ?>
                        <div class="bx_block">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block">
                            <textarea rows="<?= $rows ?>" cols="<?= $arProperties["SIZE1"] ?>"
                                      name="<?= $arProperties["FIELD_NAME"] ?>"
                                      id="<?= $arProperties["FIELD_NAME"] ?>"><?= $arProperties["VALUE"] ?></textarea>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>
                        <?
                    } elseif ($arProperties["TYPE"] == "LOCATION") {
                        $value = 0;
                        if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0) {
                            foreach ($arProperties["VARIANTS"] as $arVariant) {
                                if ($arVariant["SELECTED"] == "Y") {
                                    $value = $arVariant["ID"];
                                    break;
                                }
                            }
                        }
                        ?>
                        <div class="bx_block" style="display:none">
                            <?

                            $GLOBALS["APPLICATION"]->IncludeComponent(
                                "bitrix:sale.ajax.locations",
                                $locationTemplate,
                                array(
                                    "AJAX_CALL" => "N",
                                    "COUNTRY_INPUT_NAME" => "COUNTRY",
                                    "REGION_INPUT_NAME" => "REGION",
                                    "CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
                                    "CITY_OUT_LOCATION" => "Y",
                                    "LOCATION_VALUE" => $value,
                                    "ORDER_PROPS_ID" => $arProperties["ID"],
                                    "ONCITYCHANGE" => "",
                                    "SIZE1" => $arProperties["SIZE1"],
                                ),
                                null,
                                array('HIDE_ICONS' => 'Y')
                            );

                            ?>

                        </div>
                        <?
                    } elseif ($arProperties["TYPE"] == "RADIO") {
                        ?>
                        <div class="bx_block">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block">
                            <?
                            if (is_array($arProperties["VARIANTS"]))
                            {
                                foreach($arProperties["VARIANTS"] as $arVariants):
                                ?>
                                    <input
                                        type="radio"
                                        name="<?=$arProperties["FIELD_NAME"]?>"
                                        id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"
                                        value="<?=$arVariants["VALUE"]?>" <?if($arVariants["CHECKED"] == "Y") {echo " checked";}?> />

                                    <label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"><?=$arVariants["NAME"]?></label></br>
                                <?
                                endforeach;
                            }
                            ?>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>
                        <?
                    } elseif ($arProperties["TYPE"] == "FILE") {
                        ?>
                        <div class="bx_block">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block">
                            <?= showFilePropertyField("ORDER_PROP_" . $arProperties["ID"], $arProperties,
                                $arProperties["VALUE"], $arProperties["SIZE1"]) ?>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>

                        <?
                    }
                    ?>
                </div>
                <?
            }
        }
    }
}
?>