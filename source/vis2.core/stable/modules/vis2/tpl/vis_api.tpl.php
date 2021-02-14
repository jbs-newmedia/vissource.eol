<?php

$dir=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/tpl/actions_api';
$file=$dir.'/'.osW_Settings::getInstance()->getAction().'.tpl.php';

osW_Settings::getInstance()->vis_api_die=true;

if (file_exists($file) && dirname(realpath($file))==$dir) {
	$script=osW_Settings::getInstance()->getAction();
} else {
	$script='';
}

if ($script!='') {
	include $file;
}

?>