<?php

if (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/dashboard.inc.php')) {
	include(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/dashboard.inc.php');
} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/vis_dashboard.inc.php')) {
	include(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/vis_dashboard.inc.php');
}

?>