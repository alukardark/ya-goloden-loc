<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column
?>
<div class="bx_ordercart">
	<div class="bx_ordercart_order_pay">
		<div class="bx_ordercart_order_pay_right" style="float:left;width:50%;margin-left:0;margin-top:30px;">
			<table class="bx_ordercart_order_sum">
        <thead>
          <tr>
            <th style="display:none !important;">Вес</th>
            <th>Цена</th>
					<? if (doubleval($arResult["DISCOUNT_PRICE"]) > 0) { ?>
              <th><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?><?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?> (<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)<?endif;?></th>
          <? } ?>
          <? if (doubleval($arResult["DELIVERY_PRICE"]) > 0) { ?>
            <th class='delivery'>Доставка</th>
          <? } ?>
          
            <th>Итого</th>
            <? if ((int)$_REQUEST["PAY_SYSTEM_ID"]<=1) { ?>
            <th>Купюра</th>
            <th>Сдача</th>
            <? } ?>
          </tr>
        </thead>
				<tbody>
          <tr>
            <td class="custom_t2" style="display:none !important;" class="price"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
            <td class="custom_t2" class="price"><?=str_replace('руб.','<span class="ico-rub"></span>',$arResult["ORDER_PRICE_FORMATED"])?></td>
					<? if (doubleval($arResult["DISCOUNT_PRICE"]) > 0) { ?>
            <td class="custom_t2" class="price"><?=$arResult["DISCOUNT_PRICE"]?><span class="ico-rub"></span></td>
          <? } ?>
          <? if (doubleval($arResult["DELIVERY_PRICE"]) > 0) { ?>
            <td class="custom_t2" class="price"><?=str_replace('руб.','<span class="ico-rub"></span>',$arResult["DELIVERY_PRICE_FORMATED"])?></td>
          <? } ?>
            <td class="custom_t2 fwb" class="price"><?=str_replace('руб.','<span class="ico-rub"></span>',$arResult["ORDER_TOTAL_PRICE_FORMATED"])?></td>
            <?
                $tt = (int)trim(str_replace(" ","",str_replace("руб.","",$arResult["ORDER_TOTAL_PRICE_FORMATED"])));
                $total = (float)ceil($tt/500.0)*500;
            ?>
            <? if ((int)$_REQUEST["PAY_SYSTEM_ID"]<=1) { ?>
            <td class="custom_t2" class="price"><input type="text" id="total_cash" name="total_cash" value="<?=$total;?>"/><span class="ico-rub"></span></td>
            <td class="custom_t2" class="price"><span class="change"><?=number_format($total-1.0*$tt,0,'.',' ');?></span><span class="ico-rub"></span></td>
            <? } ?>
          </tr>
          
				</tbody>
			</table>
			<div style="clear:both;"></div>

		</div>
	</div>
</div>
