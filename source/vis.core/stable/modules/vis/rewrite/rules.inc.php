<?php

$check_parameters=false;

$parameters=$ar_parameters;

if (isset($parameters['vistool'])) {
	$seo_base_uri.='vis/'.$parameters['vistool'].'/';
} else {
	$seo_base_uri.='vis/'.vOut('vis_login_module').'/';
}

if ($seowrite_inpage==true) {
	$seo_base_uri.=h()->_catch('vispage', '', 'g');
} else {
	if (isset($parameters['vispage'])) {
		$seo_base_uri.=$parameters['vispage'];
	}	
}

unset($parameters['module']);
unset($parameters['vistool']);
unset($parameters['vispage']);

$seo_base_uri.='?';
foreach ($parameters as $key=>$value) {
	$seo_base_uri.=$key.'='.$value.'&';
}
$seo_base_uri=substr($seo_base_uri, 0, -1);

if (isset($extend[1])) {
	$seo_base_uri.='#'.$extend[1];
}

$go_default=false;

?>