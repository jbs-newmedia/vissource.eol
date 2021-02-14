<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

$ddm_group='vis2_lab_ddm4_yesno';

$var='vis2_'.osW_VIS2::getInstance()->getTool().'_'.osW_VIS2_Navigation::getInstance()->getPage().'_ddm4_yesno';

$navigation_links=array();
$navigation_links[1]=array(
	'navigation_id'=>1,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'Default',
);
$navigation_links[2]=array(
	'navigation_id'=>2,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'Text',
);
$navigation_links[3]=array(
	'navigation_id'=>3,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'Reihenfolge',
);
$navigation_links[4]=array(
	'navigation_id'=>4,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'Pflicht',
);
$navigation_links[99]=array(
	'navigation_id'=>99,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'ReadOnly',
);

osW_DDM4::getInstance()->addGroup($ddm_group, array(
	'messages'=>array(
	),
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
		'table'=>'vis2_lab_ddm4_yesno',
		'alias'=>'tbl1',
		'index'=>'yesno_id',
		'index_type'=>'integer',
		'order'=>array(
			'yesno_id'=>'desc',
		),
	),
));

osW_DDM4::getInstance()->readParameters($ddm_group);

$ddm_navigation_id=intval(h()->_catch('ddm_navigation_id', osW_DDM4::getInstance()->getParameter($ddm_group, 'ddm_navigation_id'), 'pg'));

if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=1;
}

osW_DDM4::getInstance()->addParameter($ddm_group, 'ddm_navigation_id', $ddm_navigation_id);
osW_DDM4::getInstance()->storeParameters($ddm_group);

if ($ddm_navigation_id<99) {
	osW_DDM4::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'navigation_id', 'operator'=>'=', 'value'=>$ddm_navigation_id)))), 'database');
}


// DataView

osW_DDM4::getInstance()->addPreViewElement($ddm_group, 'navigation', array(
	'module'=>'navigation',
	'options'=>array(
		'data'=>$navigation_links,
	),
));

osW_DDM4::getInstance()->addViewElement($ddm_group, 'datatables', array(
	'module'=>'datatables',
));

// DataList

switch ($ddm_navigation_id) {
	case 1:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'yesno_example', array(
			'module'=>'yesno',
			'title'=>'YesNo',
			'name'=>'yesno_example',
			'options'=>array(
				'notice'=>'Default, ohne irgendwelche Einstellungen',
			),
		));
		break;
	case 2:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'yesno_example', array(
			'module'=>'yesno',
			'title'=>'YesNo',
			'name'=>'yesno_example',
			'options'=>array(
				'text_yes'=>'aktiviert',
				'text_no'=>'deaktiviert',
				'notice'=>'Andere Texte',
			),
		));
		break;
	case 3:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'yesno_example', array(
			'module'=>'yesno',
			'title'=>'YesNo',
			'name'=>'yesno_example',
			'options'=>array(
				'displayorder'=>'ny',
				'notice'=>'No/Yes anstatt Yes/No',
			),
		));
		break;
	case 4:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'yesno_example', array(
			'module'=>'yesno',
			'title'=>'YesNo',
			'name'=>'yesno_example',
			'options'=>array(
				'required'=>true,
				'notice'=>'Default, ohne irgendwelche Einstellungen',
			),
		));
		break;
	default:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'yesno_example', array(
			'module'=>'yesno',
			'title'=>'YesNo',
			'name'=>'yesno_example',
			'options'=>array(
				'read_only'=>true,
				'default_value'=>true,
				'notice'=>'Nur lesend zugreifen',
			),
		));
		break;
}

osW_DDM4::getInstance()->addDataElement($ddm_group, 'yesno_check', array(
	'module'=>'text',
	'title'=>'Required',
	'name'=>'yesno_check',
	'options'=>array(
		'required'=>true,
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>1,
		'length_max'=>16,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'navigation_id', array(
	'module'=>'hidden',
	'name'=>'navigation_id',
	'options'=>array(
		'default_value'=>$ddm_navigation_id,
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>1,
		'length_max'=>11,
	),
	'_view'=>array(
		'enabled'=>false,
	),
	'_search'=>array(
		'enabled'=>false,
	),
	'_edit'=>array(
		'enabled'=>false,
	),
	'_delete'=>array(
		'enabled'=>false,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'options', array(
	'module'=>'options',
	'title'=>'Optionen',
));

// FormFinish

osW_DDM4::getInstance()->addFinishElement($ddm_group, 'ddm4_store_form_data', array(
	'module'=>'ddm4_store_form_data',
));

osW_DDM4::getInstance()->addAfterFinishElement($ddm_group, 'ddm4_direct', array(
	'module'=>'ddm4_direct',
));

osW_DDM4::getInstance()->runDDMPHP($ddm_group);
osW_Template::getInstance()->set('ddm_group', $ddm_group);

?>