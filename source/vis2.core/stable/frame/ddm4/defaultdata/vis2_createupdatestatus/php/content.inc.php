<?php

$default_options['enabled']=true;
$default_options['options']['prefix']='';
$default_options['options']['order']=false;
$default_options['options']['display_create_time']=true;
$default_options['options']['display_create_user']=true;
$default_options['options']['display_update_time']=true;
$default_options['options']['display_update_user']=true;
$default_options['options']['date_format']='%d.%m.%Y %H:%M';
$default_options['options']['month_asname']=false;
$default_options['options']['text_create_time']=$this->getGroupMessage($ddm_group, 'create_time');
$default_options['options']['text_create_user']=$this->getGroupMessage($ddm_group, 'create_user');
$default_options['options']['text_update_time']=$this->getGroupMessage($ddm_group, 'update_time');
$default_options['options']['text_update_user']=$this->getGroupMessage($ddm_group, 'update_user');
$default_options['name_array']=array('create_time', 'create_user_id', 'update_time', 'update_user_id');
$default_options['_search']['enabled']=false;

?>