<?php

    $phone_key = $arParams['phone_key'];
    if (!$phone_key)
        $phone_key = 'main';
    CModule::includeModule('ws.projectsettings'); 
	$phone = WS_PSettings::getFieldValue($phone_key.'_phone', false);


	if (WS_PSettings::getFieldValue('enable_phone_subst', false)) {


	
if (!function_exists('get_sources')) {
    function get_sources() { 
        global $seo_sources_url;
		$seo_sources_url="http://web-axioma.ru/seo_sources.json";
        $json = file_get_contents($seo_sources_url);
        if ($json === FALSE)
            return null;
        return json_decode($json);
    }
}



if (!function_exists('get_phones')) {
    function get_phones($iblock_id, $phone_key) {
        $result = array();
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_phone_key", "PROPERTY_source_key", "PROPERTY_value");
        $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => $iblock_id, "IBLOCK_TYPE" => "phone_subst", "IBLOCK_CODE" => "phone_subst", "PROPERTY_phone_key" => $phone_key), false, Array(), $arSelect);
        while ($ar_res = $res->Fetch()) {
            $result[$ar_res["PROPERTY_SOURCE_KEY_VALUE"]] = $ar_res["PROPERTY_VALUE_VALUE"];
        }
        return $result;
    }
}
        CModule::IncludeModule("iblock");
        $res = CIBlock::GetList(Array(), Array(
                    'TYPE' => 'phone_subst',
                    'ACTIVE' => 'Y'
                        ), true);
        $ar_res = $res->Fetch();
        $iblock_id = $ar_res["ID"];

        $sources = get_sources();
###	$phones = get_phones();
        $phones = get_phones($iblock_id, $phone_key);
        $cookie_name = 'ps_'.$phone_key;
        $res = CIBlockElement::GetList(Array("TIMESTAMP_X" => "DESC"), Array("IBLOCK_ID" => $iblock_id, "IBLOCK_TYPE" => "phone_subst", "IBLOCK_CODE" => "phone_subst", "PROPERTY_phone_key" => $phone_key));
        $ar_res = $res->Fetch();
        $ts = $ar_res["TIMESTAMP_X"];
        $change_time = 0;
        if ($ts) {
            $change_time = MakeTimeStamp($ts, "DD.MM.YYYY HH:MI:SS");
        }
        $cookie_time = (int)$_COOKIE['phone_subst'];
        if (!$_COOKIE[$cookie_name] || ($cookie_time>0 && $change_time>0 && $cookie_time<$change_time)) {
            $ref = $_SERVER["HTTP_REFERER"];
            //if ($_SERVER["SERVER_NAME"]==parse_url($ref,PHP_URL_HOST))
            //        return $phone;
            $phones = get_phones($iblock_id,$phone_key);
            if (!$phones || count($phones)==0)
                return $phone;
				
            if ($phones['ext1'])
                $sources[] = (object)array("name" => "ext1","url" => $phones['ext1_url']);
            if ($phones['ext2'])
                $sources[] = (object)array("name" => "ext2","url" => $phones['ext2_url']);
            if ($phones['ext3'])
                $sources[] = (object)array("name" => "ext3","url" => $phones['ext3_url']);
            $is_subst = false;
            foreach($sources as $source) {
			
                if ($source->type=="title")
                    continue;
                if (!trim($phones[$source->name]) )
                        continue;
                if (!is_array($source->url)) {
                    $source->url = array($source->url);
                }
                foreach($source->url as $url) {
                    if ($source->url_type=='regex' && preg_match("/{$url}/Usi",$ref)) {
					
                        $phone = $phones[$source->name];
                        $is_subst = true;
                        break;
                    } else if (strpos($ref,$url)!==FALSE) {
                        $phone = $phones[$source->name];
                        $is_subst = true;
                        break;
                    }
                }
            }
            if ($is_subst) {
                setcookie('phone_subst',$change_time, time()+3600*24*30,'/');
                setcookie($cookie_name,$phone, time()+3600*24*30,'/');
            }
        } else {
            $phone = $_COOKIE[$cookie_name];
        }
        
        
    };
    echo $phone;
?>