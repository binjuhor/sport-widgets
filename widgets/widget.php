<?php
header('Access-Control-Allow-Origin: *');
if (isset($_GET['wid'])){
	$widgets = json_decode(file_get_contents('./api/widgets.json'), true);

	if (isWidgetAvailable($widgets)) {
		$widgetData = $widgets[$_GET['wid']];
        include('./widget/'.$widgetData['name'].'.php');
	} else {
        die("No widget found");
    }
}

function isWidgetAvailable( $widgets )
{
    $widget = isset($widgets[$_GET['wid']]) ? $widgets[$_GET['wid']] : null;
    return $widget AND !empty($widget['name']) AND $widget['name'] === $_GET['widget'];
}