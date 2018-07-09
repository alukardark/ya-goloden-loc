<?

require_once($_SERVER['DOCUMENT_ROOT'] . '/smsimple-api/smsimple.config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/smsimple-api/smsimple.class.php');

$sms = new SMSimple(array(
    'url' => SMS_API,
    'username' => SMS_USERNAME,
    'password' => SMS_PASSWORD,
));

try {

    $sms->connect();
    $origins_list = $sms->origins();
    $message = '';
    $error = false;

    $message_id = $sms->send(64972, '89505836764', 'тестовое сообщение asd', true);
    $message = 'Сообщения отправлены (#' . join(', #', $message_id) . ')';

} catch (SMSimpleException $e) {
    $error = true;
    $message = $e->getMessage();
}

var_dump($message_id);
echo $message;

?>