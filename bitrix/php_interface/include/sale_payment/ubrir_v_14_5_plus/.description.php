<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include(GetLangFileName(dirname(__FILE__)."/lang/", "/payment.php"));

$psTitle = GetMessage("SALE_HPS_UBRIR");
$psDescription = GetMessage("SALE_HPS_UBRIR_DESCRIPTION");

$arPSCorrespondence = array(
	"ORDER_ID" => array(
		"NAME" => GetMessage("ORDER_ID"),
		"DESCR" => GetMessage("ORDER_ID_DESCR"),
		"VALUE" => "ID",
		"TYPE" => "ORDER"
	),
	"ORDER_DATE" => array(
		"NAME" => GetMessage("ORDER_DATE"),
		"DESCR" => GetMessage("ORDER_DATE_DESCR"),
		"VALUE" => "DATE_INSERT_DATE",
		"TYPE" => "ORDER"
	),
	"SHOULD_PAY" => array(
		"NAME" => GetMessage("SHOULD_PAY"),
		"DESCR" => GetMessage("SHOULD_PAY_DESCR"),
		"VALUE" => "PRICE",
		"TYPE" => "ORDER"
	),
	"CHANGE_STATUS_PAY" => array(
		"NAME" => GetMessage("PYM_CHANGE_STATUS_PAY"),
		"DESCR" => GetMessage("PYM_CHANGE_STATUS_PAY_DESC"),
		"VALUE" => "Y",
		"TYPE" => ""
	),

);