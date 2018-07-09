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
?>

<?
                $res = CIBlockSection::GetList(array(),array(
                    "IBLOCK_ID" => 2,
                    "ID" => $arResult["ID"],
                ),false,array("ID","IBLOCK_ID","UF_SEO_TEXT","UF_SEO_TEXT_NSK"));
                if ($row = $res->GetNext()) {
                    $seo_text = html_entity_decode(convert_tpl(trim($row["UF_SEO_TEXT"])));
                    $seo_text_nsk =  html_entity_decode(convert_tpl(trim($row["UF_SEO_TEXT_NSK"])));
                }
if (!empty($arResult['ITEMS']))
{
	if ($arParams["DISPLAY_TOP_PAGER"])
	{
		?><? echo $arResult["NAV_STRING"]; ?><?
	}

	$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
	$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
	$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
?>
<div id="j_menu">
    <h2>Всё японское меню</h2><br/>
<ul class="bx_catalog_list_home items">

<?
foreach ($arResult['ITEMS'] as $key => $arItem)
{
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
	$strMainID = $this->GetEditAreaId($arItem['ID']);
        $orig_id = $arItem['ID'];

	$arItemIDs = array(
		'ID' => $strMainID,
		'PICT' => $strMainID.'_pict',
		'SECOND_PICT' => $strMainID.'_secondpict',

		'QUANTITY' => $strMainID.'_quantity',
		'QUANTITY_DOWN' => $strMainID.'_quant_down',
		'QUANTITY_UP' => $strMainID.'_quant_up',
		'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
		'BUY_LINK' => $strMainID.'_buy_link',
		'SUBSCRIBE_LINK' => $strMainID.'_subscribe',

		'PRICE' => $strMainID.'_price',
		'DSC_PERC' => $strMainID.'_dsc_perc',
		'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',

		'PROP_DIV' => $strMainID.'_sku_tree',
		'PROP' => $strMainID.'_prop_',
		'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
		'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
	);

	$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

	$strTitle = (
		isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
		? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
		: $arItem['NAME']
	);
	?><li class="bx_catalog_item_container j_menu_el" id="<? echo $strMainID; ?>">
<pre><?//=var_dump($arParams);die;?></pre>  
  
<?
$arJSParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG']['CATALOG'],
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
		?><script type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
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
</script>
                                    <? if ($arItem["SORT"]) { ?><span class="num"><?=str_pad($arItem["SORT"],4,'0',STR_PAD_LEFT)?></span><? } //['DISPLAY_PROPERTIES']['ARTNUMBER']["VALUE"] ?>
                                    <?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", "menu", array(
                                                    "IBLOCK_TYPE" => $arItem['IBLOCK_TYPE'],
                                                    "IBLOCK_ID" => $arItem['IBLOCK_ID'],
                                                    "ELEMENT_ID" => $arItem['ID'],
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
                                    if ($arItem["PREVIEW_PICTURE"]["ID"]) {
                                        $arUserPhoto = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]["ID"]);
                                        $preview = CFile::ResizeImageGet($arUserPhoto,array("width" => 999,"height" => 126) );
                                    } else {
                                        $preview['src'] = $arItem["PREVIEW_PICTURE"]["SRC"];
                                    }
                                        
                                    $constPic["src"] = SUSHMAN_MAIN_PATH."/img/wok-filling-default.png";
                                    if ($arItem["PROPERTIES"]["CONST_PIC"]["VALUE"]) {
                                        $arConstPic = CFile::GetFileArray($arItem["PROPERTIES"]["CONST_PIC"]["VALUE"]);
                                        $constPic = CFile::ResizeImageGet($arConstPic,array("width" => 400),BX_RESIZE_IMAGE_PROPORTIONAL );
                                    }
                                    ?>
                                    <a target="_blank" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" data-popup_href="/menu/detail.php?SECTION_ID=<?=$arItem["SECTION_ID"]?>&ELEMENT_ID=<?=$arItem["ID"]?>" id="<? echo $arItemIDs['PICT']; ?>" title="<? echo $strTitle; ?>"><span class="pic"<?=($constPic["src"] ? " const_pic='{$constPic["src"]}'" : "")?>><img src="<?=$preview['src']?>"/></span>
                                        <span class="title valigned t" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" title="<? echo $arItem['NAME']; ?>"><span><? echo $arItem['NAME']; ?></span></span></a>
