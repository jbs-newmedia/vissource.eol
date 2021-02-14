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

$ddm_group='vis2_lab_ddm4_ajaxfunc';

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

$navigation_links=array();
$navigation_links[1]=array(
	'navigation_id'=>1,
	'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
	'text'=>'Default',
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
		'table'=>'vis2_lab_ddm4_ajaxfunc',
		'alias'=>'tbl1',
		'index'=>'ajaxfunc_id',
		'index_type'=>'integer',
		'order'=>array(
			'ajaxfunc_id'=>'desc',
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

#osW_DDM4::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'navigation_id', 'operator'=>'=', 'value'=>$ddm_navigation_id)))), 'database');


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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'ajaxfunc_send', array(
	'module'=>'yesno',
	'title'=>'E-Mail senden',
	'name'=>'ajaxfunc_send',
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'ajaxfunc_send_name', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'ajaxfunc_send_email', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'ajaxfunc_form', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'ajaxfunc_form_durchmesser', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'ajaxfunc_form_laenge', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'ajaxfunc_form_breite', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'ajaxfunc_form_seite', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'ajaxfunc', array(
	'module'=>'ajaxfunc',
	'title'=>'ajaxfunc',
	'options'=>array(
		'data'=>$ajax,
	)
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