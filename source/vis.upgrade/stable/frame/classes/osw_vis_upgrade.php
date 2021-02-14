<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS_Upgrade extends osW_Object {

	/*** VALUES ***/

	private $data=array();

	/*** METHODS CORE ***/

	public function __construct() {
		parent::__construct(1, 0);
	}

	public function __destruct() {
		parent::__destruct();
	}

	/*** METHODS ***/

	public function upgrade($tool='', $tool_id=0) {
		if ($tool=='') {
			$tool=osW_VIS::getInstance()->getTool();
		}
		if ($tool_id==0) {
			$tool_id=osW_VIS::getInstance()->getToolId();
		}
		if (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.$tool.'/php/navigation.inc.php')) {
			require(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.$tool.'/php/navigation.inc.php');
			$this->importPHP2DB($tool, $tool_id, $vis_navigation, $vis_permission_flags, $vis_permission_text);
			copy(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.$tool.'/php/navigation.inc.php', vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.$tool.'/php/navigation.backup.inc.php');
			h()->_chmod(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.$tool.'/php/navigation.backup.inc.php');
			unlink(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.$tool.'/php/navigation.inc.php');
		}
	}

	public function importPHP2DB($tool, $tool_id, $navigation, $permission_flags, $permission_text) {
		$_vis_script=array();
		$_vis_script['permission']=array();
		foreach ($permission_text as $key => $value) {
			$_vis_script['permission'][]=array(
				'permission_flag'=>$key,
				'permission_title'=>$value,
				'permission_ispublic'=>1,
			);
		}

		$_vis_script['navigation']=array();
		$i=0;
		$ar_header=array();
		foreach ($navigation as $key => $navigation_group) {
			if ((isset($navigation_group['info']))&&(isset($navigation_group['links']))) {
				$i=$i+10;
				$_vis_script['navigation'][]=array(
					'navigation_parent_id'=>'',
					'navigation_title'=>$navigation_group['info']['name'],
					'navigation_sortorder'=>$i,
					'navigation_ispublic'=>1,
					'page'=>array(
						'page_name_intern'=>'header_'.strtolower(h()->_outputURLString($navigation_group['info']['name'])),
						'page_name'=>'Header '.$navigation_group['info']['name'],
						'page_ispublic'=>1,
						'permission'=>array('link'),
					),
					'permission'=>array('link'),
				);
				$ar_header[]=array('permission_page'=>'header_'.strtolower(h()->_outputURLString($navigation_group['info']['name'])), 'permission_flag'=>'link');

				$k=0;
				foreach ($navigation_group['links'] as $key2 => $navigation_group2) {
					if (!isset($permission_flags[$key2])) {
						$permission_flags[$key2]=array('link', 'view');
					}

					$perm_key2=$key2;

					if ($key2=='vis_user') {
						$k=$k+10;
					}

					if ($key2=='vis_group') {
						$k=$k-20;
						$key2='vis_navigation';
						$navigation_group2='Navigation';

						$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.$tool.'/php/vis_navigation.inc.php';
						if (!file_exists($file)) {
							file_put_contents($file, "<?php\n\nif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/vis_navigation.inc.php')) {\n	include(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/vis_navigation.inc.php');\n}\n\n?>");
							h()->_chmod($file);
						}

						$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.$tool.'/tpl/vis_navigation.tpl.php';
						if (!file_exists($file)) {
							file_put_contents($file, "<?php\n\nif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/tpl/vis_navigation.tpl.php')) {\n	include(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/tpl/vis_navigation.tpl.php');\n}\n\n?>");
							h()->_chmod($file);
						}
					}

					$k=$k+10;

					$_vis_script['navigation'][]=array(
						'navigation_parent_id'=>'header_'.strtolower(h()->_outputURLString($navigation_group['info']['name'])),
						'navigation_title'=>$navigation_group2,
						'navigation_sortorder'=>$k,
						'navigation_ispublic'=>1,
						'page'=>array(
							'page_name_intern'=>$key2,
							'page_name'=>$navigation_group2,
							'page_ispublic'=>1,
							'permission'=>$permission_flags[$perm_key2],
						),
						'permission'=>$permission_flags[$perm_key2],
					);
				}
			}
		}

		// Permission
		foreach ($_vis_script['permission'] as $permission) {
			$QreadData=osW_Database::getInstance()->query('SELECT permission_id FROM :table_vis_permission: WHERE tool_id=:tool_id: AND permission_flag=:permission_flag:');
			$QreadData->bindTable(':table_vis_permission:', 'vis_permission');
			$QreadData->bindInt(':tool_id:', $tool_id);
			$QreadData->bindValue(':permission_flag:', $permission['permission_flag']);
			$QreadData->execute();
			if ($QreadData->numberOfRows()==0) {
				$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_permission: (tool_id, permission_flag, permission_title, permission_ispublic) VALUES (:tool_id:, :permission_flag:, :permission_title:, :permission_ispublic:)');
				$QinsertData->bindTable(':table_vis_permission:', 'vis_permission');
				$QinsertData->bindValue(':permission_flag:', $permission['permission_flag']);
				$QinsertData->bindValue(':permission_title:', $permission['permission_title']);
				$QinsertData->bindInt(':permission_ispublic:', $permission['permission_ispublic']);
				$QinsertData->bindInt(':tool_id:', $tool_id);
				$QinsertData->execute();
			}
		}

		$_pages=array();
		$_navigation=array();
		foreach ($_vis_script['navigation'] as $navigation) {
			// Page
			$QreadData=osW_Database::getInstance()->query('SELECT page_id FROM :table_vis_page: WHERE page_name_intern=:page_name_intern: AND tool_id=:tool_id:');
			$QreadData->bindTable(':table_vis_page:', 'vis_page');
			$QreadData->bindValue(':page_name_intern:', $navigation['page']['page_name_intern']);
			$QreadData->bindInt(':tool_id:', $tool_id);
			$QreadData->execute();
			if ($QreadData->numberOfRows()==0) {
				$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_page: (tool_id, page_name_intern, page_name, page_ispublic) VALUES (:tool_id:, :page_name_intern:, :page_name:, :page_ispublic:)');
				$QinsertData->bindTable(':table_vis_page:', 'vis_page');
				$QinsertData->bindInt(':tool_id:', $tool_id);
				$QinsertData->bindValue(':page_name_intern:', $navigation['page']['page_name_intern']);
				$QinsertData->bindValue(':page_name:', $navigation['page']['page_name']);
				$QinsertData->bindInt(':page_ispublic:', $navigation['page']['page_ispublic']);
				$QinsertData->execute();
				$_pages[$navigation['page']['page_name_intern']]=$QreadData->nextId();
			} else {
				$QreadData->next();
				$_pages[$navigation['page']['page_name_intern']]=$QreadData->value('page_id');
			}

			foreach ($navigation['page']['permission'] as $permission) {
				// Page-Permission
				$QreadData=osW_Database::getInstance()->query('SELECT page_id FROM :table_vis_page_permission: WHERE page_id=:page_id: AND tool_id=:tool_id: AND permission_flag=:permission_flag:');
				$QreadData->bindTable(':table_vis_page_permission:', 'vis_page_permission');
				$QreadData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
				$QreadData->bindInt(':tool_id:', $tool_id);
				$QreadData->bindValue(':permission_flag:', $permission);
				$QreadData->execute();
				if ($QreadData->numberOfRows()==0) {
					$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_page_permission: (page_id, tool_id, permission_flag) VALUES (:page_id:, :tool_id:, :permission_flag:)');
					$QinsertData->bindTable(':table_vis_page_permission:', 'vis_page_permission');
					$QinsertData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
					$QinsertData->bindInt(':tool_id:', $tool_id);
					$QinsertData->bindValue(':permission_flag:', $permission);
					$QinsertData->execute();
				}
			}

			// Navigation
			$QreadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis_navigation: WHERE tool_id=:tool_id: AND page_id=:page_id:');
			$QreadData->bindTable(':table_vis_navigation:', 'vis_navigation');
			$QreadData->bindInt(':tool_id:', $tool_id);
			$QreadData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
			$QreadData->execute();
			if ($QreadData->numberOfRows()==0) {
				$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_navigation: (tool_id, page_id, navigation_parent_id, navigation_title, navigation_sortorder, navigation_ispublic) VALUES (:tool_id:, :page_id:, :navigation_parent_id:, :navigation_title:, :navigation_sortorder:, :navigation_ispublic:)');
				$QinsertData->bindTable(':table_vis_navigation:', 'vis_navigation');
				$QinsertData->bindInt(':tool_id:', $tool_id);
				$QinsertData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
				if (isset($_navigation[$navigation['navigation_parent_id']])) {
					$QinsertData->bindInt(':navigation_parent_id:', $_navigation[$navigation['navigation_parent_id']]);
				} else {
					$QinsertData->bindInt(':navigation_parent_id:', 0);
				}
				$QinsertData->bindValue(':navigation_title:', $navigation['navigation_title']);
				$QinsertData->bindInt(':navigation_sortorder:', $navigation['navigation_sortorder']);
				$QinsertData->bindInt(':navigation_ispublic:', $navigation['navigation_ispublic']);
				$QinsertData->execute();
				$_navigation[$navigation['page']['page_name_intern']]=$QinsertData->nextId();
			} else {
				$QreadData->next();
				$_navigation[$navigation['page']['page_name_intern']]=$QreadData->result['navigation_id'];
			}
		}

		// Groups
		$QreadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis_group: WHERE tool_id=:tool_id:');
		$QreadData->bindTable(':table_vis_group:', 'vis_group');
		$QreadData->bindInt(':tool_id:', $tool_id);
		$QreadData->execute();
		if ($QreadData->numberOfRows()>0) {
			while ($QreadData->next()) {
				$perm=array();
				foreach ($ar_header as $header) {
					$perm[]=array('group_id'=>$QreadData->result['group_id'], 'permission_page'=>$header['permission_page'], 'permission_flag'=>$header['permission_flag']);
				}
				$perm[]=array('group_id'=>$QreadData->result['group_id'], 'permission_page'=>'start', 'permission_flag'=>'view');
				$perm[]=array('group_id'=>$QreadData->result['group_id'], 'permission_page'=>'start', 'permission_flag'=>'link');
				$perm[]=array('group_id'=>$QreadData->result['group_id'], 'permission_page'=>'vis_logout', 'permission_flag'=>'view');
				$perm[]=array('group_id'=>$QreadData->result['group_id'], 'permission_page'=>'vis_logout', 'permission_flag'=>'link');

				foreach ($perm as $_perm) {
					$QselectData=osW_Database::getInstance()->query('SELECT * FROM :table_vis_group_permission: WHERE group_id=:group_id: AND permission_page=:permission_page: AND permission_flag=:permission_flag:');
					$QselectData->bindTable(':table_vis_group_permission:', 'vis_group_permission');
					$QselectData->bindInt(':group_id:', $_perm['group_id']);
					$QselectData->bindValue(':permission_page:', $_perm['permission_page']);
					$QselectData->bindValue(':permission_flag:', $_perm['permission_flag']);
					$QselectData->execute();
					if ($QselectData->numberOfRows()==0) {
						$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_group_permission: (group_id, permission_page, permission_flag) VALUES (:group_id:, :permission_page:, :permission_flag:)');
						$QinsertData->bindTable(':table_vis_group_permission:', 'vis_group_permission');
						$QinsertData->bindInt(':group_id:', $_perm['group_id']);
						$QinsertData->bindValue(':permission_page:', $_perm['permission_page']);
						$QinsertData->bindValue(':permission_flag:', $_perm['permission_flag']);
						$QinsertData->execute();
					}
				}
			}
		}
	}
}

?>