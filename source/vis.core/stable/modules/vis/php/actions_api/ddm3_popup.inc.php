<?php

osW_Settings::getInstance()->vis_navigation_enabled=false;
osW_Settings::getInstance()->vis_api_die=false;

$function=h()->_catch('function', '', 'gp');

$dir=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/actions_api';
$file=$dir.'/'.$function.'.inc.php';

if (file_exists($file) && dirname(realpath($file))==$dir) {
	$script=$file;
} else {
	$script='';
}

if ($script!='') {
	include $file;
} else {
	h()->_die('Error');
}

?>