<?php

$data=array();
$data['navigation_id']='c'.$header_counter.''.$content_counter;
$data['custom']=true;
$data['tool_id']=osW_VIS::getInstance()->getToolId();
$data['navigation_parent_id']='c'.$header_counter;
$data['navigation_title']='TinyMCE4';
$data['navigation_sortorder']=$content_counter;
$data['navigation_ispublic']=1;
$data['page_name']='lab_ddm3_tinymce4';
$data['page_description']='TinyMCE4';
$data['page_ispublic']=1;
$data['navigation_path']='';
$data['navigation_path_array']=array();
$data['permission']=array('link', 'view');
osW_VIS_Navigation::getInstance()->addNavigationElement($data);

?>