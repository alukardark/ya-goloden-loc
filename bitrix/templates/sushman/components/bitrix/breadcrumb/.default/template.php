<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//delayed function must return a string
if(empty($arResult))
	return "";
	
$strReturn = '';

$num_items = count($arResult);
for($index = 1, $itemSize = $num_items; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	
	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
		$strReturn .= ' &raquo; <a href="'.$arResult[$index]["LINK"].'" title="'.$title.'">'.$title.'</a>';
	else
		$strReturn .= ' &raquo; <span>'.$title.'</span>';
}

return $strReturn ? '<div id="breadcrumbs" style="padding-left:46px;margin:0 auto;width:975px;"><a href="'.$arResult[0]["LINK"].'">Главная</a>'.$strReturn.'</div>' : '<div id="breadcrumb"></div>';
?>