<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

if ($frame_class===true) {
	if (file_exists(vOut('settings_abspath').'frame/classes/'.$class_name.'.php')) {
		include vOut('settings_abspath').'frame/classes/'.$class_name.'.php';
	} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('project_default_module').'/classes/'.$class_name.'.php')) {
		include vOut('settings_abspath').'modules/'.vOut('project_default_module').'/classes/'.$class_name.'.php';
	} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/classes/'.$class_name.'.php')) {
		include vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/classes/'.$class_name.'.php';
	} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/classes/'.$class_name.'.php')) {
		include vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/classes/'.$class_name.'.php';
	}
} else {
	if (file_exists(vOut('settings_abspath').'frame/classes/external/'.$class_name.'/'.$class_name.'.php')) {
		include vOut('settings_abspath').'frame/classes/external/'.$class_name.'/'.$class_name.'.php';
	} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('project_default_module').'/classes/external/'.$class_name.'/'.$class_name.'.php')) {
		include vOut('settings_abspath').'modules/'.vOut('project_default_module').'/classes/external/'.$class_name.'/'.$class_name.'.php';
	} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/classes/external/'.$class_name.'/'.$class_name.'.php')) {
		include vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/classes/external/'.$class_name.'/'.$class_name.'.php';
	} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/classes/external/'.$class_name.'/'.$class_name.'.php')) {
		include vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/classes/external/'.$class_name.'/'.$class_name.'.php';
	}
}

?>