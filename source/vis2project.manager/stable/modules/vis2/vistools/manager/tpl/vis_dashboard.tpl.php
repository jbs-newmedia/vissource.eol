<?php

if (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/tpl/dashboard.tpl.php')) {
	include(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/tpl/dashboard.tpl.php');
} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/tpl/vis_dashboard.tpl.php')) {
	include(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/tpl/vis_dashboard.tpl.php');
}

?>