<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['ERROR']))
{
	echo $arResult['ERROR'];
	return false;
}
$search = $_POST['ingr'];
foreach ($arResult['rows'] as $row) {
    $pic = "";
    if (preg_match('/src=\"(.+)\"/Usi',$row["UF_ITEM_PIC"],$matches)) {
        $pic = $matches[1];
    }
    if (!in_array($row["ID"],$search))
        continue;
?>
        <div class="search-item">
            <div class="inner">
                <div class="pic" style="background-image:<?=($pic ? "url({$pic});" : "none")?>;"></div>
                <div class="cat-title"><?=$row["UF_NAME"]?></div>
            </div>
        </div>
<?}?>
