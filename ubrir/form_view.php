<div id="callback" style="display: none;">
    <table>
        <tr>
            <h2 onclick="show(this);" style="text-align: center; cursor:pointer;">Обратная связь<span
                        style="margin-left: 20px; font-size: 80%; color: grey;"
                        onclick="jQuery('#callback').toggle();">[X]</span></h2>
        </tr>
        <tr>
            <td>Тема</td>
            <td>
                <select name="subject" id="mailsubject" style="width:150px">
                    <option selected disabled>Выберите тему</option>
                    <option value="Подключение услуги">Подключение услуги</option>
                    <option value="Продление Сертификата">Продление Сертификата</option>
                    <option value="Технические вопросы">Технические вопросы</option>
                    <option value="Юридические вопросы">Юридические вопросы</option>
                    <option value="Бухгалтерия">Бухгалтерия</option>
                    <option value="Другое">Другое</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Телефон</td>
            <td>
                <input type="text" name="email" id="mailem" style="width:150px">
            </td>
        </tr>
        <tr>
            <td>Сообщение</td>
            <td>
                <textarea name="maildesc" id="maildesc" cols="30" rows="10" style="width:150px;resize:none;"></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input id="sendmail" onclick="
			 var mailsubject = jQuery('#mailsubject').val();
			 var maildesc = jQuery('#maildesc').val();
			 var mailem = jQuery('#mailem').val();
			 console.log(mailsubject);
			 console.log(maildesc);
			 console.log(mailem);
			 if(!mailem & !!maildesc) {
			 jQuery('#mailresponse').html('<br>Необходимо указать телефон');
			 return false;
			 }
			 if(!maildesc & !!mailem) {
			 jQuery('#mailresponse').html('<br>Сообщение не может быть пустым');
			 return false;
			 }
			 if(!!mailem & !!maildesc) 
			 jQuery.ajax({
			 type: 'POST',
			 url: location.href,
			 data: {mailsubject:mailsubject, maildesc:maildesc, mailem:mailem, task_ubrir:7},
			 success: function(response){
			 jQuery('#mailresponse').html('Письмо отправлено на почтовый сервер');
			 jQuery('#maildesc').val(null);
			 jQuery('#mailsubject').val(null);
			 jQuery('#mailem').val(null);
			 }
			 });
			 else jQuery('#mailresponse').html('<br>Заполнены не все поля');
			 return false;
			 " type="button" name="sendmail" value="Отправить">
        </tr>
        <tr>
            <td>
            </td>
            <td style="padding: 0" id="mailresponse">
            </td>
        </tr>
        <tr>
            <td></td>
            <td>8 (800) 1000-200</td>
        </tr>
    </table>
</div>
<div id="ubrrwrap">
    <div id="cross">X</div>
    <div id="outinfo" style="width: 100%; margin-top: 10px;"></div>
    <div style="margin: 20px 0 20px 0; text-align: center; padding: 20px; width: 415px; border: 1px dashed #999;">
        <h3 style="text-align: center; padding: 0 0 20px 0; margin: 0;">Получить детальную информацию:</h3>
        <div style="margin: 0 auto; text-align: center; padding: 5px; width: 200px; border: 1px dashed #999;">
            <form id="ubrrform" action="/ubrir/form_controller.php" method="post">Номер
                заказа:
                <br>
                <input type="hidden" name="PID" value="<?php echo $_REQUEST['PID']; ?>">
                <input style="margin: 5px;" type="text" name="shoporderidforstatus" id="shoporderidforstatus"
                       value="" placeholder="№ заказа" size="8">
                <input style="margin: 5px;" type="hidden" name="task_ubrir" id="task_ubrir" value="">
                <input class="twpginput" type="button" id="statusbutton" value="Запросить статус заказа">
                <input class="twpginput" type="button" id="detailstatusbutton" value="Информация о заказе">
                <input class="twpginput" type="button" id="reversbutton" value="Отмена заказа"><br>
        </div>
        <input class="twpgbutton" type="button" id="recresultbutton" value="Сверка итогов">
        <input class="twpgbutton" type="button"
               onclick="$('input').removeAttr('disabled');$('#task_ubrir').val(5); submit();" id="journalbutton"
               value="Журнал операций">
        <!--    <input class="twpgbutton" type="button" onclick="$('#task_ubrir').val(6); submit();" id="unijournalbutton"-->
        <!--             value="Журнал операций MasterCard">-->
        <input class="twpgbutton" type="button" onclick="jQuery('#callback').toggle();" id="mailbutton"
               value="Написать в банк">
        </form>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var $form = jQuery('#ubrrform');

                function formsubmit() {
                    var $data = $form.serialize();
                    jQuery.ajax({
                        url: $form.attr('action'),
                        data: $data,
                        type: 'POST',
                        success: function (r) {
                            jQuery('#outinfo').html(r);
                        }
                    })
                }

                var $button = jQuery("<input type='button' value='Детальная информация' id='showform'>");
                jQuery("#pay_system_tariff").append($button);
                $button.click(function () {
                    jQuery('#ubrrwrap').show();
                    $('html, body').animate({scrollTop: 0}, 'fast');
                });
                jQuery("#cross").click(function () {
                    jQuery('#ubrrwrap').hide();
                });
                jQuery('#statusbutton').click(function () {
                    $('#task_ubrir').val(1);
                    formsubmit();
                });
                jQuery('#detailstatusbutton').click(function () {
                    $('#task_ubrir').val(2);
                    formsubmit();
                });
                jQuery('#reversbutton').click(function () {
                    $('#task_ubrir').val(3);
                    formsubmit();
                });
                jQuery('#recresultbutton').click(function () {
                    $('#task_ubrir').val(4);
                    formsubmit();
                });
                jQuery('#journalbutton').click(function () {
                    $('#task_ubrir').val(5);
                    formsubmit();
                });

            })
            ;
        </script>
    </div>
</div>