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

$ddm_group='ddm_group';

$var='vis_'.osW_VIS::getInstance()->getTool().'_'.osW_VIS_Navigation::getInstance()->getPage().'_tool_id';

$navigation_links=array();
$navigation_links[]=array(
	'navigation_id'=>1,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Default',
);
$navigation_links[]=array(
	'navigation_id'=>2,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'String',
);
$navigation_links[]=array(
	'navigation_id'=>3,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Integer',
);
$navigation_links[]=array(
	'navigation_id'=>4,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Float',
);
$navigation_links[]=array(
	'navigation_id'=>11,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'E-Mail',
);
$navigation_links[]=array(
	'navigation_id'=>12,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Url',
);
$navigation_links[]=array(
	'navigation_id'=>21,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'RegEx',
);
$navigation_links[]=array(
	'navigation_id'=>99,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'ReadOnly',
);

$ddm_navigation_ids=array();
foreach ($navigation_links as $navigation_link) {
	$ddm_navigation_ids[]=$navigation_link['navigation_id'];
}

osW_DDM3::getInstance()->addGroup($ddm_group, array(
	'messages'=>array(

	),
	'general'=>array(
		'engine'=>'datatable',
		'cache'=>h()->_catch('ddm_cache', '', 'pg'),
		'elements_per_page'=>30,
	),
	'direct'=>array(
		'module'=>vOut('frame_current_module'),
		'parameters'=>array(
			'vistool'=>osW_VIS::getInstance()->getTool(),
			'vispage'=>osW_VIS_Navigation::getInstance()->getPage(),
		),
	),
	'database'=>array(
		'table'=>'vis_lab_ddm3_text',
		'alias'=>'tbl1',
		'index'=>'text_id',
		'index_type'=>'integer',
		'order'=>array(
			'text_id'=>'desc',
		),
	),
));

osW_DDM3::getInstance()->readParameters($ddm_group);

$ddm_navigation_id=intval(h()->_catch('ddm_navigation_id', osW_DDM3::getInstance()->getParameter($ddm_group, 'ddm_navigation_id'), 'pg'));

if (!in_array($ddm_navigation_id, $ddm_navigation_ids)) {
	$ddm_navigation_id=1;
}

osW_DDM3::getInstance()->addParameter($ddm_group, 'ddm_navigation_id', $ddm_navigation_id);

if ($ddm_navigation_id<99) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'navigation_id', 'operator'=>'=', 'value'=>$ddm_navigation_id)))), 'database');
}


// DataView

osW_DDM3::getInstance()->addViewElement($ddm_group, 'navigation', array(
	'module'=>'navigation',
	'options'=>array(
		'data'=>$navigation_links,
	),
));

osW_DDM3::getInstance()->addViewElement($ddm_group, 'add_and_search', array(
	'module'=>'add_and_search',
));

osW_DDM3::getInstance()->addViewElement($ddm_group, 'sortorder', array(
	'module'=>'sortorder',
));

osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_header', array(
	'module'=>'table_header',
));

osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_data', array(
	'module'=>'table_data',
));

osW_DDM3::getInstance()->addViewElement($ddm_group, 'pages', array(
	'module'=>'pages',
));


// DataList

osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation', array(
	'module'=>'navigation',
	'options'=>array(
		'data'=>$navigation_links,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'header', array(
	'module'=>'header',
	'_search'=>array(
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'search_title', 'messages')
	),
	'_add'=>array(
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'add_title', 'messages')
	),
	'_edit'=>array(
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'edit_title', 'messages')
	),
	'_delete'=>array(
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'delete_title', 'messages')
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'text_id', array(
	'module'=>'index',
	'title'=>'ID',
	'name'=>'text_id',
));

switch ($ddm_navigation_id) {
	case 1:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
		));
		break;
	case 2:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
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
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
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
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
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
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
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
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
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
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
				'notice'=>'Telefonnummer nach dem Muster +xx xxx - xxx-xxx',
			),
			'validation'=>array(
				'module'=>'string',
				'length_min'=>5,
				'length_max'=>128,
				'preg'=>'/(^(\+49) ([0-9]{3,5}) - ([0-9]{3,6})(-([0-9]{1,3}))?$)|(^$)/Uis',
			),
		));
		break;
	case 99:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
				'read_only'=>true,
				'default_value'=>date('d.m.Y H:i:s'),
				'notice'=>'Nur lesend zugreifen',
			),
		));
		break;
	default:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'text_example', array(
			'module'=>'text',
			'title'=>'Text',
			'name'=>'text_example',
			'options'=>array(
				'notice'=>'Default, ohne irgendwelche Einstellungen.',
			),
		));
		break;
}

osW_DDM3::getInstance()->addDataElement($ddm_group, 'text_check', array(
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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation_id', array(
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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'options', array(
	'module'=>'options',
	'title'=>'Optionen',
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'submit', array(
	'module'=>'submit',
));


// FormFinish

osW_DDM3::getInstance()->addFinishElement($ddm_group, 'store_form_data', array(
	'module'=>'store_form_data',
));

osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
	'module'=>'direct',
));

osW_DDM3::getInstance()->runDDMPHP($ddm_group);
osW_Template::getInstance()->set('ddm_group', $ddm_group);

?>