<?
	if (false && 'Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
	{
	?>
			<div
				id="<? echo $arItemIDs['DSC_PERC']; ?>"
				class="bx_stick_disc right bottom"
				style="display:<? echo (0 < $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">-<? echo $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>%</div>
	<?
	}
	if (false && $arItem['LABEL'])
	{
	?>
			<div class="bx_stick average left top" title="<? echo $arItem['LABEL_VALUE']; ?>"><? echo $arItem['LABEL_VALUE']; ?></div>
	<?
	}
	?>

        <div class="price-block">
                                    <div class="short-desc">
                                        <?=$arItem["PREVIEW_TEXT"]?>
                                    </div>
          <div class="price-block2">
	<span class="price bx_price" id="<? echo $arItemIDs['PRICE']; ?>"><?
            if (isset($arItem['OFFERS']) && !empty($arItem['OFFERS'])) {
                    global $city, $cities;
                    $cityCode = $cities[$city]["CODE"];
                    $arItem['OFFERS_SELECTED'] = -1;
                    foreach ($arItem['OFFERS'] as $k => $v)
                    {
                       if ($cityCode==$v["PROPERTIES"]["CITY"]["VALUE"])
                        $arItem['OFFERS_SELECTED'] = $k;
                    }
                    $arItem = $arItem['OFFERS'][ $arItem["OFFERS_SELECTED"] ];
            }
            
	if (!empty($arItem['MIN_PRICE']))
	{
			echo str_replace("руб.","<span class='ico-rub'></span>",$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);
		if ('Y' == $arParams['SHOW_OLD_PRICE'] && $arItem['MIN_PRICE']['DISCOUNT_VALUE'] < $arItem['MIN_PRICE']['VALUE'])
		{
			?> <span><? echo $arItem['MIN_PRICE']['PRINT_VALUE']; ?></span><?
		}
	}
	?></span>
<?
$item_weight = $arItem["CATALOG_WEIGHT"];
if ($item_weight) { ?>
    <span class="weight"><?=$item_weight?>&nbsp;г</span>
<?}?>
<div class="clear"></div>
          </div>
        <div class="q-block">
            <?
            if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS']))
            {
		if ($arItem['CAN_BUY'])
		{
			if ('Y' == $arParams['USE_PRODUCT_QUANTITY'])
			{
			?>
                        <span id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>" class="arrow arrow-left"></span><span id="<? echo $arItemIDs['QUANTITY_UP']; ?>" class="arrow arrow-right"></span>
			<input type="text" class="bx_col_input" id="<? echo $arItemIDs['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<? echo $arItem['CATALOG_MEASURE_RATIO']; ?>">
			<?
			}
			?>
			<?
		}
		else
		{
			?><span class="bx_notavailable"><?
			echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE'));
			?></span><?
		}
            }
        ?>
        </div>

        </div><?
	if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS']))
	{
		if ($arItem['CAN_BUY'])
		{
			?>
			<a id="<? echo $strMainID; ?>_buy_link" class="buy bx_bt_button bx_medium" href="javascript:void(0)" rel="nofollow" data-product-id="<?=$arItem["ID"]?>" data-id="<?=$orig_id?>"></a>
			<?
		}
		else
		{
			if ('Y' == $arParams['PRODUCT_SUBSCRIPTION'] && 'Y' == $arItem['CATALOG_SUBSCRIPTION'])
			{
			?>
				<a
					id="<? echo $arItemIDs['SUBSCRIBE_LINK']; ?>"
					class="buy bx_bt_button_type_2 bx_medium"
					href="javascript:void(0)"></a>
                        <?
			}
		}
		if (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']))
		{ /*
?>
			<div class="bx_catalog_item_articul">
<?
			foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp)
			{
				?><br><span style="display:none;"><? print_r($arOneProp);?></span><strong><? echo $arOneProp['NAME']; ?></strong> <?
					echo (
						is_array($arOneProp['DISPLAY_VALUE'])
						? implode('<br>', $arOneProp['DISPLAY_VALUE'])
						: $arOneProp['DISPLAY_VALUE']
					);
			}
?>
			</div>
<?
                    */
		}
		$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
		if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
		{
?>
		<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
<?
			if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
			{
				foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
				{
?>
					<input
						type="hidden"
						name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
						value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>"
						>
<?
					if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
						unset($arItem['PRODUCT_PROPERTIES'][$propID]);
				}
			}
			$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
			if (!$emptyProductProperties)
			{
?>
				<table>
<?
					foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo)
					{
?>
						<tr><td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
							<td>
<?
								if(
									'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
									&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
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
                </div></li>

<?
		}

	}

}?>
                    </ul><div class="clear"></div>
</div>
<?

    if ($seo_text || $seo_text_nsk) {
        echo "<div class='additional-text'>".($cityCode=='nsk' ? $seo_text_nsk : $seo_text)."</div>";
    }

} else {
?>
<br/><br/>
<p>В этом разделе нет товаров</p>
<?
}