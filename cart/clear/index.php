<?
$header_class="basket-header";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
?>

<?php
CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>