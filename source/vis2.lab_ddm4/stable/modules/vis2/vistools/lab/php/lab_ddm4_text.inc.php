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

$ddm_group='vis2_lab_ddm4_text';

$var='vis2_'.osW_VIS2::getInstance()->getTool().'_'.osW_VIS2_Navigation::getInstance()->getPage().'_ddm4_text';

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
	'text'=>'String',
);
$navigation_links[3]=array(
	'navigation_id'=>3,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'Integer',
);
$navigation_links[4]=array(
	'navigation_id'=>4,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'Float',
);
$navigation_links[11]=array(
	'navigation_id'=>11,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'E-Mail',
);
$navigation_links[12]=array(
	'navigation_id'=>12,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'Url',
);
$navigation_links[21]=array(
	'navigation_id'=>21,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'RegEx',
);
$navigation_links[99]=array(
	'navigation_id'=>99,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'ReadOnly',
);

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
		'table'=>'vis2_lab_ddm4_text',
		'alias'=>'tbl1',
		'index'=>'text_id',
		'index_type'=>'integer',
		'order'=>array(
			'text_id'=>'desc',
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

osW_DDM4::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'navigation_id', 'operator'=>'=', 'value'=>$ddm_navigation_id)))), 'database');


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
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
				'search'=>true,
			),
		));
		break;
	case 2:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
				'search'=>true,
				'notice'=>'String zwischen 2 und 12 Zeichen',
			),
			'validation'=>array(
				'module'=>'string',
				'length_min'=>2,
				'length_max'=>12,
			),
		));
		break;
	case 3:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
				'search'=>true,
				'notice'=>'Integer zwischen 25 und 75',
			),
			'validation'=>array(
				'module'=>'integer',
				'value_min'=>25,
				'value_max'=>75,
			),
		));
		break;
	case 4:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
				'search'=>true,
				'notice'=>'Float zwischen 2.50 und 7.25',
			),
			'validation'=>array(
				'module'=>'float',
				'value_min'=>2.5,
				'value_max'=>7.25,
			),
		));
		break;
	case 11:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
				'search'=>true,
				'notice'=>'E-Mail zwischen 5 und 128 Zeichen',
			),
			'validation'=>array(
				'module'=>'string',
				'length_min'=>5,
				'length_max'=>128,
				'filter'=>array(
					'email'=>array(),
				),
			),
		));
		break;
	case 12:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
				'search'=>true,
				'notice'=>'URL zwischen 5 und 128 Zeichen',
			),
			'validation'=>array(
				'module'=>'string',
				'length_min'=>5,
				'length_max'=>128,
				'filter'=>array(
					'url'=>array(),
				),
			),
		));
		break;
	case 21:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
				'search'=>true,
				'notice'=>'Telefonnummer nach dem Muster +XX XXX - XXX-XXX',
			),
			'validation'=>array(
				'module'=>'string',
				'length_min'=>5,
				'length_max'=>128,
				'preg'=>'/(^(\+)([0-9]{2,2}) ([0-9]{3,5}) - ([0-9]{3,6})(-([0-9]{1,3}))?$)|(^$)/Uis',
			),
		));
		break;
	default:
		osW_DDM4::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
				'search'=>true,
				'read_only'=>true,
				'default_value'=>date('d.m.Y H:i:s'),
				'notice'=>'Nur lesend zugreifen',
			),
		));
		break;
}

osW_DDM4::getInstance()->addDataElement($ddm_group, 'text_check', array(
	'module'=>'text',
	'title'=>'Required',
	'name'=>'text_check',
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