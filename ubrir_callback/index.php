<?
include( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php" );

use Bitrix\Main\Loader;
use ISYS\UBRIR\CallbackHandler;

Loader::includeModule( 'isys.ubrir' );
Loader::includeModule( 'sale' );

$bankPaymentHandler = new CallbackHandler();
$bankPaymentHandler->ProcessCallbackResponse();

if ( ! $bankPaymentHandler->isRequestSuccessfullyParsed() ) {
	die(); //Что-то странное
}


$bankPaymentHandler->ShowCallbackProcessResult();

// Если оплата прошла
if ( $bankPaymentHandler->isPaymentApproved() ) {

	// Найду настроки платежной системы по заказу
	$shopOrderId = $bankPaymentHandler->GetShopOrderId();
	$arOrder     = CSaleOrder::GetByID( $shopOrderId );
	if ( ! $arOrder ) {
		echo "Заказ с кодом " . $shopOrderId . " не найден";
	}
	// Найду платежную систему заказа, что бы узнать её настройки
	$arPaySys = CSalePaySystem::GetByID( $arOrder['PAY_SYSTEM_ID'], $arOrder['PERSON_TYPE_ID'] );
	if ( ! $arPaySys ) {
		echo "Платежная система не найдена";
	}
	// достану параметры обработчика
	$arPaySysParams        = CSalePaySystemAction::UnSerializeParams( $arPaySys['PSA_PARAMS'] );
	$autoChangeOrderStatus = $arPaySysParams['CHANGE_STATUS_PAY']['VALUE'];

	// Менять статус
	if ( $autoChangeOrderStatus == 'Y' ) {
		//Тут меняем
		$paidStatus    = "P"; //Оплачен, формируется к отправке
		$StatusChanged = CSaleOrder::StatusOrder(
			$shopOrderId, $paidStatus );
		if ( ! $StatusChanged ) {
			echo "Ошибка изменения статуса";
		}

	}


}