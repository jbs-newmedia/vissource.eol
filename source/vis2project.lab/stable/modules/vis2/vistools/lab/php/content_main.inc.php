<?php

$header_counter=100;
foreach (glob(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/lab_header/*.inc.php') as $file_header) {
	$header_counter++;
	$header=str_replace('.inc.php', '', basename($file_header));
	include($file_header);
}

?>