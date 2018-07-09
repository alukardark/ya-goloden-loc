<?php

$uri = mb_strtolower(urldecode($_GET['url']),'utf-8');
header("HTTP/1.1 301 Moved Permanently");
header("Location: /$uri");
die;