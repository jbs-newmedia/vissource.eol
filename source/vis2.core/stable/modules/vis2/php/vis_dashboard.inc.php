<?php

$dashboard_files=array();
$dashboard_tpls=array();
$dashboard_files_g=glob(vOut('settings_abspath').'modules/vis2/php/dashboard/*.php');
foreach ($dashboard_files_g as $file) {
	$dashboard_files[substr(basename($file), 0, 3)]=$file;
	$dashboard_tpls[substr(basename($file), 0, 3)]=str_replace(['/php/', 'inc.php'], ['/tpl/', 'tpl.php'], $file);
}
$dashboard_files_l=glob(vOut('settings_abspath').'modules/vis2/vistools/'.osW_VIS2::getInstance()->getTool().'/php/dashboard/*.php');
foreach ($dashboard_files_l as $file) {
	$dashboard_files[substr(basename($file), 0, 3)]=$file;
	$dashboard_tpls[substr(basename($file), 0, 3)]=str_replace(['/php/', 'inc.php'], ['/tpl/', 'tpl.php'], $file);
}
ksort($dashboard_files);

$dashboard_run='init';
$navigation_links=array();
foreach ($dashboard_files as $file) {
	include($file);
}
$dashboard_run='run';

foreach ($dashboard_files as $file) {
	include($file);
}
osW_Template::getInstance()->set('dashboard_tpls', $dashboard_tpls);

?>