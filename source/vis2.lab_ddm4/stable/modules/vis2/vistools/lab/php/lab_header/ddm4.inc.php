<?php

$data=array();
$data['navigation_id']='c'.$header_counter;
$data['custom']=true;
$data['tool_id']=osW_VIS2::getInstance()->getToolId();
$data['navigation_parent_id']=0;
$data['navigation_title']='DDM4';
$data['navigation_sortorder']=$header_counter;
$data['navigation_ispublic']=1;
$data['page_id']='p1';
$data['page_name']='header_ddm4';
$data['page_description']='DDM4';
$data['page_ispublic']=0;
$data['navigation_path']='';
$data['navigation_path_array']=array();
$data['permission']=array('link');
osW_VIS2_Navigation::getInstance()->addNavigationElement($data);

$content_counter=0;
foreach (glob(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/lab_content/'.$header.'/*.inc.php') as $file_content) {
	$content_counter++;
	include($file_content);
}

?>