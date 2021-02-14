<?php

$ddm_group='vis2_settings';

$settings_files=array();
$settings_tpls=array();
$settings_files_g=glob(vOut('settings_abspath').'modules/vis2/php/settings/*.php');
foreach ($settings_files_g as $file) {
	$settings_files[substr(basename($file), 0, 3)]=$file;
	$settings_tpls[substr(basename($file), 0, 3)]=str_replace(['/php/', 'inc.php'], ['/tpl/', 'tpl.php'], $file);
}
$settings_files_l=glob(vOut('settings_abspath').'modules/vis2/vistools/'.osW_VIS2::getInstance()->getTool().'/php/settings/*.php');
foreach ($settings_files_l as $file) {
	$settings_files[substr(basename($file), 0, 3)]=$file;
	$settings_tpls[substr(basename($file), 0, 3)]=str_replace(['/php/', 'inc.php'], ['/tpl/', 'tpl.php'], $file);
}
ksort($settings_files);

$settings_run='init';
$navigation_links=array();
foreach ($settings_files as $file) {
	include($file);
}
$settings_run='run';

osW_DDM4::getInstance()->addGroup($ddm_group, array(
	'general'=>array(
		'engine'=>'ddm4_datatables',
		'cache'=>h()->_catch('ddm_cache', '', 'pg'),
		'elements_per_page'=>50,
	),
	'direct'=>array(
		'module'=>vOut('frame_current_module'),
		'parameters'=>array(
			'vistool'=>osW_VIS2::getInstance()->getTool(),
			'vispage'=>osW_VIS2_Navigation::getInstance()->getPage(),
		),
	),
	'database'=>array(
		'alias'=>'tbl1',
		'index_type'=>'integer',
	),
));

osW_DDM4::getInstance()->readParameters($ddm_group);

$ddm_navigation_id=intval(h()->_catch('ddm_navigation_id', osW_DDM4::getInstance()->getParameter($ddm_group, 'ddm_navigation_id'), 'pg'));

if (!isset($settings_files[$ddm_navigation_id])) {
	reset($settings_files);
	$ddm_navigation_id=key($settings_files);
}

osW_DDM4::getInstance()->addParameter($ddm_group, 'ddm_navigation_id', $ddm_navigation_id);
osW_DDM4::getInstance()->storeParameters($ddm_group);

if (isset($settings_files[$ddm_navigation_id])) {
	include $settings_files[$ddm_navigation_id];
	osW_Template::getInstance()->set('settings_tpl', $settings_tpls[$ddm_navigation_id]);
} else {
	osW_Template::getInstance()->set('settings_tpl', '');
}

?>