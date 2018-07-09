<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!empty($arResult['ERROR'])) {
    echo $arResult['ERROR'];
    return false;
}
$ingrs = $_POST['country'];

?>
<ul>
    <? foreach ($arResult['rows'] as $row) { ?>
        <li>
            <input type="checkbox" name="country[]" id="sc<?= $row["ID"] ?>"
                   value="<?= $row["ID"] ?>" <?= (is_array($ingrs) && in_array($row["ID"],
                $ingrs) ? "checked" : "") ?>/><label for="sc<?= $row["ID"] ?>"><?= $row["UF_NAME"] ?></label>
        </li>
    <? } ?>
</ul>