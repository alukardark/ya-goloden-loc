<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$strMainID = "popup_".$this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);
?>
<div class="item-info bx_item_detail" id="<? echo $arItemIDs['ID']; ?>">
<?
if ('Y' == $arParams['DISPLAY_NAME'])
{
?>
<div class="bx_item_title">
        <? if ($arResult["SORT"]) { ?><span class="num"><?=str_pad($arResult["SORT"],4,'0',STR_PAD_LEFT)?></span><? } // ['DISPLAY_PROPERTIES']['ARTNUMBER']["VALUE"]?>     
	<h1>
		<? echo (
			isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
			? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
			: $arResult["NAME"]
		); ?>
	</h1>
</div>
<?
}
?>
	<div class="bx_item_container">
<?
    CModule::IncludeModule("sale");
    CModule::IncludeModule("catalog");
    $db_res = CSaleViewedProduct::GetList(
                    null,
                    array(
                        "PRODUCT_ID" => $arResult["ID"]
                    ),
                    array("PRODUCT_ID")
    );
    $real = 0;
    if ($arItems = $db_res->Fetch())
    {
        $real = (int)$arItems["CNT"];
    }
    $seed = (int)$arResult['DISPLAY_PROPERTIES']['SEED']["VALUE"];
    if ($seed<20) {
        $seed = rand(20,40);
        CIBlockElement::SetPropertyValueCode($arResult["ID"],"SEED",$seed);    
    }
    $viewed = $real;
    if ($viewed<$seed)
        $viewed+=$seed;

    function pluralForm($n, $form1, $form2, $form5) {
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) return $form5;
        if ($n1 > 1 && $n1 < 5) return $form2;
        if ($n1 == 1) return $form1;
        return $form5;
    }    

    $ids = array($arResult['ID']);

            $elementId = $arResult['ID'];
            $iblockId = $arResult['IBLOCK_ID'];
            $arSkuInfo = CCatalogSKU::GetInfoByProductIBlock($iblockId);
            $rres = CIBlockElement::GetList(
                            array(),
                            array('IBLOCK_ID' => $arSkuInfo['IBLOCK_ID'], '=PROPERTY_'.$arSkuInfo['SKU_PROPERTY_ID'] => $elementId),
                            false,
                            false,
                            array("ID","PROPERTY_CITY","CATALOG_GROUP_1")
                    );
            
            while($ob = $rres->GetNextElement()) {
                $offer = $ob->GetFields();
                $ids[] = $offer['ID'];
            }


    $rsOrder = CSaleOrder::GetList(array('ID' => 'DESC'), array('BASKET_PRODUCT_ID' => $ids));
    $real_date =0;
    if ($arSales = $rsOrder->Fetch())
    {
        $real_date = MakeTimeStamp($arSales['DATE_INSERT']);
    }
    $seed2 = $arResult['DISPLAY_PROPERTIES']['SEED2']["VALUE"];
    if (!$seed2) {
        $test = time()-3600*rand(4,24*4);
        $lb = MakeTimeStamp(date("d.m.Y",$test)." 00:30:00");
        $rb = MakeTimeStamp(date("d.m.Y",$test)." 10:00:00");
        if ($test>$lb && $test<$rb) {
            $test -= 8*3600;
        }
        $seed2 = date("d.m.Y H:i:s",$test);
        CIBlockElement::SetPropertyValueCode($arResult["ID"],"SEED2",$seed2);    
    }
    $seed2 = MakeTimeStamp($seed2);
    $last_buy_date = date("d.m.Y H:i",$real_date>$seed2 ? $real_date : $seed2);
