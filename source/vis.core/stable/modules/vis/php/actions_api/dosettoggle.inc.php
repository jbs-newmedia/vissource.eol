<?php

$name=h()->_catch('name', '', 'p');
$compressed=h()->_catch('compressed', '', 'p');

if (($name!='')&&($compressed!='')) {
	osW_VIS_User::getInstance()->setUserValue('compressed_'.osW_VIS::getInstance()->getTool().'_'.$name, $compressed, 'integer');
}

?>