<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS_Manager extends osW_Object {

	/*** PROPERTIES ***/

	private $data=array();

	/*** METHODS CORE ***/


	public function __construct() {
		parent::__construct(__CLASS__, 1, 0);
		$this->tool='desktop';
	}

	public function __destruct() {
		parent::__destruct();
	}

	/*** METHODS ***/

	public function getTools() {
		if (!isset($this->data['tools'])) {
			$QselectTools = osW_Database::getInstance()->query('SELECT * FROM :table_vis_tool: WHERE 1 ORDER BY tool_name ASC');
			$QselectTools->bindTable(':table_vis_tool:', 'vis_tool');
			$QselectTools->execute();
			if ($QselectTools->query_handler===false) {
				osW_VIS::getInstance()->__initDB();
				$QselectTools->execute();
			}
			if ($QselectTools->numberOfRows()>0) {
				while ($QselectTools->next()) {
					$this->data['tools'][$QselectTools->value('tool_id')]=$QselectTools->value('tool_name');
				}
			} else {
				$this->data['tools']=array();
			}
		}
		return $this->data['tools'];
	}

	public function getUsers() {
		if (!isset($this->data['users'])) {
			$QselectTools = osW_Database::getInstance()->query('SELECT * FROM :table_vis_user: WHERE 1 ORDER BY user_lastname ASC, user_firstname ASC, user_name ASC');
			$QselectTools->bindTable(':table_vis_user:', 'vis_user');
			$QselectTools->execute();
			if ($QselectTools->query_handler===false) {
				osW_VIS_User::getInstance()->__initDB();
				$QselectTools->execute();
			}
			if ($QselectTools->numberOfRows()>0) {
				while ($QselectTools->next()) {
					$this->data['users'][$QselectTools->value('user_id')]=$QselectTools->value('user_lastname').' '.$QselectTools->value('user_firstname');
				}
			} else {
				$this->data['users']=array();
			}
		}
		return $this->data['users'];
	}

	public function getGroups($tool_id=0) {
		if (!isset($this->data['groups'])) {
			$QselectTools = osW_Database::getInstance()->query('SELECT * FROM :table_vis_group: WHERE 1 ORDER BY group_name_intern ASC');
			$QselectTools->bindTable(':table_vis_group:', 'vis_group');
			$QselectTools->execute();
			if ($QselectTools->query_handler===false) {
				osW_VIS_Group::getInstance()->__initDB();
				$QselectTools->execute();
			}
			if ($QselectTools->numberOfRows()>0) {
				while ($QselectTools->next()) {
					$this->data['groups'][$QselectTools->value('tool_id')][$QselectTools->value('group_id')]=$QselectTools->value('group_name');
				}
			}
		}
		if (($tool_id>0)&&(isset($this->data['groups'][$tool_id]))) {
			return $this->data['groups'][$tool_id];
		} else {
			return array();
		}
		return $this->data['groups'];
	}

	/**
	 *
	 * @return osW_VIS_Manager
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>