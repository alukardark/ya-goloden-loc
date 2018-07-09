<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

$seo_sources_url = "http://web-axioma.ru/seo_sources.json";
CModule::IncludeModule("iblock");

function get_sources() {
    global $seo_sources_url;
    $json = file_get_contents($seo_sources_url);
    if ($json === FALSE)
        return null;
    return json_decode($json);
}

function get_phones($iblock_id, $phone_key) {
    $result = array();
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_phone_key", "PROPERTY_source_key", "PROPERTY_value");
    $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => $iblock_id, "IBLOCK_TYPE" => "phone_subst", "IBLOCK_CODE" => "phone_subst", "PROPERTY_phone_key" => $phone_key), false, Array(), $arSelect);
    while ($ar_res = $res->Fetch()) {
        $result[$ar_res["PROPERTY_SOURCE_KEY_VALUE"]] = $ar_res["PROPERTY_VALUE_VALUE"];
    }
    return $result;
}

function update_phone($iblock_id, $phone_key, $source_key, $value) {
    $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => $iblock_id, "IBLOCK_TYPE" => "phone_subst", "IBLOCK_CODE" => "phone_subst", "PROPERTY_phone_key" => $phone_key, "PROPERTY_source_key" => $source_key));
    $data = array(
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID" => $iblock_id,
        "PROPERTY_VALUES" => array(
            "phone_key" => $phone_key,
            "source_key" => $source_key,
            "value" => $value
        ),
        "NAME" => $phone_key . "-" . $source_key,
        "ACTIVE" => "Y",
    );
    $ar_res = $res->Fetch();
    $el = new CIBlockElement;
    if (!$ar_res) {
        $res = $el->Add($data);
    } else {
        $el->Update($ar_res["ID"], $data);
    }
}

function update_ext_phone($iblock_id, $phone_key, $ext_key) {
    $url = $_REQUEST[$ext_key."_url"];
    update_phone($iblock_id, $phone_key, $ext_key."_url", $url);
    $phone = $_REQUEST[$ext_key];
    update_phone($iblock_id, $phone_key, $ext_key, $phone);
}

$res = CIBlock::GetList(Array(), Array(
            'TYPE' => 'phone_subst',
            'ACTIVE' => 'Y'
                ), true);
$ar_res = $res->Fetch();
$iblock_id = $ar_res["ID"];
$phone_key = $_GET['key'];
$sources = get_sources();
$phones = get_phones($iblock_id, $phone_key);

if ($REQUEST_METHOD == "POST" && strlen($Update) > 0 && check_bitrix_sessid()) {
    if (!$iblock_id) {
        LocalRedirect("/bitrix/admin/phone_subst.php?key=" . $phone_key . "&msg=fail_iblock&lang=" . LANG);
    }
    foreach ($sources as $source) {
        if ($source->type == "title")
            continue;
        $v = trim($_REQUEST[$source->name]);
        update_phone($iblock_id, $phone_key, $source->name, $v);
    }
    update_ext_phone($iblock_id,$phone_key,"ext1");
    update_ext_phone($iblock_id,$phone_key,"ext2");
    update_ext_phone($iblock_id,$phone_key,"ext3");
    LocalRedirect("/bitrix/admin/phone_subst.php?key=" . $phone_key . "&lang=" . LANG);
}
$APPLICATION->SetTitle("Подмена номеров");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>
<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?key=<?= $phone_key ?>" name="form1">
    <input type="hidden" name="Update" value="Y">
<?= bitrix_sessid_post() ?>
    <table>
    <?php
    foreach ($sources as $source) {
        if ($source->type == 'title') {
            ?>
                <tr><td colspan="2"><?= $source->title ?></td></tr>
                <?php
                continue;
            }
            ?>
            <tr><td><?= $source->title ?></td><td><input type="text" name="<?= $source->name ?>" value="<?= $phones[$source->name] ?>"/></td></tr>
            <?php
        }
        ?>
        <tr><td colspan="2">Внешние сайты</td></tr>
        <tr><td><input type="text" name="ext1_url" value="<?=$phones['ext1_url']?>"/></td><td><input type="text" name="ext1" value="<?=$phones['ext1']?>"/></td></tr>
        <tr><td><input type="text" name="ext2_url" value="<?=$phones['ext2_url']?>"/></td><td><input type="text" name="ext2" value="<?=$phones['ext2']?>"/></td></tr>
        <tr><td><input type="text" name="ext3_url" value="<?=$phones['ext3_url']?>"/></td><td><input type="text" name="ext3" value="<?=$phones['ext3']?>"/></td></tr>
        <tr><td colspan="2"><input type="submit" class="button" name="save" value="Сохранить изменения"/></td></tr>
    </table>
</form>
<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");