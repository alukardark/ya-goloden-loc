<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['ERROR']))
{
	echo $arResult['ERROR'];
	return false;
}
global $city;
$availableIPs = array(
	'176.197.241.81',
	'46.149.226.65'
);
?>
<select name="city">
<?foreach ($arResult['rows'] as $k => $row) {
    if ($row['UF_ACTIVE']=='Нет')
        continue;
	//if($row['UF_NAME'] == 'Кемерово' and !in_array($_SERVER['REMOTE_ADDR'], $availableIPs))
	//continue;

?>
    <option value="<?=$row['ID']?>"<?=($row['ID']==$city ? " selected" : "")?>><?=$row["UF_NAME"]?></option>
<?}?>
</select>