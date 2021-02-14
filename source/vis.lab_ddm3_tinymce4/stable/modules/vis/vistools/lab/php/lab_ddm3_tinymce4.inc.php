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
	'text'=>'Full',
);
$navigation_links[]=array(
	'navigation_id'=>3,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Classic',
);
$navigation_links[]=array(
	'navigation_id'=>4,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'HTML5',
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
		'table'=>'vis_lab_ddm3_tinymce4',
		'alias'=>'tbl1',
		'index'=>'tinymce4_id',
		'index_type'=>'integer',
		'order'=>array(
			'tinymce4_id'=>'desc',
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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'tinymce4_id', array(
	'module'=>'index',
	'title'=>'ID',
	'name'=>'tinymce4_id',
));

switch ($ddm_navigation_id) {
	case 1:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'tinymce4_example', array(
			'module'=>'tinymce4',
			'title'=>'TinyMCE4',
			'name'=>'tinymce4_example',
			'options'=>array(
				'notice'=>'Default, ohne irgendwelche Einstellungen.',
			),
		));
		break;
	case 2:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'tinymce4_example', array(
			'module'=>'tinymce4',
			'title'=>'TinyMCE4',
			'name'=>'tinymce4_example',
			'options'=>array(
				'notice'=>'Full featured example',
				'tinymce'=>array(
					'skin'=>'osw',
					'plugins'=>'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern',
					'toolbar1'=>'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
					'toolbar2'=>'print preview media | forecolor backcolor emoticons',
					'image_advtab'=>true,
					'templates'=>array(
						array(
							'title'=>'Test template 1',
							'content'=>'Test 1',
						),
						array(
							'title'=>'Test template 2',
							'content'=>'Test 2',
						),
					),
				),
			),
		));
		break;
	case 3:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'tinymce4_example', array(
			'module'=>'tinymce4',
			'title'=>'TinyMCE4',
			'name'=>'tinymce4_example',
			'options'=>array(
				'notice'=>'Classic featured example',
				'tinymce'=>array(
					'plugins'=>'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern',
					'toolbar1'=>'newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect',
					'toolbar2'=>'cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor',
					'toolbar3'=>'table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft',
					'menubar'=>false,
					'toolbar_items_size'=>'small',
					'style_formats'=>array(
						array(
							'title'=>'Bold text',
							'inline'=>'b',
						),
						array(
							'title'=>'Red text',
							'inline'=>'span',
							'styles'=>array(
								'color'=>'#ff0000',
							),
						),
						array(
							'title'=>'Red header',
							'block'=>'h1',
							'styles'=>array(
								'color'=>'#ff0000',
							),
						),
						array(
							'title'=>'Example 1',
							'inline'=>'span',
							'classes'=>'example1',
						),
						array(
							'title'=>'Example 2',
							'inline'=>'span',
							'classes'=>'example2',
						),
						array(
							'title'=>'Table styles',
						),
						array(
							'title'=>'Table row 1',
							'selector'=>'tr',
							'classes'=>'tablerow1',
						),
					),
					'image_advtab'=>true,
					'templates'=>array(
						array(
							'title'=>'Test template 1',
							'content'=>'Test 1',
						),
						array(
							'title'=>'Test template 2',
							'content'=>'Test 2',
						),
					),
				),
			),
		));
		break;
	case 4:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'tinymce4_example', array(
			'module'=>'tinymce4',
			'title'=>'TinyMCE4',
			'name'=>'tinymce4_example',
			'options'=>array(
				'notice'=>'HTML5 formats',
				'tinymce'=>array(
					'style_formats'=>array(
						array(
							'title'=>'Headers',
							'items'=>array(
								array(
									'title'=>'h1',
									'block'=>'h1',
								),
								array(
									'title'=>'h2',
									'block'=>'h2',
								),
								array(
									'title'=>'h3',
									'block'=>'h3',
								),
								array(
									'title'=>'h4',
									'block'=>'h4',
								),
								array(
									'title'=>'h5',
									'block'=>'h5',
								),
								array(
									'title'=>'h6',
									'block'=>'h6',
								),
							),
						),
						array(
							'title'=>'Blocks',
							'items'=>array(
								array(
									'title'=>'p',
									'block'=>'p',
								),
								array(
									'title'=>'div',
									'block'=>'div',
								),
								array(
									'title'=>'pre',
									'block'=>'pre',
								),
							),
						),
						array(
							'title'=>'Containers',
							'items'=>array(
								array(
									'title'=>'section',
									'block'=>'section',
									'wrapper'=>true,
									'merge_siblings'=>false,
								),
								array(
									'title'=>'article',
									'block'=>'article',
									'wrapper'=>true,
									'merge_siblings'=>false,
								),
								array(
									'title'=>'blockquote',
									'block'=>'blockquote',
									'wrapper'=>true,
								),
								array(
									'title'=>'hgroup',
									'block'=>'hgroup',
									'wrapper'=>true,
								),
								array(
									'title'=>'aside',
									'block'=>'aside',
									'wrapper'=>true,
								),
								array(
									'title'=>'figure',
									'block'=>'figure',
									'wrapper'=>true,
								),
							),
						),
					),
					'image_advtab'=>true,
					'visualblocks_default_state'=>true,
					'end_container_on_empty_block'=>true,
				),
			),
		));
		break;
	default:
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'tinymce4_example', array(
			'module'=>'tinymce4',
			'title'=>'TinyMCE4',
			'name'=>'tinymce4_example',
			'options'=>array(
				'notice'=>'Default, ohne irgendwelche Einstellungen.',
			),
		));
		break;
}

osW_DDM3::getInstance()->addDataElement($ddm_group, 'tinymce4_check', array(
	'module'=>'text',
	'title'=>'Required',
	'name'=>'tinymce4_check',
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