<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

# current version build
$cv=$this->_version;
$cb=$this->_build;

if (!defined('root_path')) {
	$dir=vOut('settings_abspath').'frame/classes_patch/osw_vis2_lab/';
	$files=glob($dir.'*.{php}', GLOB_BRACE);
	foreach ($files as $file) {
		include($file);
	}
}

?>