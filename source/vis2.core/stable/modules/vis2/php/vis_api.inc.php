<?php

osW_Settings::getInstance()->vis_api_die=true;

$dir=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/actions_api';
$file=$dir.'/'.osW_Settings::getInstance()->getAction().'.inc.php';

if (file_exists($file) && dirname(realpath($file))==$dir) {
	$script=osW_Settings::getInstance()->getAction();
} else {
	$script='';
}

if ($script!='') {
	include $file;
} else {
	echo 'VIS2API v0.2';
}

if (vOut('vis_api_die')===true) {
	h()->_die();
}

?>