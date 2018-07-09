<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!empty($arResult['ERROR'])) {
    echo $arResult['ERROR'];
    return false;
}
?>
<? foreach ($arResult['rows'] as $row) {
    $pic = "";
    if (preg_match('/src=\"(.+)\"/Usi', $row["UF_ITEM_PIC"], $matches)) {
        $pic = $matches[1];
    }

    ?>
    <div class="swiper-slide">
        <div class="inner">
            <div class="pic" style="background-image:<?= ($pic ? "url({$pic});" : "none") ?>;"></div>
            <div class="cat-title"><?= $row["UF_NAME"] ?></div>
            <div class="cb"><input type="checkbox" name="ingr[]" value="<?= $row["ID"] ?>"
                                   id="ccb<?= $row["ID"] ?>"><label for="ccb<?= $row["ID"] ?>"></label></div>
        </div>
    </div>
<? } ?>
