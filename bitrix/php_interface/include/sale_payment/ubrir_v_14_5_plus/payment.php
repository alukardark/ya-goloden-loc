<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use ISYS\UBRIR\UbrirPayment;

Loc::loadMessages(__FILE__);
Loader::includeModule('isys.ubrir');

$paymentControl = new UbrirPayment();

$callbackURL = ISYS\UBRIR\Helpers::GetHostProtocol() . '://' . SITE_SERVER_NAME . '/ubrir_callback/'; //isMyResult обрабатывает

$amount = CSalePaySystemAction::GetParamValue("SHOULD_PAY");
$orderDate = CSalePaySystemAction::GetParamValue("ORDER_DATE");
$orderId = CSalePaySystemAction::GetParamValue("ORDER_ID");
$paymentId = $orderId; // в старой версии нет оплат

try {
	$responseData = $paymentControl->SetupAndPrepareToPay($paymentId, $amount, $callbackURL);

	if ($responseData->isSuccess()) {
		$TWPG_OrderId = $responseData->GetTWPG_OrderId();
		$SessionId = $responseData->GetSessionId();

		$paymentControl->SaveTWPGOrder($orderId, $TWPG_OrderId, $SessionId, $paymentId); //, $ecomSessionID

		//Уводим пользователя платить
		$formUrl = $responseData->getFieldValue('Url');
		//$PaymentURL = $responseData->GetPaymentUrl($TWPG_OrderId, $SessionId);

		$params = array(
			'URL' => $formUrl,
			'TWPG_ORDER_ID' => $TWPG_OrderId,
			'TWPG_SESSION_ID' => $SessionId
		);
	} else {
		$params = array(
			'ERROR' => 'Ошибка подготовки перед совершением платежа. Обратитесь к администратору магазина'
		);
	}

} catch (\Exception $exception) {
	$paymentControl->Log($exception->getMessage());

	$params = array(
		'ERROR' => 'Платежный шлюз не настроен, обратитесь к администратору'
	);
}



$sum = roundEx($params['PAYMENT_SHOULD_PAY'], 2);
?>

<div class="sale-paysystem-wrapper">
	<? if (!empty($params['ERROR'])): ?><?= $params['ERROR'] ?>
	<? else: ?>
		<span class="tablebodytext">
		<?= Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_UBRIR_DESCRIPTION'); ?>
		<?= SaleFormatCurrency($amount, 'RUB'); ?>
	</span>
		<form name="ShopForm" action="<?= $params['URL']; ?>" method="get">
			<input type="hidden" name="orderid" value="<?= $params['TWPG_ORDER_ID'] ?>"/>
			<input type="hidden" name="sessionid" value="<?= $params['TWPG_SESSION_ID'] ?>"/>

			<div class="sale-paysystem-ubrir-button-container">
			<span class="sale-paysystem-ubrir-button">
                <button class="sale-paysystem-ubrir-button-item"><?= Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_UBRIR_BUTTON_PAID') ?></button>
			</span>
				<span class="sale-paysystem-ubrir-button-descrition"><?= Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_UBRIR_REDIRECT_MESS'); ?></span>
			</div>
			<p>
				<span class="tablebodytext sale-paysystem-description"><?= Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_UBRIR_WARNING_RETURN'); ?></span>
			</p>
		</form>
	<? endif ?>
</div>