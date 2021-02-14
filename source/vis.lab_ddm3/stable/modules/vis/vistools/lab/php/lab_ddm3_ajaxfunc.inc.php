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


$ajax=array();

$ajax['init']=array('index', 'header', 'ajaxfunc_send', 'ajaxfunc', 'submit');

$ajax['logic']['group_send']['view']=array('ajaxfunc_send_name', 'ajaxfunc_send_email');
$ajax['logic']['group_send']['rule']=array('ajaxfunc_send'=>array(1));

$ajax['logic']['group_form']['view']=array('ajaxfunc_form');
$ajax['logic']['group_form']['rule']=array('ajaxfunc_send'=>array(0, 1));

$ajax['logic']['group_form_kreis']['view']=array('ajaxfunc_form_durchmesser');
$ajax['logic']['group_form_kreis']['rule']=array('ajaxfunc_form'=>array('kreis'));

$ajax['logic']['group_form_rechteck']['view']=array('ajaxfunc_form_laenge', 'ajaxfunc_form_breite');
$ajax['logic']['group_form_rechteck']['rule']=array('ajaxfunc_form'=>array('rechteck'));

$ajax['logic']['group_form_quadrat']['view']=array('ajaxfunc_form_seite');
$ajax['logic']['group_form_quadrat']['rule']=array('ajaxfunc_form'=>array('quadrat'));


osW_DDM3::getInstance()->addGroup($ddm_group, array(
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
		'table'=>'vis_lab_ddm3_ajaxfunc',
		'alias'=>'tbl1',
		'index'=>'ajaxfunc_id',
		'index_type'=>'integer',
		'order'=>array(
			'ajaxfunc_id'=>'desc',
		),
	),
));


// DataView

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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ajaxfunc_id', array(
	'module'=>'index',
	'title'=>'ID',
	'name'=>'ajaxfunc_id',
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ajaxfunc_send', array(
	'module'=>'yesno',
	'title'=>'E-Mail senden',
	'name'=>'ajaxfunc_send',
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ajaxfunc_send_name', array(
	'module'=>'text',
	'title'=>'Name',
	'name'=>'ajaxfunc_send_name',
	'options'=>array(
		'order'=>true,
		'required'=>true,
	),
	'validation'=>array(
		'length_min'=>5,
		'length_max'=>128,
	),
	'_list'=>array(
#		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ajaxfunc_send_email', array(
	'module'=>'text',
	'title'=>'E-Mail',
	'name'=>'ajaxfunc_send_email',
	'options'=>array(
		'order'=>true,
		'required'=>true,
	),
	'validation'=>array(
		'length_min'=>5,
		'length_max'=>128,
		'filter'=>array(
			'email'=>array(),
		),
	),
	'_list'=>array(
#		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ajaxfunc_form', array(
	'module'=>'select',
	'title'=>'Form',
	'name'=>'ajaxfunc_form',
	'options'=>array(
		'order'=>true,
		'required'=>true,
		'data'=>array(
			''=>'Bitte wählen',
			'kreis'=>'Kreis',
			'rechteck'=>'Rechteck',
			'quadrat'=>'Quadrat',
		),
	),
	'validation'=>array(
		'length_min'=>1,
		'length_max'=>16,
	),
	'_list'=>array(
#		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ajaxfunc_form_durchmesser', array(
	'module'=>'text',
	'title'=>'Durchmesser',
	'name'=>'ajaxfunc_form_durchmesser',
	'options'=>array(
		'required'=>true,
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>1,
		'length_max'=>3,
		'value_min'=>1,
		'value_max'=>250,
	),
	'_list'=>array(
#		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ajaxfunc_form_laenge', array(
	'module'=>'text',
	'title'=>'Länge',
	'name'=>'ajaxfunc_form_laenge',
	'options'=>array(
		'required'=>true,
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>1,
		'length_max'=>3,
		'value_min'=>1,
		'value_max'=>250,
	),
	'_list'=>array(
#		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ajaxfunc_form_breite', array(
	'module'=>'text',
	'title'=>'Breite',
	'name'=>'ajaxfunc_form_breite',
	'options'=>array(
		'required'=>true,
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>1,
		'length_max'=>3,
		'value_min'=>1,
		'value_max'=>250,
	),
	'_list'=>array(
#		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ajaxfunc_form_seite', array(
	'module'=>'text',
	'title'=>'Seite',
	'name'=>'ajaxfunc_form_seite',
	'options'=>array(
		'required'=>true,
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>1,
		'length_max'=>3,
		'value_min'=>1,
		'value_max'=>250,
	),
	'_list'=>array(
#		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ajaxfunc', array(
	'module'=>'ajaxfunc',
	'title'=>'ajaxfunc',
	'options'=>array(
		'data'=>$ajax,
	)
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