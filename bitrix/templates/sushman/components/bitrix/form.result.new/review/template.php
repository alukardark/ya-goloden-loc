<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="popup-form">
<?=($arResult["FORM_NOTE"] ? "<div class='success success2'>Ваш отзыв был отправлен<br/>После проверки он будет опубликован</div>" : "")?>

<?if ($arResult["isFormNote"] != "Y")
{
?>
<h2 class="sm">Оставить отзыв</h2>
<?=$arResult["FORM_HEADER"]?>

<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>

<?
if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y")
{
?>
			<p><?=$arResult["FORM_DESCRIPTION"]?></p>
	<?
} // endif
	?>
<?
/***********************************************************************************
						form questions
***********************************************************************************/
?>
<div class="fields sm">
	<?
	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
	{
		if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
		{
			echo $arQuestion["HTML_CODE"];
		}
		else
		{
	?>
			<?=$arQuestion["HTML_CODE"]?>
                        <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                        <span class="error-fld"><?=$arResult["FORM_ERRORS"][$FIELD_SID]?></span><br/>
                        <?endif;?>
	<?
		}
	} //endwhile
	?>
<input <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" class="btn btn-grad2" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" /></span>
</div>
<?=$arResult["CAPTCHA_HTML_CODE"]?>
<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
?>
</div>
<script type="text/javascript">
    $(".popup-form input[type=file]").customFile();
    $(".popup-form .file-upload-wrapper").before("<div id='files'/>");
</script>