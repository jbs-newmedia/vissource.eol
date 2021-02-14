<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

if (file_exists(vOut('settings_abspath').'frame/ddm3/'.$path.$file)) {
	$file_exists=true;
	include vOut('settings_abspath').'frame/ddm3/'.$path.$file;
} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('project_default_module').'/ddm3/'.$path.$file)) {
	$file_exists=true;
	include vOut('settings_abspath').'modules/'.vOut('project_default_module').'/ddm3/'.$path.$file;
} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/ddm3/'.$path.$file)) {
	$file_exists=true;
	include vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/ddm3/'.$path.$file;
} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/ddm3/'.$path.$file)) {
	$file_exists=true;
	include vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/ddm3/'.$path.$file;
} else {
	if ($ob_get_contents===true) {
		echo '';
	} else {
		return false;
	}
}

?>