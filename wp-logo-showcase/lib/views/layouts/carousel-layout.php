<?php
$desc = null;
$itemsA = array();
if($url){
	$itemsA['logo'] = "<a href='{$url}' target='_blank'>$img_src</a>";
	$itemsA['title'] = "<h3><a href='{$url}' target='_blank'>{$title}</a></h3>";
}else{
	$itemsA['logo'] = $img_src;
	$itemsA['title'] = "<h3>{$title}</h3>";
}
$desc .="<div class='logo-description'>";
$desc .= apply_filters('the_content', $description);
$desc .="</div>";
$itemsA['description'] = $desc;

$html = null;
$html .="<div class='rt-col-md-{$grid} rt-col-sm-6 rt-col-xs-12'>";
    $html .="<div class='single-logo rt-equal-height data-title='{$title}'>";
        $html .="<div class='single-logo-container'>";
        foreach($items as $item){
            $html .= !empty($itemsA[$item]) ? $itemsA[$item] : null;
        }
        $html .="</div>";
    $html .="</div>";
$html .="</div>";
echo $html;