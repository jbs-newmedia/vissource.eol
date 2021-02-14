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
	'navigation_id'=>0,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'#1',
);

$navigation_links[]=array(
	'navigation_id'=>1,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'#2',
);

$navigation_links[]=array(
	'navigation_id'=>2,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'#3',
);

$navigation_links[]=array(
	'navigation_id'=>3,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'#4',
);

$navigation_links[]=array(
	'navigation_id'=>4,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'#5',
);

$navigation_links[]=array(
	'navigation_id'=>5,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'#6',
);

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
		'table'=>'vis_lab_ddm3_fileimage',
		'alias'=>'tbl1',
		'index'=>'fileimage_id',
		'index_type'=>'integer',
		'order'=>array(
			'fileimage_id'=>'desc',
		),
	),
));

osW_DDM3::getInstance()->readParameters($ddm_group);

$ddm_navigation_id=intval(h()->_catch('ddm_navigation_id', osW_DDM3::getInstance()->getParameter($ddm_group, 'ddm_navigation_id'), 'pg'));

if (!in_array($ddm_navigation_id, array(0,1,2,3,4,5))) {
	$ddm_navigation_id=0;
}

osW_DDM3::getInstance()->addParameter($ddm_group, 'ddm_navigation_id', $ddm_navigation_id);

osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'navigation_id', 'operator'=>'=', 'value'=>$ddm_navigation_id)))), 'database');


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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'fileimage_id', array(
	'module'=>'index',
	'title'=>'ID',
	'name'=>'fileimage_id',
));

switch ($ddm_navigation_id) {
	case 1:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'fileimage_example', array(
			'module'=>'fileimage',
			'title'=>'Bild',
			'name'=>'fileimage_example',
			'options'=>array(
				'notice'=>'Nur Bild vom Typ image/jpeg.',
			),
			'validation'=>array(
				'module'=>'fileimage',
				'types'=>array('image/jpeg'),
			),
		));
		break;
	case 2:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'fileimage_example', array(
			'module'=>'fileimage',
			'title'=>'Bild',
			'name'=>'fileimage_example',
			'options'=>array(
				'notice'=>'Nur Bild mit der Endung .jpg',
			),
			'validation'=>array(
				'module'=>'fileimage',
				'extensions'=>array('jpg'),
			),
		));
		break;
	case 3:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'fileimage_example', array(
			'module'=>'fileimage',
			'title'=>'Bild',
			'name'=>'fileimage_example',
			'options'=>array(
				'notice'=>'Nur Bild mit Größe > 2KB und < 2MB',
			),
			'validation'=>array(
				'module'=>'fileimage',
				'size_min'=>1024*2,
				'size_max'=>1024*1024*2,
			),
		));
		break;
	case 4:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'fileimage_example', array(
			'module'=>'fileimage',
			'title'=>'Bild',
			'name'=>'fileimage_example',
			'options'=>array(
				'notice'=>'Nur Bild mit Breite 800px-1280px x Höhe 640px-800px',
			),
			'validation'=>array(
				'module'=>'fileimage',
				'width_min'=>800,
				'width_max'=>1280,
				'height_min'=>640,
				'height_max'=>800,
			),
		));
		break;
	case 5:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'fileimage_example', array(
			'module'=>'fileimage',
			'title'=>'Bild',
			'name'=>'fileimage_example',
			'options'=>array(
				'notice'=>'Nur Bild vom Typ image/jpeg, mit der Endung .jpg, mit Größe > 2KB und < 2MB und mit Breite 800px-1280px x Höhe 640px-800px',
			),
			'validation'=>array(
				'module'=>'fileimage',
				'types'=>array('image/jpeg'),
				'extensions'=>array('jpg'),
				'size_min'=>1024*2,
				'size_max'=>1024*1024*2,
				'width_min'=>800,
				'width_max'=>1280,
				'height_min'=>640,
				'height_max'=>800,
			),
		));
		break;
	default:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'fileimage_example', array(
			'module'=>'fileimage',
			'title'=>'Bild',
			'name'=>'fileimage_example',
			'options'=>array(
				'notice'=>'Default, ohne irgendwelche Einstellungen.',
			),
		));
}

osW_DDM3::getInstance()->addDataElement($ddm_group, 'fileimage_check', array(
	'module'=>'text',
	'title'=>'Required',
	'name'=>'fileimage_check',
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