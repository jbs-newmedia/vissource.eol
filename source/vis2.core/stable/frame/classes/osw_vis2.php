<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS2 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS2 extends osW_Object {

	/*** PROPERTIES ***/

	private $data=array();

	/*** METHODS CORE ***/

	public function __construct() {
		parent::__construct(1, 0);
		$this->tool='';
		$this->page='';
	}

	public function __destruct() {
		parent::__destruct();
	}

	/*** METHODS ***/

	/*
	 * Ermittelt die vorhanden Tools
	 */
	public function getTools() {
		if (isset($this->data['tools'])) {
			return $this->data['tools'];
		}
		$this->data['tools']=array();
		$this->data['tools'][vOut('vis2_login_module')]='Logon';
		$this->data['tools'][vOut('vis2_chtool_module')]='ChTool';
		$QselectTools = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_tool: WHERE tool_status=:tool_status: ORDER BY tool_name ASC');
		$QselectTools->bindTable(':table_vis2_tool:', 'vis2_tool');
		$QselectTools->bindInt(':tool_status:', 1);
		$QselectTools->execute();
		if ($QselectTools->query_handler===false) {
			$this->__initDB();
			$QselectTools->execute();
		}
		if ($QselectTools->numberOfRows()>0) {
			while ($QselectTools->next()) {
				$this->data['tools'][$QselectTools->value('tool_name_intern')]=$QselectTools->value('tool_name');
			}
		}
		return $this->data['tools'];
	}

	/*
	 * Ermittelt die vorhanden Tools mit Details
	*/
	public function getToolsWithDetails() {
		$name='toolswithdetails';
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}

		$this->data[$name]=array();
		$this->data[$name][vOut('vis2_login_module')]=array(
			'tool_name_intern'=>vOut('vis2_login_module'),
			'tool_name'=>'Logon',
		);
		$QselectTools = osW_Database::getInstance()->query('SELECT tool_id, tool_name, tool_name_intern, tool_description, tool_status, tool_hide_logon, tool_hide_navigation FROM :table_vis2_tool: WHERE tool_status=:tool_status: ORDER BY tool_name ASC');
		$QselectTools->bindTable(':table_vis2_tool:', 'vis2_tool');
		$QselectTools->bindInt(':tool_status:', 1);
		$QselectTools->execute();
		if ($QselectTools->query_handler===false) {
			$this->__initDB();
			$QselectTools->execute();
		}
		if ($QselectTools->numberOfRows()>0) {
			while ($QselectTools->next()) {
				$this->data[$name][$QselectTools->value('tool_name_intern')]=$QselectTools->result;
			}
		}
		return $this->data[$name];
	}

	/*
	 * Ermittelt Details eine Tools
	*/
	public function getToolDetails($tool_id=0) {
		if ($tool_id==0) {
			$tool_id=$this->getToolID();
		}
		$name='tooldetails';
		if (isset($this->data[$name][$tool_id])) {
			return $this->data[$name][$tool_id];
		}

		$this->data[$name][$tool_id]=array();
		$QselectTool = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_tool: WHERE tool_id=:tool_id:');
		$QselectTool->bindTable(':table_vis2_tool:', 'vis2_tool');
		$QselectTool->bindInt(':tool_id:', $tool_id);
		$QselectTool->execute();
		if ($QselectTool->query_handler===false) {
			$this->__initDB();
			$QselectTool->execute();
		}
		if ($QselectTool->numberOfRows()==1) {
			$QselectTool->next();
			$this->data[$name][$tool_id]=$QselectTool->result;
		}
		return $this->data[$name][$tool_id];
	}

	/*
	 * Liefert den passenden Array fuer osW_Form->drawSelectField mit module als index
	 */
	public function getToolsSelectArray() {
		if (isset($this->data['toolsarray'])) {
			return $this->data['toolsarray'];
		}
		$this->data['toolsarray']=array();
		foreach ($this->getTools() as $tool => $name) {
			if ($tool!=vOut('vis2_login_module')) {
				$this->data['toolsarray'][]=array(
					'text'=>$name, 'value'=>$tool,
				);
			}
		}
		return $this->data['toolsarray'];
	}

	/*
	 * Liefert den passenden Array fuer osW_Form->drawSelectField mit module als index
	*/
	public function getToolsSelectLogonArray() {
		$name='toollogonsarray';
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}
		$this->data[$name]=array();
		foreach ($this->getToolsWithDetails() as $tool_details) {
			if (($tool_details['tool_name_intern']!=vOut('vis2_login_module'))&&($tool_details['tool_hide_logon']==0)) {
				$this->data[$name][]=array(
					'value'=>$tool_details['tool_name_intern'], 'text'=>$tool_details['tool_name'],
				);
			}
		}
		return $this->data[$name];
	}

	/*
	 * Liefert den passenden Array fuer osW_Form->drawSelectField mit module als index nach Rechten
	*/
	public function getToolsSelectArraybyPermission() {
		if (isset($this->data['toolsarraypermission'])) {
			return $this->data['toolsarraypermission'];
		}
		$this->data['toolsarraypermission']=array();
		$QselectTools = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_tool: AS t INNER JOIN :table_vis2_user_tool: AS u ON (u.tool_id=t.tool_id) WHERE t.tool_status=:tool_status: AND u.user_id=:user_id: ORDER BY t.tool_name ASC');
		$QselectTools->bindTable(':table_vis2_tool:', 'vis2_tool');
		$QselectTools->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
		$QselectTools->bindInt(':tool_status:', 1);
		$QselectTools->bindInt(':user_id:', osW_VIS2_User::getInstance()->getId());
		$QselectTools->execute();
		if ($QselectTools->query_handler===false) {
			$this->__initDB();
			$QselectTools->execute();
		}
		if ($QselectTools->numberOfRows()>0) {
			while ($QselectTools->next()) {
				$this->data['toolsarraypermission'][$QselectTools->value('tool_name_intern')]=$QselectTools->value('tool_name');
			}
		}
		return $this->data['toolsarraypermission'];
	}

	/*
	 * Liefert den passenden Array fuer osW_Form->drawSelectField mit module als index nach Rechten
	*/
	public function getToolsSelectLogonArraybyPermission() {
		$name='toolslogonarraypermission';
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}
		$this->data[$name]=array();
		$QselectTools = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_tool: AS t INNER JOIN :table_vis2_user_tool: AS u ON (u.tool_id=t.tool_id) WHERE t.tool_status=:tool_status: AND u.user_id=:user_id: AND t.tool_hide_navigation=:tool_hide_navigation: ORDER BY t.tool_name ASC');
		$QselectTools->bindTable(':table_vis2_tool:', 'vis2_tool');
		$QselectTools->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
		$QselectTools->bindInt(':tool_status:', 1);
		$QselectTools->bindInt(':user_id:', osW_VIS2_User::getInstance()->getId());
		$QselectTools->bindInt(':tool_hide_navigation:', 0);
		$QselectTools->execute();
		if ($QselectTools->query_handler===false) {
			$this->__initDB();
			$QselectTools->execute();
		}
		if ($QselectTools->numberOfRows()>0) {
			while ($QselectTools->next()) {
				$this->data[$name][$QselectTools->value('tool_name_intern')]=$QselectTools->value('tool_name');
			}
		}
		return $this->data[$name];
	}

	/*
	 * Liefert den passenden Array fuer osW_Form->drawSelectField mit id als index
	 */
	public function getToolsListArray() {
		if (isset($this->data['toolslistarray'])) {
			return $this->data['toolslistarray'];
		}
		$this->data['toolslistarray']=array();
		$QselectTools = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_tool: WHERE tool_status=:tool_status: ORDER BY tool_name ASC');
		$QselectTools->bindTable(':table_vis2_tool:', 'vis2_tool');
		$QselectTools->bindInt(':tool_status:', 1);
		$QselectTools->execute();
		if ($QselectTools->query_handler===false) {
			$this->__initDB();
			$QselectTools->execute();
		}
		if ($QselectTools->numberOfRows()>0) {
			while ($QselectTools->next()) {
				$this->data['toolslistarray'][$QselectTools->value('tool_id')]=$QselectTools->value('tool_name');
			}
		}
		return $this->data['toolslistarray'];
	}

	/*
	 * Liefert den passenden Array fuer osW_Form->drawSelectField mit id als index nach Rechten
	*/
	public function getToolsListArraybyPermission() {
		if (isset($this->data['toolslistarraypermission'])) {
			return $this->data['toolslistarraypermission'];
		}
		$this->data['toolslistarraypermission']=array();
		$QselectTools = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_tool: AS t INNER JOIN :table_vis2_user_tool: AS u ON (u.tool_id=t.tool_id) WHERE t.tool_status=:tool_status: AND u.user_id=:user_id: ORDER BY t.tool_name ASC');
		$QselectTools->bindTable(':table_vis2_tool:', 'vis2_tool');
		$QselectTools->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
		$QselectTools->bindInt(':tool_status:', 1);
		$QselectTools->bindInt(':user_id:', osW_VIS2_User::getInstance()->getId());
		$QselectTools->execute();
		if ($QselectTools->query_handler===false) {
			$this->__initDB();
			$QselectTools->execute();
		}
		if ($QselectTools->numberOfRows()>0) {
			while ($QselectTools->next()) {
				$this->data['toolslistarraypermission'][$QselectTools->value('tool_id')]=$QselectTools->value('tool_name');
			}
		}
		return $this->data['toolslistarraypermission'];
	}

	/*
	 * Ueberprueft ob es sich um ein gueltiges Modul handelt
	 */
	private function validateTool($tool) {
		if (!isset($this->data['tools'])) {
			$this->getTools();
		}
		if (isset($this->data['tools'][$tool])) {
			return true;
		}
		return false;
	}

	/*
	 * Ermittelt das aktuelle Tool
	 */
	public function setTool($tool) {
		$tool=strtolower($tool);
		if ($this->validateTool($tool)===true) {
			$this->tool=$tool;
			osW_Session::getInstance()->set('vis2_tool', $this->tool);
			return true;
		}
		return false;
	}

	/*
	 * Liefert das aktuelle Tool
	 */
	public function getTool() {
		return $this->tool;
	}

	/*
	 * Liefert die aktuelle ToolID
	*/
	public function getToolID() {
		return $this->getToolIdByModule();
	}

	/*
	 * Liefert den aktuelle ToolName
	*/
	public function getToolName() {
		return $this->getToolNameByModule();
	}

	/*
	 * Liefert das aktuelle Tool-ID
	*/
	public function getToolIdByModule($tool='') {
		if ($tool=='') {
			$tool=$this->getTool();
		}

		if (!isset($this->data['toolsarray_module_id'])) {
			$this->data['toolsarray_module_id']=array();
			$QselectTools = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_tool: WHERE tool_status=:tool_status: ORDER BY tool_name ASC');
			$QselectTools->bindTable(':table_vis2_tool:', 'vis2_tool');
			$QselectTools->bindInt(':tool_status:', 1);
			$QselectTools->execute();
			if ($QselectTools->query_handler===false) {
				$this->__initDB();
				$QselectTools->execute();
			}
			if ($QselectTools->numberOfRows()>0) {
				while ($QselectTools->next()) {
					$this->data['toolsarray_module_id'][$QselectTools->value('tool_name_intern')]=$QselectTools->value('tool_id');
				}
			}
		}
		if (isset($this->data['toolsarray_module_id'][$tool])) {
			return $this->data['toolsarray_module_id'][$tool];
		}
		return 0;
	}

	/*
	 * Liefert den aktuelle ToolName
	*/
	public function getToolNameByModule($tool='') {
		if ($tool=='') {
			$tool=$this->getTool();
		}

		if (!isset($this->data['toolsarray_module_name'])) {
			$this->data['toolsarray_module_name']=array();
			$QselectTools = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_tool: WHERE tool_status=:tool_status: ORDER BY tool_name ASC');
			$QselectTools->bindTable(':table_vis2_tool:', 'vis2_tool');
			$QselectTools->bindInt(':tool_status:', 1);
			$QselectTools->execute();
			if ($QselectTools->query_handler===false) {
				$this->__initDB();
				$QselectTools->execute();
			}
			if ($QselectTools->numberOfRows()>0) {
				while ($QselectTools->next()) {
					$this->data['toolsarray_module_name'][$QselectTools->value('tool_name_intern')]=$QselectTools->value('tool_name');
				}
			}
		}
		if (isset($this->data['toolsarray_module_name'][$tool])) {
			return $this->data['toolsarray_module_name'][$tool];
		}
		return 0;
	}

	public function checkToolPermissionByEMail($tool, $email) {
		if (osW_VIS2_User::getInstance()->getIdByEMail($email)!==false) {
			return $this->checkToolPermissionbyID($tool, osW_VIS2_User::getInstance()->getIdByEMail($email));
		}
		return false;
	}

	public function checkToolPermissionByID($tool, $user_id) {
		if ($tool==vOut('vis2_chtool_module')) {
			return true;
		}
		$QselectTool = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_tool: WHERE tool_name_intern=:tool_name_intern: AND tool_status=:tool_status:');
		$QselectTool->bindTable(':table_vis2_tool:', 'vis2_tool');
		$QselectTool->bindValue(':tool_name_intern:', $tool);
		$QselectTool->bindInt(':tool_status:', 1);
		$QselectTool->execute();
		if ($QselectTool->query_handler===false) {
			$this->__initDB();
			$QselectTool->execute();
		}
		if ($QselectTool->numberOfRows()==1) {
			$QloadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis2_user_tool: WHERE user_id=:user_id: AND tool_id=:tool_id:');
			$QloadData->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
			$QloadData->bindInt(':user_id:', $user_id);
			$QloadData->bindInt(':tool_id:', $this->getToolIdByModule($tool));
			$QloadData->execute();
			if ($QloadData->numberOfRows()==1) {
				return true;
			}
		}
		return false;
	}

	public function getUsernameById($user_id) {
		$name=__FUNCTION__;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$Quser = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_user: WHERE 1');
			$Quser->bindTable(':table_vis2_user:', 'vis2_user');
			$Quser->execute();
			if ($Quser->query_handler===false) {
				$this->__initDB();
				$Quser->execute();
			}
			if ($Quser->numberOfRows()>0) {
				while ($Quser->next()) {
					$this->data[$name][$Quser->value('user_id')]=$Quser->value('user_lastname').' '.$Quser->value('user_firstname');
				}
			}
		}
		if (isset($this->data[$name][$user_id])) {
			return $this->data[$name][$user_id];
		}
		return '';
	}

	/**
	 *
	 * @return osW_VIS2
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>