?>
<div class="viewinfo"><div class="bg"></div><div class="inner">Это блюдо просматривают <span style="font-size:18px;"><?=$viewed?></span> <?=pluralForm($viewed,"человек", "человека", "человек")?>
    <br/>Это блюдо последний раз заказали <?=$last_buy_date?></div></div>

		<div class="bx_lt">
                                    <?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", "menu", array(
                                                    "IBLOCK_TYPE" => $arResult['IBLOCK_TYPE'],
                                                    "IBLOCK_ID" => $arResult['IBLOCK_ID'],
                                                    "ELEMENT_ID" => $arResult['ID'],
                                                    "ELEMENT_CODE" => "",
                                                    "PROP_CODE" => "TYPE_REF",
                                                    "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                                                    "CACHE_TIME" => $arParams['CACHE_TIME'],
                                                    "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
                                                    "WIDTH" => "",
                                                    "HEIGHT" => ""
                                                    ),
                                                    $component,
                                                    array("HIDE_ICONS" => "Y")
                                            );?>
                    
                    <?
                    if ($arResult["DETAIL_PICTURE"]["ID"]) {
                        $arUserPhoto = CFile::GetFileArray($arResult["DETAIL_PICTURE"]["ID"]);
                        $preview = CFile::ResizeImageGet($arUserPhoto,array("width" => 573,"height" => 610),BX_RESIZE_IMAGE_EXACT );
                    } else {
                        $preview['src'] = $arResult["DETAIL_PICTURE"]["SRC"];
                    }
                    ?>
                    <span id="<? echo $arItemIDs['PICT']; ?>" class="pic" title="<? echo $strTitle; ?>"><? if ($preview['src']) { ?><img src="<?=$preview['src']?>"/><?}?></span>
		</div>

		<div class="bx_rt">
                    
<div class="item_info_section">
	<div class="bx_item_description">
<?
        $items_q = explode(",",$arResult['PROPERTIES']["ITEM_CONTENT_QUANTITY"]["VALUE"]);
        
        $set_items = $arResult['PROPERTIES']["ITEM_CONTENT"]["VALUE"];
        if ($set_items && count($set_items)) {
            $res = CIBlockElement::GetList(array("SORT" => "ASC"),array("ID" => $set_items, "IBLOCK_ID" => $arResult["IBLOCK_ID"]),false,false,array("ID", "NAME", "DETAIL_PAGE_URL"));
            $html = "";
            $i=0;
            while($row = $res->GetNext()) {
                $quantity = (int)trim($items_q[$i]) ? (int)trim($items_q[$i]) : 1;
                $html .= "<li><a href='{$row['DETAIL_PAGE_URL']}'>{$row["NAME"]}</a>&nbsp;&mdash;&nbsp;{$quantity}</li>";
                $i++;
            }

    		$long_class = "";
            if ($html) {
            	if(strlen($html) > 500) {
            		//$long_class = "related-long";
            	}
                echo "<h4>Состав</h4><ul class='related-items {$long_class}'>{$html}</ul>";
            }
        }

        

	if ('html' == $arResult['DETAIL_TEXT_TYPE'])
	{
		echo $arResult['DETAIL_TEXT'];
	}
	else
	{
		?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
	}

	// Входит в состав
	$cont_html = "";
    $cont_items = CIBlockElement::GetList(
    	array("SORT" => "ASC"),
    	array(
    		// "ID" => $set_items, 
    		"IBLOCK_ID" => $arResult["IBLOCK_ID"],
    		"PROPERTY_ITEM_CONTENT" => $arResult[ID]
    		),
    	false,false,
    	array("ID", "NAME", "DETAIL_PAGE_URL")
    );
    while($row = $cont_items->GetNext()) {
		$cont_html .= "<li><a href='{$row['DETAIL_PAGE_URL']}'>{$row["NAME"]}</a></li>";
    }

    if ($cont_html) {
        echo "<h4 class='related-h4'>Входит в состав:</h4><ul class='related-items'>{$cont_html}</ul>";
    }	
?>    
	</div>
</div>
<?
    $item_quantity = $arResult["CATALOG_MEASURE_RATIO"];
    $item_weight = $arResult["CATALOG_WEIGHT"];
    $has_props = $item_quantity || $item_weight;
    if ($has_props) {
?>
<div class="item_props">
            <? if ($item_quantity) { ?><span class="l">Количество: <span><?=$item_quantity?>&nbsp;шт</span></span><? } ?>    
            <? if ($item_weight) { ?><span class="r">Вес: <span><?=$item_weight?>&nbsp;г</span></span><? } ?>    
            <div class="clear"></div>
</div>
<? } ?>

<? if(strpos($arResult[NAME], "1/2 пиццы") !== FALSE) {
	$add_class = "bbb-hidden";
} 

if($arResult[IBLOCK_ID]==2) { // wok menu
	$add_class = "bbb-hidden";
}
?>

