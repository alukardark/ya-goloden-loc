<?php
if (!$SECTION_ID or !in_array($SECTION_ID, getNonDiscountSections())) {
    global $city, $cities;
    CModule::IncludeModule('highloadblock');
    $hlblock_id = 6;
    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $main_query = new Bitrix\Main\Entity\Query($entity);
    $main_query->setSelect(array(
        'ID',
        'UF_NAME',
        'UF_ACTIVE_TIME',
        'UF_HANDLER',
        'UF_ACTIVE',
        'UF_DISCOUNT'
    ));
    $main_query->setOrder(array("UF_SORT"));
    $main_query->setFilter(array("UF_CITY" => $city, "UF_ACTIVE" => 1, "UF_SHOW_IN_MENU" => 1));
    $result = $main_query->exec();
    $result = new CDBResult($result);
    $rows = array();
    while ($row = $result->Fetch()) {
        $rows[] = $row;
    }

    CModule::includeModule('ws.projectsettings');
    $holidays_str = WS_PSettings::getFieldValue("HOLIDAYS");
    $holidays = explode(",", $holidays_str);
    $is_holiday = false;
    foreach ($holidays as $hd) {
        $hd = trim($hd);
        $cur_dm = date("d.m");
        if ($cur_dm == $hd) {
            $is_holiday = true;
        }
    }

    $discount = ['UF_DISCOUNT' => 0];
    foreach ($rows as $row) {

        if ($row["UF_HANDLER"] == "notholiday" && $is_holiday) {
            continue;
        }
        if (count($row["UF_ACTIVE_TIME"]) > 0) {
            $workings = array();
            $dw = Array(
                'Пн' => 1,
                'Вт' => 2,
                'Ср' => 3,
                'Чт' => 4,
                'Пт' => 5,
                'Сб' => 6,
                'Вс' => 7
            );
            $dont_hide_this_shit = false;
            foreach ($row["UF_ACTIVE_TIME"] as $r) {

                $parts = explode("/", $r);
                $ds = explode("-", trim($parts[0]));
                $dws = $dw[trim($ds[0])];
                $dwe = $dw[trim($ds[1])];
                if ($dwe === null) {
                    $dwe = $dws;
                }
                $tstr = explode("-", trim($parts[1]));
                $ts = MakeTimeStamp(date("d.m.Y") . " " . trim($tstr[0]));
                $te = MakeTimeStamp(date("d.m.Y") . " " . trim($tstr[1]));
                $cur = getdate();
                $cur_stamp = time();

                $cur_dw = $cur["wday"];
                $cur_h = $cur["hours"];
                $cur_m = $cur["minutes"];
                if ($cur_dw === 0) {
                    $cur_dw = 7;
                }

                if ($cur_dw >= $dws && $cur_dw <= $dwe &&
                    $cur_stamp >= $ts && $cur_stamp <= $te
                ) {
                    $dont_hide_this_shit = true;
                    break;
                } else {
                }

            }

            if (!$dont_hide_this_shit) {
                continue;
            }
        }

        if ($discount['UF_DISCOUNT'] < $row['UF_DISCOUNT']) {
            $discount = $row;
        }
    }
    $discountHtml = [];
    if ($discount['ID']) {
        $priceShowed = (int)str_replace(' ', '', $priceShowed);
        $discountHtml[] = '<div class="preview_item_discount_block ' . $page . '">';
        $discountHtml[] = '<span class="preview_item_purchase_price new-design">' . $priceShowed . '<span class=\'ico-rub\'></span></span><br/>';
        $discountHtml[] = '<span title="Акция «' . $discount['UF_NAME'] . '»" class="preview_item_discount_value">' . $discount['UF_DISCOUNT'] . '%</span>';
        $discountHtml[] = '</div>';

        // тут такое говнище сейчас будет
        $priceId = $arItemIDs['PRICE'];
        $newValue = round($priceShowed * ((100 - $discount['UF_DISCOUNT']) / 100));
		$discountHtml[] = '<script type="text/javascript">$(document).ready(function(){ $("#' . $priceId . '").html("' . $newValue . '<span class=\'ico-rub\'></span>"); });</script>';
    }
    $discountHtml = implode($discountHtml);
	$discountHtml = '';
}

?>