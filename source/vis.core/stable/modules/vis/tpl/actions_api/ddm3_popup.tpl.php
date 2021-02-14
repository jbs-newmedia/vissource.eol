<div class="ddm3_popup">
<?php

$function=h()->_catch('function', '', 'gp');

$dir=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/tpl/actions_api';
$file=$dir.'/'.$function.'.tpl.php';

if (file_exists($file) && dirname(realpath($file))==$dir) {
	$script=$file;
} else {
	$script='';
}

if ($script!='') {
	include $file;
}

?>
</div>