<div class="bbb <?=$add_class?>">
<div class="price-block <?=($has_props ? "no-line": "")?>">
<div class="item_price">
<?
$boolDiscountShow = false; //(0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF']);
?>
	<div class="item_old_price" id="<? echo $arItemIDs['OLD_PRICE']; ?>" style="display: <? echo ($boolDiscountShow ? '' : 'none'); ?>"><? echo ($boolDiscountShow ? $arResult['MIN_PRICE']['PRINT_VALUE'] : ''); ?></div>
	<div class="item_current_price" id="<? echo $arItemIDs['PRICE']; ?>"><? echo str_replace("руб.","<span class='ico-rub'></span>",$arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']); ?></div>
</div>

<div class="item_info_section">
<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
        global $city, $cities;
        $cityCode = $cities[$city]["CODE"];
        $arProp = $arResult['SKU_PROPS']['CITY'];
        $arResult['OFFERS_SELECTED'] = -1;
        foreach ($arResult['OFFERS'] as $k => $v)
        {
           if ($cityCode==$v["PROPERTIES"]["CITY"]["VALUE"])
            $arResult['OFFERS_SELECTED'] = $k;
        }
	$canBuy = $arResult['OFFERS_SELECTED']==-1 ? false : $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];
}
else
{
	$canBuy = $arResult['CAN_BUY'];
}
if ($canBuy)
{
	$buyBtnMessage = ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));
	$buyBtnClass = 'bx_big bx_bt_button bx_cart';
}
else
{
	$buyBtnMessage = ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE'));
	$buyBtnClass = 'bx_big bx_bt_button_type_2 bx_cart btn-disabled';
}
global $ignored_ids,$wok_section_ids,$pizza_section_ids;
if (in_array($arResult["IBLOCK_SECTION_ID"],$ignored_ids)) {
    $canBuy = false;
    $arResult['CAN_BUY'] = "N";
	$buyBtnClass = 'bx_big bx_bt_button bx_cart btn-addto-constructor '.(in_array($arResult["IBLOCK_SECTION_ID"],$wok_section_ids) ? "wok-cons" : "").(in_array($arResult["IBLOCK_SECTION_ID"],$pizza_section_ids) ? "pizza-cons" : "");
        $arParams['MESS_NOT_AVAILABLE'] = $buyBtnMessage = 'Добавить';
}

if ('Y' == $arParams['USE_PRODUCT_QUANTITY'] && $canBuy)
{
?>
<div class="q-block">
            <span id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>" class="arrow arrow-left"></span><span id="<? echo $arItemIDs['QUANTITY_UP']; ?>" class="arrow arrow-right"></span>
            <input type="text" class="bx_col_input" id="<? echo $arItemIDs['QUANTITY']; ?>" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
					? 1
					: $arResult['CATALOG_MEASURE_RATIO']
				) ?>">
</div>
<? } ?>
<? if (!$canBuy && 'Y' == $arParams['USE_PRODUCT_QUANTITY']) { ?>
    <input type="hidden" class="bx_col_input" id="<? echo $arItemIDs['QUANTITY']; ?>" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
					? 1
					: $arResult['CATALOG_MEASURE_RATIO']
				) ?>">
<? } ?>

</div>
    <div class="clear"></div>
</div>
			<a href="javascript:void(0);" class="buy <?=$buyBtnClass; ?>" id="<?=$arItemIDs['BUY_LINK']; ?>" data-id="<?=$arResult['ID']?>"><span></span><? echo $buyBtnMessage; ?></a>
                        
</div>                        
		</div>

		<div class="bx_rb">
                    
                    
		</div>
                <div style="clear: both;"></div>
	</div>
	<div class="clb"></div>
</div>
<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP']))
{
	$arSkuProps = array();
?>
<div class="item_info_section" style="display:none;" id="<? echo $arItemIDs['PROP_DIV']; ?>">
<?

	foreach ($arResult['SKU_PROPS'] as &$arProp)
	{
		if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
			continue;
		$arSkuProps[] = array(
			'ID' => $arProp['ID'],
			'SHOW_MODE' => $arProp['SHOW_MODE'],
			'VALUES_COUNT' => $arProp['VALUES_COUNT']
		);
		if ('TEXT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_size full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_size';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
?>
				<li
					data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID']; ?>"
					data-onevalue="<? echo $arOneValue['ID']; ?>"
				></li>
<?
			}
?>
			</ul>
			<div class="bx_slide_left" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
	</div>
<?
		}
		elseif ('PICT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_scu full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_scu';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
		<span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
		<div class="bx_scu_scroller_container"><div class="bx_scu">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
?>
				<li
					data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID'] ?>"
					data-onevalue="<? echo $arOneValue['ID']; ?>"
					style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>; display: none;"
				><i title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"></i>
				<span class="cnt"><span class="cnt_item"
					style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');"
					title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"
				></span></span></li>
<?
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?
		}
	}
	unset($arProp);
