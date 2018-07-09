<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once 'class.JavaScriptPacker.php';
require_once 'class.upload.php';

    function submit_review_images() {
        
        $result = array();
        $tmp_dir = $_SERVER["DOCUMENT_ROOT"]. "/bitrix/tmp/";
        $result["result"] = array();
            $source = array_pop($_FILES);
            $handle = new upload($source, 'ru_RU');
            if ($handle->uploaded) {
                $r = array("name" => $handle->file_src_name,"size" => $handle->file_src_size);
                $handle->file_max_size = 1024*1024*3;
                $handle->image_resize = true;
                $handle->image_ratio = true;
                $handle->image_ratio_fill = true;
                $handle->image_convert = "jpg";
                $handle->image_y = 800;
                $handle->image_ratio_no_zoom_in = true;
                $handle->image_ratio_no_zoom_out = false;
                $handle->allowed = array("image/*");
                $handle->process($tmp_dir);
                if ($handle->processed) {
                    $r["name"] = $handle->file_dst_name;
                    $handle->file_name_body_pre = "thumb_";
                    $handle->image_resize = true;
                    $handle->image_convert = "jpg";
                    $handle->image_ratio = true;
                    $handle->image_ratio_x = true;
                    $handle->image_y = 150;
                    $handle->process($tmp_dir);
                    if ($handle->processed) {
                        $r['thumb'] = $handle->file_dst_name;
                        $r['status'] = "ok";
                    } else {
                        $r['error'] =  $handle->error;
                    }
                    $handle->clean();
                } else {
                    $r['error'] =  $handle->error;
                }                  
                $result["result"][] = $r;
            }
        return $result;
    }


if ($_POST['action']=="upload_file") {
    $result = submit_review_images();
    echo json_encode($result);
    die;
}

    function put_captcha($key,$name) {
        $name = $name;
        $orig_value = rand().time();
        $value = rand().uniqid('', true);
        $_SESSION["form_".$key] = "$name/$value";
        $packer = new JavaScriptPacker("$('form[name=SIMPLE_FORM_$key] input[name=txt_$name]').val('$value');", 'Normal', true, false);
        $jscode = $packer->pack();
        $captcha = <<<EOF
        <input type="hidden" name="txt_$name" value='$orig_value'/>
        <script type="text/javascript">{$jscode}</script>
EOF;
        return $captcha;
    }
        
if ($arResult["isFormErrors"] != "Y" && $arResult["isFormNote"] == "Y") { 
    if (CModule::IncludeModule('form'))
    {
        CModule::includeModule('ws.projectsettings'); 
        $RESULT_ID = (int)$_REQUEST["RESULT_ID"];
        $arAnswer = CFormResult::GetDataByID(
                $RESULT_ID, 
                array(),
                $arResult2, 
                $arAnswer2);    
        $title = $arResult2['NAME'];
        $name = $arAnswer['NAME'][0]['USER_TEXT'];
        //$phone = $arAnswer['PHONE'][0]['USER_TEXT'];
    } else
    {
       ShowError('Нет такого модуля');
    }
} else {
    $name = md5($arResult['arForm']['NAME']);
    $id = $arResult['arForm']['ID'];   
    $captcha = put_captcha($id,$name);
    $arResult['CAPTCHA_HTML_CODE'] = $captcha;
    foreach($arResult["QUESTIONS"] as $k => &$item) {
        $fclass = "";
        if (strpos($k,"NAME")!==FALSE) {
            $fclass="name";
        }
        if (strpos($k,"EMAIL")!==FALSE) {
            $item["HTML_CODE"] = str_replace("<input ","<input class='phone' ",$item["HTML_CODE"]);
            $fclass="email";
        }
        $item["HTML_CODE"] = str_replace("<input ","<input maxlength='200' placeholder='{$item['CAPTION']}' ",$item["HTML_CODE"]);
        $item["HTML_CODE"] = str_replace("<textarea ","<textarea maxlength='2000' placeholder='{$item['CAPTION']}' ",$item["HTML_CODE"]);
        $item["HTML_CODE"] = "<div class='f f{$fclass}'>{$item["HTML_CODE"]}</div>";
    }
}
?>