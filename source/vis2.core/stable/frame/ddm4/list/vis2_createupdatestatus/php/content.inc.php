<?php

if($this->getListElementOption($ddm_group, $element, 'display_create_time')==true) {
	$_columns[$options['options']['prefix'].'create_time']=array(
		'name'=>$options['options']['prefix'].'create_time',
		'order'=>true,
		'search'=>false,
	);
}

if($this->getListElementOption($ddm_group, $element, 'display_create_user')==true) {
	$_columns[$options['options']['prefix'].'create_user_id']=array(
		'name'=>$options['options']['prefix'].'create_user_id',
		'order'=>true,
		'search'=>false,
	);
}

if($this->getListElementOption($ddm_group, $element, 'display_update_time')==true) {
	$_columns[$options['options']['prefix'].'update_time']=array(
		'name'=>$options['options']['prefix'].'update_time',
		'order'=>true,
		'search'=>false,
	);
}

if($this->getListElementOption($ddm_group, $element, 'display_update_user')==true) {
	$_columns[$options['options']['prefix'].'update_user_id']=array(
		'name'=>$options['options']['prefix'].'update_user_id',
		'order'=>true,
		'search'=>false,
	);
}

$this->setOrderElementName($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'create_time', $this->getEditElementOption($ddm_group, $element, 'text_create_time'));
$this->incCounter($ddm_group, 'list_view_elements');
$this->setOrderElementName($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'create_user_id', $this->getEditElementOption($ddm_group, $element, 'text_create_user'));
$this->incCounter($ddm_group, 'list_view_elements');

$this->setOrderElementName($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'update_time', $this->getEditElementOption($ddm_group, $element, 'text_update_time'));
$this->incCounter($ddm_group, 'list_view_elements');
$this->setOrderElementName($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'update_user_id', $this->getEditElementOption($ddm_group, $element, 'text_update_user'));
$this->incCounter($ddm_group, 'list_view_elements');

?>