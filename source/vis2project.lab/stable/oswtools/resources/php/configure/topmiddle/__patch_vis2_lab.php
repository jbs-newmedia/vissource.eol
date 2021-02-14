<?php

$this->data['settings']=array();

$this->data['settings']['data']=array(
	'page_title'=>'Create/Update VIS2Lab-Tables',
);

if (($position=='run')&&(isset($_POST['next']))&&($_POST['next']=='next')) {
	if ((isset($this->data['values_json']['database_db']))&&(isset($this->data['values_json']['database_db']))&&(isset($this->data['values_json']['database_db']))&&(isset($this->data['values_json']['database_db']))) {
		osW_Tool_Database::addDatabase('default', array('type'=>'mysql', 'database'=>$this->data['values_json']['database_db'], 'server'=>$this->data['values_json']['database_server'], 'username'=>$this->data['values_json']['database_username'], 'password'=>$this->data['values_json']['database_password'], 'pconnect'=>false, 'prefix'=>$this->data['values_json']['database_prefix']));


		$patches=array('osw_vis2_lab');

		foreach ($patches as $_class) {
			$class=root_path.'frame/classes/'.$_class.'.php';
			$class_content=file_get_contents($class);
			preg_match('/__construct\(([0-9]+), ([0-9]+)(,|\))/Uis', $class_content, $result);
			$cv_version=$result[1];
			$cv_build=$result[2];
			$class_patch=root_path.'frame/classes_patch/'.$_class.'.php';
			$class_patch_content=file_get_contents($class_patch);
			$class_patch_content=str_replace('$this->_version', $cv_version, $class_patch_content);
			$class_patch_content=str_replace('$this->_build', $cv_build, $class_patch_content);
			$class_patch_content=str_replace('osW_Database::getInstance()', 'osW_Tool_Database::getInstance()', $class_patch_content);
			$class_patch_content=str_replace('<?php', '#<?php', $class_patch_content);
			$class_patch_content=preg_replace('/vOut\(\'([a-zA-Z0-9\_\-]+)\'\)/Uis', '\$this->data[\'values_json\'][\'$1\']', $class_patch_content);
			eval($class_patch_content);
		}

		if (defined('root_path')) {
			$dir=root_path.'frame/classes_patch/osw_vis2_lab/';
			$files=glob($dir.'*.{php}', GLOB_BRACE);
			foreach ($files as $file) {
				$class_patch_content=urldecode(file_get_contents($file));
				$class_patch_content=str_replace('osW_Database::getInstance()', 'osW_Tool_Database::getInstance()', $class_patch_content);
				$class_patch_content=str_replace('<?php', '#<?php', $class_patch_content);
				$class_patch_content=preg_replace('/vOut\(\'([a-zA-Z0-9\_\-]+)\'\)/Uis', '\$this->data[\'values_json\'][\'$1\']', $class_patch_content);
				eval($class_patch_content);
			}
		}

		$this->data['messages'][]='VIS2Lab-Tables were created/updated successfully';
	} else {
		$this->data['messages'][]='VIS2Lab-Tables creation/update was skipped (there is no database configured)';
	}
}

if (($position=='run')&&(isset($_POST['prev']))&&($_POST['prev']=='prev')) {
	$this->data['messages'][]='VIS2Lab-Tables creation/update was skipped (go to previous page)';
}

?>