<?php
    $tabs_titles = "";
    $tabs_contents = "";
    $is_first = true;
    foreach($tabs as $k => $tab) {
        ob_start(); 
        include_once("tab-".$tab['id'].".php");
        $out = trim(ob_get_contents()," \t\r\n");
        ob_end_clean();         
        if ($out) {
            $tabs_titles .= "<li class='".($is_first ? "active first" : "").($k==count($tabs)-1 ? " last" : "")."'><span>{$tab['title']}</span></li>";
            $tabs_contents .= "<div class='tab-cont".($is_first ? " active" : "")."'>{$out}</div>";
            $is_first = false;
        }
    }
    if ($tabs_titles && $tabs_contents) {
        $arVariables = getCatalogVariables();
        $pos = !$arVariables["ELEMENT_CODE"] ? "left:0;margin-left:-12px;" : "";
        echo <<<EOF
                        <div class="center-col"><div class="tabs-wrapper" style="{$pos}">
                            <ul class="tabs">{$tabs_titles}</ul>
                            <div class="clear"></div>
                            <div class="conts">{$tabs_contents}</div>
                            <div class="clear"></div>
                        </div></div>
EOF;
    }