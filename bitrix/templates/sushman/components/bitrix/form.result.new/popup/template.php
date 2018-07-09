<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?if ($arResult["isFormErrors"] != "Y" && $arResult["isFormNote"] == "Y") { ?>
<p class="success">
    <strong>Спасибо за Ваш отклик!<br/>Вы обязательно получите ответ в самое ближайшее время!</strong><br/>
</p>
<? } ?>
<?
	foreach($arResult["FORM_ERRORS"] as $k => $v) {
            if ($k=="NAME")
                $arResult["FORM_ERRORS"][$k] = "Введите имя";
            if ($k=="PHONE")
                $arResult["FORM_ERRORS"][$k] = "Введите телефон";
            if ($k=="EMAIL")
                $arResult["FORM_ERRORS"][$k] = "Введите e-mail";
            if ($k=="MESSAGE")
                $arResult["FORM_ERRORS"][$k] = "Введите сообщение";
        }

?>
<?if ($arResult["isFormNote"] != "Y")
{
?>
<?=$arResult["FORM_HEADER"]?>

<? if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y") { ?>
<p class="form-description"><?=$arResult["FORM_DESCRIPTION"]?></p>
<? } ?>
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>


<?
/***********************************************************************************
						form questions
***********************************************************************************/
?>
<div class="fields">
	<?

	$availableIPs = array(
		'158.46.22.198',
		'46.149.226.65'
	);

	SetGlobalVars();
	global $city, $cities;

	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
	{
		//if($arQuestion['CAPTION'] == 'Город' and !in_array($_SERVER['REMOTE_ADDR'], $availableIPs)) continue;
	?>
			<?=$arQuestion["HTML_CODE"]?>
                        <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                        <span class="error-fld"><?=$arResult["FORM_ERRORS"][$FIELD_SID]?></span><br/>
                        <?endif;?>
	<?
	} //endwhile
	?>
    <div class="privacy-agr">Нажимая на кнопку, вы даете <a target="_blank" href="/pi/">согласие на обработку персональных данных</a></div>
<input <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" class="btn btn-grad2" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
</div>
<?=$arResult["CAPTCHA_HTML_CODE"]?>
<script type="text/javascript">
                $(".fields input.phone").inputmask({ mask:"+7 9999999999", showMaskOnHover: false, onincomplete: function() {
                        this.value = $(this).val().replace(/_/g,"");
                    } } );

</script>
<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
?>
