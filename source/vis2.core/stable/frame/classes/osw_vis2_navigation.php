<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS2 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS2_Navigation extends osW_Object {

	/*** VALUES ***/

	private $data=array();

	/*** METHODS CORE ***/

	public function __construct() {
		parent::__construct(1, 0);
		$this->loadNavi();
		$this->setCurrentNavigationId();
	}

	public function __destruct() {
		parent::__destruct();
	}

	/*** METHODS ***/

	private function build_sorter($key) {
		return function ($a, $b) use ($key) {
			return strnatcmp($a[$key], $b[$key]);
		};
	}

	public function addNavigationElement($data=array(), $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		if (!isset($data['page_name_intern'])) {
			$data['page_name_intern']=$data['page_name'];
			$data['page_name']=$data['page_description'];
		}

		$name_tree='navigation_tree_'.$tool_id;
		$name_unsorted='navigation_unsorted_'.$tool_id;
		$name_unsorted_name2id='navigation_name2id_'.$tool_id;

		$data['permission_link']=false;
		$data['permission_view']=false;

		if (in_array('link', $data['permission'])) {
			$data['permission_link']=true;
		}

		if (in_array('view', $data['permission'])) {
			$data['permission_view']=true;
		}

		$this->data[$name_tree][$data['navigation_parent_id']][$data['navigation_id']]=$data;
		$this->data[$name_unsorted][$data['navigation_id']]=$data;
		$this->data[$name_unsorted_name2id][$data['page_name_intern']]=$data['navigation_id'];

		foreach ($this->data[$name_tree] as $parent_id => $parent_elements) {
			uasort($this->data[$name_tree][$parent_id], $this->build_sorter('navigation_sortorder'));
		}

		if (strlen($data['page_name_intern'])>0) {
			foreach ($data['permission'] as $flag) {
				osW_VIS2_Permission::getInstance()->addPermissionByUserId($data['page_name_intern'], $flag, 0, $tool_id);
			}
		}

		$this->createNavigationPath($tool_id);
	}

	public function loadNavi($tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_tree='navigation_tree_'.$tool_id;
		$name_unsorted='navigation_unsorted_'.$tool_id;
		$name_unsorted_name2id='navigation_name2id_'.$tool_id;

		$this->data[$name_tree]=array();
		$this->data[$name_unsorted]=array();
		$this->data[$name_unsorted_name2id]=array();

		$Qselect=osW_Database::getInstance()->query('SELECT *, n.tool_id, n.page_id FROM :table_vis2_navigation: AS n LEFT JOIN :table_vis2_page: AS p on (p.tool_id=n.tool_id AND p.page_id=n.page_id) WHERE n.tool_id=:tool_id: ORDER BY n.navigation_parent_id ASC, n.navigation_sortorder ASC, n.navigation_title ASC');
		$Qselect->bindTable(':table_vis2_navigation:', 'vis2_navigation');
		$Qselect->bindTable(':table_vis2_page:', 'vis2_page');
		$Qselect->bindInt(':tool_id:', $tool_id);
		$Qselect->execute();
		if ($Qselect->numberOfRows()>0) {
			while($Qselect->next()) {
				$navigation_element=$Qselect->result;
				$navigation_element['navigation_sortorder']=intval($navigation_element['navigation_sortorder']);
				$navigation_element['custom']=false;
				$navigation_element['permission_link']=false;
				$navigation_element['permission_view']=false;
				$this->data[$name_tree][$navigation_element['navigation_parent_id']][$navigation_element['navigation_id']]=$navigation_element;
				$this->data[$name_unsorted][$navigation_element['navigation_id']]=$navigation_element;
				$this->data[$name_unsorted_name2id][$navigation_element['page_name_intern']]=$navigation_element['navigation_id'];
			}
		}
		$this->createNavigationPath($tool_id);
		$this->createNavigationPermission($tool_id);
	}

	private function createNavigationPath($tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_tree='navigation_tree_'.$tool_id;
		if (!isset($this->data[$name_tree])) {
			$this->loadNavi($tool_id);
		}

		$name_tree='navigation_tree_'.$tool_id;
		$name_unsorted='navigation_unsorted_'.$tool_id;

		foreach ($this->data[$name_tree] as $parent_id => $members) {
			foreach ($members as $member_id => $member) {
				if ($parent_id==0) {
					$this->data[$name_tree][$parent_id][$member_id]['navigation_path'] = '0_' . $member_id;
				} else {
					$this->data[$name_tree][$parent_id][$member_id]['navigation_path'] = $this->getNavigationPath($member_id, $tool_id);
				}
				$this->data[$name_tree][$parent_id][$member_id]['navigation_path_array']=explode('_', $this->data[$name_tree][$parent_id][$member_id]['navigation_path']);

				$this->data[$name_unsorted][$member_id]['navigation_path'] = $this->data[$name_tree][$parent_id][$member_id]['navigation_path'];
				$this->data[$name_unsorted][$member_id]['navigation_path_array'] = $this->data[$name_tree][$parent_id][$member_id]['navigation_path_array'];
			}
		}
	}

	private function createNavigationPermission($tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_tree='navigation_tree_'.$tool_id;
		if (!isset($this->data[$name_tree])) {
			$this->loadNavi($tool_id);
		}

		$name_tree='navigation_tree_'.$tool_id;
		$name_unsorted='navigation_unsorted_'.$tool_id;

		$ar_permission=array();
		$Qselect=osW_Database::getInstance()->query('SELECT * FROM :table_vis2_page_permission: WHERE tool_id=:tool_id:');
		$Qselect->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
		$Qselect->bindInt(':tool_id:', $tool_id);
		$Qselect->execute();
		if ($Qselect->numberOfRows()>0) {
			while($Qselect->next()) {
				if (!isset($ar_permission[$Qselect->result['page_id']])) {
					$ar_permission[$Qselect->result['page_id']]=array();
				}
				$ar_permission[$Qselect->result['page_id']][]=$Qselect->result['permission_flag'];
			}
		}

		foreach ($this->data[$name_tree] as $parent_id => $members) {
			foreach ($members as $member_id => $member) {
				if (isset($ar_permission[$member['page_id']])) {
					$this->data[$name_tree][$parent_id][$member_id]['permission']=$ar_permission[$member['page_id']];
					$this->data[$name_unsorted][$member_id]['permission']=$ar_permission[$member['page_id']];
				} else {
					$this->data[$name_tree][$parent_id][$member_id]['permission']=array();
					$this->data[$name_unsorted][$member_id]['permission']=array();
				}
			}
		}
	}

	private function getNavigationPath($member_id, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_tree='navigation_tree_'.$tool_id;
		if (!isset($this->data[$name_tree])) {
			$this->loadNavi($tool_id);
		}

		$name_tree='navigation_tree_'.$tool_id;
		$name_unsorted='navigation_unsorted_'.$tool_id;

		$link_path = array();
		$link_path[] = $member_id;
		if (isset($this->data[$name_unsorted][$member_id])) {
			while (($this->data[$name_unsorted][$member_id]['navigation_parent_id']!=0)) {
				$member_id = $this->data[$name_unsorted][$member_id]['navigation_parent_id'];
				$link_path[] = $member_id;
			}
		}
		$link_path[] = 0;
		$link_path = array_reverse($link_path);
		$link_path = implode('_', $link_path);
		return $link_path;
	}

	public function getNavigationUnsorted($tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_tree='navigation_tree_'.$tool_id;
		if (!isset($this->data[$name_tree])) {
			$this->loadNavi($tool_id);
		}

		$name_unsorted='navigation_unsorted_'.$tool_id;
		return $this->data[$name_unsorted];
	}

	public function getNavigationRealUnsorted($tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_real_unsorted='navigation_real_unsorted_'.$tool_id;
		if (!isset($this->data[$name_real_unsorted])) {
			$name_tree='navigation_tree_'.$tool_id;
			if (!isset($this->data[$name_tree])) {
				$this->loadNavi($tool_id);
			}

			$name_unsorted='navigation_unsorted_'.$tool_id;
			$this->data[$name_real_unsorted]=$this->data[$name_unsorted];

			foreach ($this->data[$name_real_unsorted] as $key => $value) {
				if ($value['custom']===true) {
					unset($this->data[$name_real_unsorted][$key]);
				}
			}
		}
		return $this->data[$name_real_unsorted];
	}

	public function getNavigation($parent_id=0, $max_level=0, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_tree='navigation_tree_'.$tool_id;
		if (!isset($this->data[$name_tree])) {
			$this->loadNavi($tool_id);
		}

		$name='navigation_'.$parent_id.'_'.$max_level.'_'.$tool_id;
		if (!isset($this->data[$name])) {
			$this->data[$name]=$this->createNavigationRecursive($parent_id, 0, $max_level, $tool_id);
		}

		return $this->data[$name];
	}

	private function createNavigationRealRecursive($parent_id, $level=0, $max_level=0, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_tree='navigation_tree_'.$tool_id;
		$name_navigation='navigation_data_'.$tool_id;

		if (isset($this->data[$name_tree][$parent_id])) {
			$data=array();
			foreach ($this->data[$name_tree][$parent_id] as $category_id => $category) {
				$_data=array();
				$category['navigation_level']=$level;
				$this->data[$name_navigation][$category_id]=$category;

				if ((isset($this->data['current_navigation_path_array_'.$tool_id]))&&(in_array($category_id, $this->data['current_navigation_path_array_'.$tool_id]))) {
					$category['navigation_active']=true;
				} else {
					$category['navigation_active']=false;
				}
				if (isset($this->data[$name_tree][$category_id])&&(($max_level==0)||($max_level>$level+1))) {
					$this->data[$name_navigation][$category_id]['navigation_subcats']=true;
					$category['navigation_subcats']=true;
					$_data['info']=$category;
					$_data['links']=$this->createNavigationRealRecursive($category_id, $level+1, $max_level, $tool_id);
					foreach ($_data['links'] as $_category) {
						if ($_category['info']['navigation_active']===true) {
							$_data['info']['navigation_active']=true;
						}
					}
					$data[]=$_data;
				} else {
					$this->data[$name_navigation][$category_id]['navigation_subcats']=false;
					$category['navigation_subcats']=false;
					if (!isset($category['navigation_active'])) {
						$category['navigation_active']=false;
					}
					$_data['info']=$category;
					$_data['links']=array();
					$data[]=$_data;
				}
			}
			return $data;
		}
	}

	private function createNavigationRecursive($parent_id, $level=0, $max_level=0, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_tree='navigation_tree_'.$tool_id;
		$name_navigation='navigation_data_'.$tool_id;

		if (isset($this->data[$name_tree][$parent_id])) {
			$data=array();
			foreach ($this->data[$name_tree][$parent_id] as $category_id => $category) {
				if($category['navigation_ispublic']==1) {
					$_data=array();
					$category['navigation_level']=$level;
					$this->data[$name_navigation][$category_id]=$category;

					if ((isset($this->data['current_navigation_path_array_'.$tool_id]))&&(in_array($category_id, $this->data['current_navigation_path_array_'.$tool_id]))) {
						$category['navigation_active']=true;
					} else {
						$category['navigation_active']=false;
					}
					if (isset($this->data[$name_tree][$category_id])&&(($max_level==0)||($max_level>$level+1))) {
						$this->data[$name_navigation][$category_id]['navigation_subcats']=true;
						$category['navigation_subcats']=true;
						$_data['info']=$category;
						$_data['links']=$this->createNavigationRecursive($category_id, $level+1, $max_level, $tool_id);
						foreach ($_data['links'] as $_category) {
							if ($_category['info']['navigation_active']===true) {
								$_data['info']['navigation_active']=true;
							}
						}
						$data[]=$_data;
					} else {
						$this->data[$name_navigation][$category_id]['navigation_subcats']=false;
						$category['navigation_subcats']=false;
						if (!isset($category['navigation_active'])) {
							$category['navigation_active']=false;
						}
						$_data['info']=$category;
						$_data['links']=array();
						$data[]=$_data;
					}
				}
			}
			return $data;
		}
	}

	public function getNavigationWithPermission($parent_id=0, $max_level=0, $user_id=0, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		if ($user_id==0) {
			$user_id=osW_VIS2_User::getInstance()->getId();
		}

		$name='navigation_'.$parent_id.'_'.$max_level.'_'.$tool_id.'_'.$user_id;
		if (!isset($this->data[$name])) {
			$this->data[$name]=$this->getNavigation($parent_id, $max_level, $tool_id);

			foreach ($this->data[$name] as $id => $_navigation_element) {
				$this->data[$name][$id]=$this->checkNavigationPermissionRecursive($_navigation_element, $user_id, $tool_id);
			}
		}

		return $this->data[$name];
	}

	private function checkNavigationPermissionRecursive($navigation_element, $user_id=0, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		if ($user_id==0) {
			$user_id=osW_VIS2_User::getInstance()->getId();
		}

		if (($navigation_element['info']['page_name_intern']!='')&&(osW_VIS2_Permission::getInstance()->checkPermission('link', $navigation_element['info']['page_name_intern'], $user_id, $tool_id)===true)) {
			$navigation_element['info']['permission_link']=true;
		}

		if (($navigation_element['info']['page_name_intern']!='')&&(osW_VIS2_Permission::getInstance()->checkPermission('view', $navigation_element['info']['page_name_intern'], $user_id, $tool_id)===true)) {
			$navigation_element['info']['permission_view']=true;
		}

		if ((isset($navigation_element['links']))&&(!empty($navigation_element['links']))) {
			foreach ($navigation_element['links'] as $id => $_navigation_element) {
				$navigation_element['links'][$id]=$this->checkNavigationPermissionRecursive($_navigation_element, $user_id, $tool_id);
			}
		}

		return $navigation_element;
	}


	public function getNavigationReal($parent_id=0, $max_level=0, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_tree='navigation_real_'.$tool_id;
		if (!isset($this->data[$name_tree])) {
			$this->loadNavi($tool_id);
		}

		$name='navigation_real_'.$parent_id.'_'.$max_level.'_'.$tool_id;
		if (!isset($this->data[$name])) {
			$this->data[$name]=$this->createNavigationRealRecursive($parent_id, 0, $max_level, $tool_id);
		}

		return $this->data[$name];
	}

	private function checkNavigationRealRecursive($navigation_element, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		if ($navigation_element['info']['custom']===true) {
			return array();
		}

		if ((isset($navigation_element['links']))&&(!empty($navigation_element['links']))) {
			foreach ($navigation_element['links'] as $id => $_navigation_element) {
				if ($this->checkNavigationRealRecursive($_navigation_element, $tool_id)!=array()) {
					$navigation_element['links'][$id]=$this->checkNavigationRealRecursive($_navigation_element, $tool_id);
				} else {
					unset($navigation_element['links'][$id]);
				}
			}
		}
		return $navigation_element;
	}

	function setCurrentNavigationId($navigation_id=0, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_tree='navigation_tree_'.$tool_id;
		if (!isset($this->data[$name_tree])) {
			$this->loadNavi($tool_id);
		}

		$name_unsorted='navigation_unsorted_'.$tool_id;
		if ((!isset($this->data[$name_unsorted]))||(!isset($this->data[$name_unsorted][$navigation_id]))) {
			$this->data['current_navigation_id_'.$tool_id]=0;
			$this->data['current_navigation_path_array_'.$tool_id]=array();
			return false;
		}
		$this->data['current_navigation_id_'.$tool_id]=$navigation_id;
		$this->data['current_navigation_path_array_'.$tool_id]=$this->data[$name_unsorted][$navigation_id]['navigation_path_array'];
		return true;
	}

	public function getPages($tool_id) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name='pages_'.$tool_id;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$Qselect=osW_Database::getInstance()->query('SELECT * FROM :table_vis2_page: WHERE tool_id=:tool_id: AND page_ispublic=:page_ispublic: ORDER BY page_name ASC, page_name_intern ASC');
			$Qselect->bindTable(':table_vis2_page:', 'vis2_page');
			$Qselect->bindInt(':tool_id:', $tool_id);
			$Qselect->bindInt(':page_ispublic:', 1);
			$Qselect->execute();
			if ($Qselect->numberOfRows()>0) {
				while($Qselect->next()) {
					$this->data[$name][$Qselect->result['page_id']]=$Qselect->result['page_name'].' ('.$Qselect->result['page_name_intern'].')';
				}
			}
		}
		return $this->data[$name];
	}

	function getPageIdByName($page_name, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_unsorted_name2id='navigation_name2id_'.$tool_id;

		if ((!isset($this->data[$name_unsorted_name2id]))||(!isset($this->data[$name_unsorted_name2id][$page_name]))) {
			return 0;
		}

		return $this->data[$name_unsorted_name2id][$page_name];
	}

	function getPageNamebyId($page_id, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name_unsorted='navigation_unsorted_'.$tool_id;

		if ((!isset($this->data[$name_unsorted]))||(!isset($this->data[$name_unsorted][$page_id]))) {
			return 'not found';
		}

		return $this->data[$name_unsorted][$page_id]['navigation_title'];
	}

	function getPageName($page, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		if ($page=='vis_dashboard') {
			return 'Dashboard';
		}

		if ($page=='vis_settings') {
			return 'Einstellungen';
		}

		if ($page=='vis_profile') {
			return 'Profil';
		}

		$name_page2id='navigation_name2id_'.$tool_id;

		if ((!isset($this->data[$name_page2id]))||(!isset($this->data[$name_page2id][$page]))) {
			return 'not found';
		}

		return $this->getPageNamebyId($this->data[$name_page2id][$page], $tool_id);
	}

	public function validatePage($page='', $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		if ($page=='') {
			$page=$this->getPage();
		}

		if ($page=='vis_api') {
			return true;
		}

		$name_unsorted_name2id='navigation_name2id_'.$tool_id;

		if ((isset($this->data[$name_unsorted_name2id]))||($this->data[$name_unsorted_name2id][$page])) {
			return true;
		}

		$this->setPage($this->getDefaultPage());
		return false;
	}

	/*
	 * Ermittelt die aktuelle ToolPage
	*/
	public function setPage($page) {
		$page=strtolower($page);
		if (strlen($page)) {
			$this->page=$page;
		}
		return true;
	}

	/*
	 * Liefert die aktuelle ToolPage
	*/
	public function getPage() {
		return $this->page;
	}

	/*
	 * Liefert die Default ToolPage
	*/
	public function getDefaultPage() {
		return 'vis_dashboard';
	}

	/**
	 *
	 * @return osW_VIS2_Navigation
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>