<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS2 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS2_Group extends osW_Object {

	/*** VALUES ***/

	public $data=array();

	/*** METHODS CORE ***/

	public function __construct() {
		parent::__construct(1, 1);
	}

	public function __destruct() {
		parent::__destruct();
	}

	/*** METHODS ***/

	/*
	 * Liefert den passenden Array fuer osW_Form->drawSelectField mit id als index
	*/
	public function getListArray($key='group_id', $value='group_name') {
		if (isset($this->data['listarray'])) {
			return $this->data['listarray'];
		}
		$this->data['listarray']=array();
		$QselectTools = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_group: WHERE group_status=:group_status: AND tool_id=:tool_id: ORDER BY :order: ASC');
		$QselectTools->bindTable(':table_vis2_group:', 'vis2_group');
		$QselectTools->bindInt(':group_status:', 1);
		$QselectTools->bindInt(':tool_id:', osW_VIS2::getInstance()->getToolID());
		$QselectTools->bindValue(':order:', $value);
		$QselectTools->execute();
		if ($QselectTools->query_handler===false) {
			$this->__initDB();
			$QselectTools->execute();
		}
		if ($QselectTools->numberOfRows()>0) {
			while ($QselectTools->next()) {
				$this->data['listarray'][$QselectTools->value($key)]=$QselectTools->value($value);
			}
		}
		return $this->data['listarray'];
	}

	public function getGroupsByUserId($user_id=0) {
		if ($user_id==0) {
			$user_id=osW_VIS2_User::getInstance()->getId();
		}
		if (isset($this->data['groups'][$user_id])) {
			return $this->data['groups'][$user_id];
		}
		$this->data['groups'][$user_id]=array();
		$QloadData = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_user_group: WHERE user_id=:user_id: AND tool_id=:tool_id:');
		$QloadData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
		$QloadData->bindInt(':user_id:', $user_id);
		$QloadData->bindInt(':tool_id:', osW_VIS2::getInstance()->getToolId());
		$QloadData->execute();
		if ($QloadData->query_handler===false) {
			$this->__initDB();
			$QloadData->execute();
		}
 		if ($QloadData->numberOfRows()>0) {
			while ($QloadData->next()) {
				$this->data['groups'][$user_id][$QloadData->Value('group_id')]=$QloadData->Value('group_id');
			}
		}
		return $this->data['groups'][$user_id];
	}

	public function getGroupsNameInternByUserId($user_id=0) {
		if ($user_id==0) {
			$user_id=osW_VIS2_User::getInstance()->getId();
		}
		if (isset($this->data['groupsnameintern'][$user_id])) {
			return $this->data['groupsnameintern'][$user_id];
		}
		$this->data['groupsnameintern'][$user_id]=array();
		$QloadData = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_user_group: AS ug INNER JOIN :table_vis2_group: AS g ON (g.group_id=ug.group_id) WHERE ug.user_id=:user_id: AND ug.tool_id=:tool_id:');
		$QloadData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
		$QloadData->bindTable(':table_vis2_group:', 'vis2_group');
		$QloadData->bindInt(':user_id:', $user_id);
		$QloadData->bindInt(':tool_id:', osW_VIS2::getInstance()->getToolId());
		$QloadData->execute();
		if ($QloadData->query_handler===false) {
			$this->__initDB();
			$QloadData->execute();
		}
 		if ($QloadData->numberOfRows()>0) {
			while ($QloadData->next()) {
				$this->data['groupsnameintern'][$user_id][$QloadData->Value('group_name_intern')]=$QloadData->Value('group_name_intern');
			}
		}
		return $this->data['groupsnameintern'][$user_id];
	}

	/**
	 *
	 * @return osW_VIS2_Group
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>