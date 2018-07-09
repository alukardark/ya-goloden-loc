<?

class Recall
{
    protected $currentDate;
    protected $availableIPs = array(
        '158.46.22.198',
        '46.149.226.65',
        '158.46.18.231'
    );

    function __construct()
    {
        $this->currentDate = date('d.m.Y H:i:s');
    }

    function checkAccess()
    {
        return in_array($_SERVER['REMOTE_ADDR'], $this->availableIPs);
    }

    function auto_run()
    {
        //if(!$this->checkAccess()) return;
        $out = array();
        if (!is_not_order_time()) {
            $out[] = $this->renderButton();
            $out[] = $this->renderForm();
            $out[] = $this->addScript();
        }
        return implode($out);

    }

    function addScript()
    {
        return '<script type="text/javascript">
					$(".recall-form-wrapper input[name=phone]").inputmask({ mask:"+7 9999999999", showMaskOnHover: false, onincomplete: function() { 
							this.value = $(this).val().replace(/_/g,""); 
						} } );
				</script>';
    }

    function renderButton()
    {
        return '<a href="javascript:" class="recall-button">Перезвоните мне</a>';
    }

    function renderForm()
    {
        $out = array();

        $out[] = '<div class="recall-form-wrapper">';
        $out[] = '<form>';
        $out[] = '<div class="title">Ваш номер</div>';
        $out[] = '<input type="text" name="phone" placeholder="Телефон" />';
        $out[] = '<input type="hidden" name="hidden" />';
        $out[] = '<div class="privacy-agr">Нажимая на кнопку, вы даете <a target="_blank" href="/pi/">согласие на обработку персональных данных</a></div>';
        $out[] = '<input type="submit" name="submit" value="Срочно перезвонить!" />';
        $out[] = '</form>';
        $out[] = '</div>';

        return implode($out);
    }

    function log()
    {
        SetGlobalVars();
        global $city, $cities;

        CModule::IncludeModule('form');
        CFormResult::Add(2, ['form_text_8' => $_POST['phone'], 'form_text_9' => $cities[$city]['NAME']], "N", 1);
    }

    function submit()
    {
        $this->email();
        $this->sms();
        $this->log();

        return json_encode(array(
            'success' => 1,
            'message' => 'Спасибо за Ваш отклик!<br/>Вы обязательно получите ответ в самое ближайшее время!'
        ));
    }

    function email()
    {
        SetGlobalVars();
        global $city, $cities;

        $emails = explode(',', trim($cities[$city]['EMAIL']));

        if (is_array($emails)) {

            foreach ($emails as $email) {

                $data = array(
                    'CITY' => $cities[$city]['NAME'],
                    'RECIPIENT' => trim($email),
                    'PHONE' => $_POST['phone'],
                    'DATE' => $this->currentDate
                );

                CEvent::Send('RECALL_FORM', s1, $data, 'N', 76);
            }
        } else {

            $data = array(
                'CITY' => $cities[$city]['NAME'],
                'RECIPIENT' => $emails,
                'PHONE' => $_POST['phone'],
                'DATE' => $this->currentDate
            );

            CEvent::Send('RECALL_FORM', s1, $data, 'N', 76);
        }
    }

    function sms()
    {
        if (SMS_PROVIDER == 'smsphpclass') {
            SetGlobalVars();
            global $city, $cities;

            require_once($_SERVER[DOCUMENT_ROOT] . "/bitrix/templates/.default/smsPHPClass/transport.php");
            $api = new Transport();

            $phone = $cities[$city]["SMS_PHONE"];
            $phone = str_replace(array(" ", "-", "+", "(", ")"), "", trim($phone));
            if ($phone) {
                $params = array(
                    "onlydelivery" => "0",
                    "text" => "Перезвоните пользователю. Телефон: {$_POST['phone']}. ya-goloden.ru"
                );

                $send = $api->send($params, explode(",", $phone));
            }
        }
        if (SMS_PROVIDER == 'smsimple') {
            SetGlobalVars();
            global $city, $cities;

            $phone = $cities[$city]["SMS_PHONE"];
            $phone = str_replace(array(" ", "-", "+", "(", ")"), "", trim($phone));
            if ($phone) {
                $msg = "Перезвоните пользователю. Телефон: {$_POST['phone']}. ya-goloden.ru";
                $send = simpleSMS($phone, $msg);
            }
        }
    }
}

switch ($_POST['action']) {
    case 'submit':
        include $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include.php";
        $obj = new Recall;
        echo $obj->submit();
        break;

    default:
        $obj = new Recall;
        echo $obj->auto_run();
        break;
}

?>