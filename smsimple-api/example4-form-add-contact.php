<?php

    require_once('./smsimple.config.php');
    require_once('./smsimple.class.php');

    header('Content-type: text/html; charset=utf-8');
    
    if ($_POST['phone']):
        $sms = new SMSimple(array(
            'url' => SMS_API,
            'username' => SMS_USERNAME,
            'password' => SMS_PASSWORD,
        ));

        try {
            
            $group_id = 12345; // вставьте правильный номер группы

            $sms->connect();
            $contact_id = $sms->addContactToGroup(array(
                'group_id'   => $group_id,
                'phone'      => $_POST['phone'],
                'title'      => $_POST['title'],
                'custom_1'   => "",
                'custom_2'   => "",
            ));
            echo "Телефон добавлен в группу $group_id";

        }
        catch (SMSimpleException $e) {
            print $e->getMessage();
        }

    else:
?>

<html>
<body>
<form method="post">
Имя <input type="text" name="title" size="15"><br/>
Телефон <input type="text" name="phone" size="15"><br/>
<input type="submit" value="Добавить">
</form>
</body>
</html>

<?    endif;