?>
</div>
<?
}
?>


<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP']))
{
	foreach ($arResult['JS_OFFERS'] as &$arOneJS)
	{
		if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE'])
		{
			$arOneJS['PRICE']['PRINT_DISCOUNT_DIFF'] = GetMessage('ECONOMY_INFO', array('#ECONOMY#' => $arOneJS['PRICE']['PRINT_DISCOUNT_DIFF']));
			$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
		}
		$strProps = '';
                $arOneJS['CAN_BUY'] = $canBuy;

		if ($arResult['SHOW_OFFERS_PROPS'])
		{
			if (!empty($arOneJS['DISPLAY_PROPERTIES']))
			{
				foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp)
				{
					$strProps .= '<dt>'.$arOneProp['NAME'].'</dt><dd>'.(
						is_array($arOneProp['VALUE'])
						? implode(' / ', $arOneProp['VALUE'])
						: $arOneProp['VALUE']
					).'</dd>';
				}
			}
		}
		$arOneJS['DISPLAY_PROPERTIES'] = $strProps;
	}
	if (isset($arOneJS))
		unset($arOneJS);
	$arJSParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => true,
			'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
			'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
			'DISPLAY_COMPARE' => ('Y' == $arParams['DISPLAY_COMPARE']),
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE']
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'NAME' => $arResult['~NAME'],
                        'CAN_BUY' => $canBuy
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'SKU_PROPS' => $arResult['OFFERS_PROP_CODES']
		),
		'OFFERS' => $arResult['JS_OFFERS'],
		'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS' => $arSkuProps
	);
}
else
{
	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
	if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
	{
?>
<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
<?
		if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
		{
			foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
			{
?>
	<input
		type="hidden"
		name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
		value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>"
	>
<?
				if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
					unset($arResult['PRODUCT_PROPERTIES'][$propID]);
			}
		}
		$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
		if (!$emptyProductProperties)
		{
?>
	<table>
<?
			foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo)
			{
?>
	<tr><td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
	<td>
<?
				if(
					'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE']
					&& 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']
				)
				{
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><label><input
							type="radio"
							name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
							value="<? echo $valueID; ?>"
							<? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>
						><? echo $value; ?></label><br><?
					}
				}
				else
				{
					?><select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><option
							value="<? echo $valueID; ?>"
							<? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>
						><? echo $value; ?></option><?
					}
					?></select><?
				}
?>
	</td></tr>
<?
			}
?>
	</table>
<?
		}
?>
</div>
<?
	}
	$arJSParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => (isset($arResult['MIN_PRICE']) && !empty($arResult['MIN_PRICE']) && is_array($arResult['MIN_PRICE'])),
			'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
			'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
			'DISPLAY_COMPARE' => ('Y' == $arParams['DISPLAY_COMPARE']),
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE']
		),
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'PICT' => $arFirstPhoto,
			'NAME' => $arResult['~NAME'],
			'SUBSCRIPTION' => true,
			'PRICE' => $arResult['MIN_PRICE'],
			'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
			'SLIDER' => $arResult['MORE_PHOTO'],
			'CAN_BUY' => $arResult['CAN_BUY'],
			'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
			'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
			'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
			'BUY_URL' => $arResult['~BUY_URL'],
		),
		'BASKET' => array(
			'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS' => $emptyProductProperties,
			'BASKET_URL' => $arParams['BASKET_URL']
		)
	);
	unset($emptyProductProperties);
}
?>
<script type="text/javascript">
BX.message({
	MESS_BTN_BUY: '<? echo ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CT_BCE_CATALOG_BUY')); ?>',
	MESS_BTN_ADD_TO_BASKET: '<? echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CT_BCE_CATALOG_ADD')); ?>',
	MESS_NOT_AVAILABLE: '<? echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? CUtil::JSEscape($arParams['MESS_NOT_AVAILABLE']) : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE')); ?>',
	TITLE_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
	TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
	BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
	BTN_SEND_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
	BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE') ?>',
	SITE_ID: '<? echo SITE_ID; ?>'
});
var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);

</script>