<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!empty($arResult['ERROR'])) {
    echo $arResult['ERROR'];
    return false;
}
$ingrs = $_POST['ingr'];

?>
<ul id="discounts">
    <input type="radio" name="discount" value="" id="discount_none"/>
    <? foreach ($arResult['rows'] as $row) { ?>
        <?
        $pic = "";
        if (preg_match('/src=\"(.+)\"/Usi', $row["UF_PIC"], $matches)) {
            $pic = $matches[1];
        }
        ?>
        <li>
            <input type="radio" name="discount" value="<?= $row["ID"] ?>" id="discount_<?= $row["ID"] ?>"/>
            <label for="discount_<?= $row["ID"] ?>">
                <?= ($pic ? "<img src='{$pic}' alt='{$row["UF_NAME"]}'/>" : "") ?>
                <!-- <span class="title"><?= $row["UF_NAME"] ?></span> -->
                <span class="desc"><span class="valigned"><span><?= $row["UF_DESCRIPTION"] ?></span></span></span>
            </label>
        </li>
    <? } ?>
</ul>