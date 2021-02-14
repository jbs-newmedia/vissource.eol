<?php

if($this->getListElementOption($ddm_group, $element, 'display_create_time')==true) {
	$_search[$options['options']['prefix'].'create_time']=$options['options']['prefix'].'create_time';
}

if($this->getListElementOption($ddm_group, $element, 'display_create_user')==true) {
	$_search[$options['options']['prefix'].'create_user_id']=$options['options']['prefix'].'create_user_id';
}

if($this->getListElementOption($ddm_group, $element, 'display_update_time')==true) {
	$_search[$options['options']['prefix'].'update_time']=$options['options']['prefix'].'update_time';
}

if($this->getListElementOption($ddm_group, $element, 'display_update_user')==true) {
	$_search[$options['options']['prefix'].'update_user_id']=$options['options']['prefix'].'update_user_id';
}

?>