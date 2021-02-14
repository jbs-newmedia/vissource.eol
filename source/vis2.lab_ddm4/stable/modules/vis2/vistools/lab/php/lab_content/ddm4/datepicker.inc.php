<?php

$data=array();
$data['navigation_id']='c'.$header_counter.''.$content_counter;
$data['custom']=true;
$data['tool_id']=osW_VIS2::getInstance()->getToolId();
$data['navigation_parent_id']='c'.$header_counter;
$data['navigation_title']='DatePicker';
$data['navigation_sortorder']=$content_counter;
$data['navigation_ispublic']=1;
$data['page_name']='lab_ddm4_datepicker';
$data['page_description']='DatePicker';
$data['page_ispublic']=1;
$data['navigation_path']='';
$data['navigation_path_array']=array();
$data['permission']=array('link', 'view');
osW_VIS2_Navigation::getInstance()->addNavigationElement($data);

?>