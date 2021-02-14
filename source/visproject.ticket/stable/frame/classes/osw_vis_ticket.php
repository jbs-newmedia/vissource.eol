<?php

class osW_VIS_Ticket extends osW_Object {

	/*** PROPERTIES ***/

	private $data=array();

	/*** METHODS CORE ***/


	public function __construct() {
		parent::__construct(1, 3);
	}

	public function __destruct() {
		parent::__destruct();
	}

	/*** METHODS ***/

	public function getProjects($key='project_id', $value='project_name') {
		$name=__FUNCTION__.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_project: WHERE project_status=:project_status: ORDER BY project_sortorder ASC, project_name ASC');
			$QgetData->bindTable(':table_vis_ticket_project:', 'vis_ticket_project');
			$QgetData->bindInt(':project_status:', 1);
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];
				}
			}
		}
		return $this->data[$name];
	}

	public function getProjectsByUserId($user_id=0, $key='project_id', $value='project_name') {
		if ($user_id==0) {
			$user_id=osW_VIS_User::getInstance()->getId();
		}

		$name=__FUNCTION__.'_'.$user_id.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();

			if ((vOut('vis_ticket_permission_modus')=='both')||(vOut('vis_ticket_permission_modus')=='group')) {
				$QgetData = osW_Database::getInstance()->query('SELECT p.* FROM :table_vis_ticket_project: AS p INNER JOIN :table_vis_ticket_pemgroup_project: AS up ON (up.project_id=p.project_id) INNER JOIN :table_vis_ticket_user_pemgroup: AS tup ON (tup.group_id=up.group_id AND tup.user_id=:user_id:) WHERE project_parent_id=:project_parent_id: AND p.project_status=:project_status: ORDER BY project_sortorder ASC, p.project_name ASC');
				$QgetData->bindTable(':table_vis_ticket_project:', 'vis_ticket_project');
				$QgetData->bindTable(':table_vis_ticket_pemgroup_project:', 'vis_ticket_pemgroup_project');
				$QgetData->bindTable(':table_vis_ticket_user_pemgroup:', 'vis_ticket_user_pemgroup');
				$QgetData->bindInt(':project_parent_id:', 0);
				$QgetData->bindInt(':user_id:', $user_id);
				$QgetData->bindInt(':project_status:', 1);
				$QgetData->execute();
				if ($QgetData->numberOfRows()>0) {
					$this->data[$name]=array();
					while ($QgetData->next()) {
						if ($value=='array') {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result;
						} else {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];
						}

						$QgetData2 = osW_Database::getInstance()->query('SELECT p.* FROM :table_vis_ticket_project: AS p INNER JOIN :table_vis_ticket_pemgroup_project: AS up ON (up.project_id=p.project_id) INNER JOIN :table_vis_ticket_user_pemgroup: AS tup ON (tup.group_id=up.group_id AND tup.user_id=:user_id:) WHERE project_parent_id=:project_parent_id: AND p.project_status=:project_status: ORDER BY project_sortorder ASC, p.project_name ASC');
						$QgetData2->bindTable(':table_vis_ticket_project:', 'vis_ticket_project');
						$QgetData2->bindTable(':table_vis_ticket_pemgroup_project:', 'vis_ticket_pemgroup_project');
						$QgetData2->bindTable(':table_vis_ticket_user_pemgroup:', 'vis_ticket_user_pemgroup');
						$QgetData2->bindInt(':project_parent_id:', $QgetData->result['project_id']);
						$QgetData2->bindInt(':user_id:', $user_id);
						$QgetData2->bindInt(':project_status:', 1);
						$QgetData2->execute();
						if ($QgetData2->numberOfRows()>0) {
							while ($QgetData2->next()) {
								if ($value=='array') {
									$this->data[$name][$QgetData2->result[$key]]=$QgetData2->result;
								} else {
									if ($value=='project_id') {
										$this->data[$name][$QgetData2->result[$key]]=$QgetData2->result[$value];
									} else {
										$this->data[$name][$QgetData2->result[$key]]=$QgetData->result['project_name'].': '.$QgetData2->result[$value];
									}
								}
							}
						}
					}
				}
			}

			if ((vOut('vis_ticket_permission_modus')=='both')||(vOut('vis_ticket_permission_modus')=='user')) {
				$QgetData = osW_Database::getInstance()->query('SELECT p.* FROM :table_vis_ticket_project: AS p INNER JOIN :table_vis_ticket_user_project: AS up ON (up.project_id=p.project_id AND up.user_id=:user_id:) WHERE project_parent_id=:project_parent_id: AND p.project_status=:project_status: ORDER BY project_sortorder ASC, p.project_name ASC');
				$QgetData->bindTable(':table_vis_ticket_project:', 'vis_ticket_project');
				$QgetData->bindTable(':table_vis_ticket_user_project:', 'vis_ticket_user_project');
				$QgetData->bindInt(':project_parent_id:', 0);
				$QgetData->bindInt(':user_id:', $user_id);
				$QgetData->bindInt(':project_status:', 1);
				$QgetData->execute();
				if ($QgetData->numberOfRows()>0) {
					while ($QgetData->next()) {
						if ($value=='array') {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result;
						} else {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];
						}

						$QgetData2 = osW_Database::getInstance()->query('SELECT p.* FROM :table_vis_ticket_project: AS p INNER JOIN :table_vis_ticket_user_project: AS up ON (up.project_id=p.project_id AND up.user_id=:user_id:) WHERE project_parent_id=:project_parent_id: AND p.project_status=:project_status: ORDER BY project_sortorder ASC, p.project_name ASC');
						$QgetData2->bindTable(':table_vis_ticket_project:', 'vis_ticket_project');
						$QgetData2->bindTable(':table_vis_ticket_user_project:', 'vis_ticket_user_project');
						$QgetData2->bindInt(':project_parent_id:', $QgetData->result['project_id']);
						$QgetData2->bindInt(':user_id:', $user_id);
						$QgetData2->bindInt(':project_status:', 1);
						$QgetData2->execute();
						if ($QgetData2->numberOfRows()>0) {
							while ($QgetData2->next()) {
								if ($value=='array') {
									$this->data[$name][$QgetData2->result[$key]]=$QgetData2->result;
								} else {
									if ($value=='project_id') {
										$this->data[$name][$QgetData2->result[$key]]=$QgetData2->result[$value];
									} else {
										$this->data[$name][$QgetData2->result[$key]]=$QgetData->result['project_name'].': '.$QgetData2->result[$value];
									}
								}
							}
						}
					}
				}
			}
		}

		return $this->data[$name];
	}

	public function getProjectData($project_id, $key='') {
		$name=__FUNCTION__;
		if (!isset($this->data[$name][$project_id])) {
			$this->data[$name][$project_id]=array();
			$QselectData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_project: WHERE project_id=:project_id:');
			$QselectData->bindTable(':table_vis_ticket_project:', 'vis_ticket_project');
			$QselectData->bindInt(':project_id:', $project_id);
			$QselectData->execute();
			if ($QselectData->numberOfRows()==1) {
				$QselectData->next();
				$QselectData->result['project_name_full']='';
				if ($QselectData->result['project_parent_id']!=0) {
					$QselectParentData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_project: WHERE project_id=:project_id:');
					$QselectParentData->bindTable(':table_vis_ticket_project:', 'vis_ticket_project');
					$QselectParentData->bindInt(':project_id:', $QselectData->result['project_parent_id']);
					$QselectParentData->execute();
					if ($QselectParentData->numberOfRows()>0) {
						$QselectParentData->next();
						$QselectData->result['project_name_full'].=$QselectParentData->result['project_name'].': ';
					}
				}
				$QselectData->result['project_name_full'].=$QselectData->result['project_name'];
				$this->data[$name][$project_id]=$QselectData->result;
			}
		}
		if (($key!='')&&(isset($this->data[$name][$project_id][$key]))) {
			return $this->data[$name][$project_id][$key];
		}
		return $this->data[$name][$project_id];
	}

	public function getProjectsStructure($project_parent_id=0, $key='project_id', $value='array') {
		$name=__FUNCTION__.'_'.$project_parent_id.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_project: WHERE project_parent_id=:project_parent_id: ORDER BY project_sortorder ASC, project_name ASC');
			$QgetData->bindTable(':table_vis_ticket_project:', 'vis_ticket_project');
			$QgetData->bindInt(':project_parent_id:', $project_parent_id);
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					if ($value=='array') {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->result;
					} else {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];
					}
				}
			}
		}
		return $this->data[$name];
	}

	public function getGroups($key='group_id', $value='group_name') {
		$name=__FUNCTION__.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_group: WHERE group_status=:group_status: ORDER BY group_name ASC');
			$QgetData->bindTable(':table_vis_ticket_group:', 'vis_ticket_group');
			$QgetData->bindInt(':group_status:', 1);
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];
				}
			}
		}
		return $this->data[$name];
	}

	public function getGroups2Users($key='group_id', $value='group_name') {
		$name=__FUNCTION__.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_group: WHERE group_status=:group_status: OR group_isdefault=:group_isdefault: ORDER BY group_name ASC');
			$QgetData->bindTable(':table_vis_ticket_group:', 'vis_ticket_group');
			$QgetData->bindInt(':group_status:', 1);
			$QgetData->bindInt(':group_isdefault:', 1);
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];
				}
			}
		}
		return $this->data[$name];
	}

	public function getPemGroups2Users($key='group_id', $value='group_name') {
		$name=__FUNCTION__.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_pemgroup: WHERE group_status=:group_status: OR group_isdefault=:group_isdefault: ORDER BY group_name ASC');
			$QgetData->bindTable(':table_vis_ticket_pemgroup:', 'vis_ticket_pemgroup');
			$QgetData->bindInt(':group_status:', 1);
			$QgetData->bindInt(':group_isdefault:', 1);
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];
				}
			}
		}
		return $this->data[$name];
	}

	public function getProjectGroups($project_id, $key='group_id', $value='group_name') {
		$name=__FUNCTION__.'_'.$project_id.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT g.* FROM :table_vis_ticket_group: AS g LEFT JOIN :table_vis_ticket_group_project: AS p ON (p.group_id=g.group_id) WHERE g.group_status=:group_status: AND p.project_id=:project_id: ORDER BY g.group_name ASC');
			$QgetData->bindTable(':table_vis_ticket_group:', 'vis_ticket_group');
			$QgetData->bindTable(':table_vis_ticket_group_project:', 'vis_ticket_group_project');
			$QgetData->bindInt(':group_status:', 1);
			$QgetData->bindInt(':project_id:', $project_id);
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];
				}
			}
		}
		return $this->data[$name];
	}

	public function getImportance($key='importance_internal_id', $value='importance_description') {
		$name=__FUNCTION__.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_importance: WHERE 1 ORDER BY importance_internal_id DESC');
			$QgetData->bindTable(':table_vis_ticket_importance:', 'vis_ticket_importance');
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];
				}
			}
		}
		return $this->data[$name];
	}

	public function getImportanceData($importance_internal_id, $key='') {
		$name=__FUNCTION__;
		if (!isset($this->data[$name][$importance_internal_id])) {
			$this->data[$name][$importance_internal_id]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_importance: WHERE importance_internal_id=:importance_internal_id:');
			$QgetData->bindTable(':table_vis_ticket_importance:', 'vis_ticket_importance');
			$QgetData->bindInt(':importance_internal_id:', $importance_internal_id);
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					$this->data[$name][$importance_internal_id]=$QgetData->result;
				}
			}
		}
		if (($key!='')&&(isset($this->data[$name][$importance_internal_id][$key]))) {
			return $this->data[$name][$importance_internal_id][$key];
		}
		return $this->data[$name][$importance_internal_id];
	}

	public function getStatus($checkopen=true, $checkclosed=true, $checkwaiting=true, $key='status_internal_id', $value='status_description') {
		$name=__FUNCTION__.'_'.$checkclosed.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_status: WHERE 1 ORDER BY status_sortorder ASC');
			$QgetData->bindTable(':table_vis_ticket_status:', 'vis_ticket_status');
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];
					if (($checkopen==true)&&($QgetData->result['status_flag']==1)) {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value].=' (Ticket offen)';
					}
					if (($checkwaiting==true)&&($QgetData->result['status_flag']==5)) {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value].=' (Ticket wartend)';
					}
					if (($checkclosed==true)&&($QgetData->result['status_flag']==9)) {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value].=' (Ticket geschlossen)';
					}
				}
			}
		}
		return $this->data[$name];
	}

	public function getStatusDataOpen($check=true, $key='status_internal_id', $value='status_description') {
		return $this->getStatusCore('10000', $check, $key, $value);
	}

	public function getStatusDataWaiting($check=true, $key='status_internal_id', $value='status_description') {
		return $this->getStatusCore('01000', $check, $key, $value);
	}

	public function getStatusDataAssigned($check=true, $key='status_internal_id', $value='status_description') {
		return $this->getStatusCore('00100', $check, $key, $value);
	}

	public function getStatusDataInWork($check=true, $key='status_internal_id', $value='status_description') {
		return $this->getStatusCore('00010', $check, $key, $value);
	}

	public function getStatusDataClosed($check=true, $key='status_internal_id', $value='status_description') {
		return $this->getStatusCore('00001', $check, $key, $value);
	}

	/*
	 * $statusmask
	 * 0 = open
	 * 1 = waiting
	 * 2 = assigned
	 * 3 = inwork
	 * 4 = closed
	 */
	public function getStatusCore($statusmask='11111', $check=true, $key='status_internal_id', $value='status_description') {
		$name=__FUNCTION__.'_'.$statusmask.'_'.$check.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$statusmask=str_split($statusmask);
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_status: WHERE 1 ORDER BY status_sortorder ASC');
			$QgetData->bindTable(':table_vis_ticket_status:', 'vis_ticket_status');
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					if ((($statusmask[0]=='1')&&($QgetData->result['status_flag']==1)) ||
						(($statusmask[1]=='1')&&($QgetData->result['status_flag']==5)) ||
						(($statusmask[2]=='1')&&($QgetData->result['status_flag']==0)) ||
						(($statusmask[3]=='1')&&($QgetData->result['status_flag']==7)) ||
						(($statusmask[4]=='1')&&($QgetData->result['status_flag']==9))) {

						$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];

						if (($check==true)&&($QgetData->result['status_flag']==1)) {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value].=' (Ticket offen)';
						}
						if (($check==true)&&($QgetData->result['status_flag']==5)) {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value].=' (Ticket wartend)';
						}
						if (($check==true)&&($QgetData->result['status_flag']==0)) {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value].=' (Ticket zugewiesen)';
						}
						if (($check==true)&&($QgetData->result['status_flag']==7)) {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value].=' (Ticket in Bearbeitung)';
						}
						if (($check==true)&&($QgetData->result['status_flag']==9)) {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value].=' (Ticket geschlossen)';
						}
					}
				}
			}
		}

		if ((!isset($this->data[$name]))||($this->data[$name]==array())) {
			return array(-1);
		}

		return $this->data[$name];
	}


	/*
	 * @deprecated
	 */
	public function getStatusData($open=true, $closed=true, $waiting=true, $rest=true, $checkopen=true, $checkclosed=true, $checkwaiting=true, $key='status_internal_id', $value='status_description') {
		$name=__FUNCTION__.'_'.$open.'_'.$closed.'_'.$waiting.'_'.$rest.'_'.$checkopen.'_'.$checkclosed.'_'.$checkwaiting.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_status: WHERE 1 ORDER BY status_sortorder ASC');
			$QgetData->bindTable(':table_vis_ticket_status:', 'vis_ticket_status');
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					if ((($open==true)&&($QgetData->result['status_flag']==1))||(($waiting==true)&&($QgetData->result['status_flag']==5))||(($closed==true)&&($QgetData->result['status_flag']==9))||(($rest==true)&&($QgetData->result['status_flag']==0))) {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value];
						if (($checkopen==true)&&($QgetData->result['status_flag']==1)) {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value].=' (Ticket offen)';
						}
						if (($checkwaiting==true)&&($QgetData->result['status_flag']==5)) {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value].=' (Ticket wartend)';
						}
						if (($checkclosed==true)&&($QgetData->result['status_flag']==9)) {
							$this->data[$name][$QgetData->result[$key]]=$QgetData->result[$value].=' (Ticket geschlossen)';
						}
					}
				}
			}
		}
		return $this->data[$name];
	}

	public function getUsers($key='user_id', $value='user_fullname') {
		$name=__FUNCTION__.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT u.* FROM :table_vis_user: AS u INNER JOIN :table_vis_ticket_user_group: AS g ON (g.user_id=u.user_id) WHERE u.user_status=:user_status: ORDER BY u.user_lastname ASC, u.user_firstname ASC');
			$QgetData->bindTable(':table_vis_user:', 'vis_user');
			$QgetData->bindTable(':table_vis_ticket_user_group:', 'vis_ticket_user_group');
			$QgetData->bindInt(':user_status:', 1);
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					if ($value=='user_fullname') {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->value('user_lastname').' '.$QgetData->value('user_firstname');
					} elseif ($value=='user_fullnamefl') {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->value('user_firstname').' '.$QgetData->value('user_lastname');
					} else {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->value($value);
					}
				}
			}
		}
		return $this->data[$name];
	}

	public function getGroupUsers($group_id, $key='user_id', $value='user_fullname') {
		$name=__FUNCTION__.'_'.$group_id.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT u.* FROM :table_vis_user: AS u LEFT JOIN :table_vis_ticket_user_group: AS g ON (g.user_id=u.user_id) WHERE u.user_status=:user_status: AND g.group_id=:group_id: ORDER BY u.user_lastname ASC, u.user_firstname ASC');
			$QgetData->bindTable(':table_vis_user:', 'vis_user');
			$QgetData->bindTable(':table_vis_ticket_user_group:', 'vis_ticket_user_group');
			$QgetData->bindInt(':user_status:', 1);
			$QgetData->bindInt(':group_id:', $group_id);
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					if ($value=='user_fullname') {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->value('user_lastname').' '.$QgetData->value('user_firstname');
					} elseif ($value=='user_fullnamefl') {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->value('user_firstname').' '.$QgetData->value('user_lastname');
					} else {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->value($value);
					}
				}
			}
		}
		return $this->data[$name];
	}

	public function getAllUsers($key='user_id', $value='user_fullname') {
		$name=__FUNCTION__.'_'.$key.'_'.$value;
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT u.* FROM :table_vis_ticket_user_group: AS ug INNER JOIN :table_vis_user: AS u ON (u.user_id=ug.user_id) WHERE 1 ORDER BY u.user_lastname ASC, u.user_firstname ASC');
			$QgetData->bindTable(':table_vis_user:', 'vis_user');
			$QgetData->bindTable(':table_vis_ticket_user_group:', 'vis_ticket_user_group');
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					if ($value=='user_fullname') {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->value('user_lastname').' '.$QgetData->value('user_firstname');
					} elseif ($value=='user_fullnamefl') {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->value('user_firstname').' '.$QgetData->value('user_lastname');
					} else {
						$this->data[$name][$QgetData->result[$key]]=$QgetData->value($value);
					}
				}
			}
		}
		return $this->data[$name];
	}

	public function getGroupData($group_id, $key='') {
		$name=__FUNCTION__;
		if (!isset($this->data[$name][$group_id])) {
			$this->data[$name][$group_id]=array();
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_group: WHERE group_id=:group_id:');
			$QgetData->bindTable(':table_vis_ticket_group:', 'vis_ticket_group');
			$QgetData->bindInt(':group_id:', $group_id);
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					$this->data[$name][$group_id]=$QgetData->result;
				}
			}
		}
		if (($key!='')&&(isset($this->data[$name][$group_id][$key]))) {
			return $this->data[$name][$group_id][$key];
		}
		return $this->data[$name][$group_id];
	}

	public function getTicketDataByTicketId($ticket_id) {
		$name=__FUNCTION__;
		if (!isset($this->data[$name][$ticket_id])) {
			$this->data[$name][$ticket_id]=array();
			$QgetTnumber = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket: WHERE ticket_id=:ticket_id:');
			$QgetTnumber->bindTable(':table_vis_ticket:', 'vis_ticket');
			$QgetTnumber->bindInt(':ticket_id:', $ticket_id);
			$QgetTnumber->execute();
			if ($QgetTnumber->numberOfRows()==1) {
				$QgetTnumber->next();
				$this->data[$name][$ticket_id]=$QgetTnumber->result;
			}
		}
		return $this->data[$name][$ticket_id];
	}

	public function clearTicketDataByTicketId($ticket_id) {
		$name='getTicketDataByTicketId';
		if (isset($this->data[$name][$ticket_id])) {
			unset($this->data[$name][$ticket_id]);
		}
		return true;
	}

	public function getNoticeByTicketId($ticket_id, $elements_per_page=10) {
		$name=__FUNCTION__;
		if (!isset($this->data[$name][$ticket_id])) {
			$this->data[$name][$ticket_id]=array();
			$QgetTnumber = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_notice: WHERE ticket_id=:ticket_id: ORDER BY notice_create_time ASC');
			$QgetTnumber->bindTable(':table_vis_ticket_notice:', 'vis_ticket_notice');
			$QgetTnumber->bindInt(':ticket_id:', $ticket_id);
			$QgetTnumber->setPrimaryKey('notice_id');
			$QgetTnumber->bindLimit($elements_per_page, 0, 5, 'ddm_page');
			$QgetTnumber->execute();
			if ($QgetTnumber->numberOfRows()>0) {
				$this->data[$name][$ticket_id]['data']=array();
				while ($QgetTnumber->next()) {
					$QgetTnumber->result['notice_data']=array();
					for($i=1;$i<=5;$i++) {
						if ($QgetTnumber->result['notice_data'.$i]!='') {
							$QgetTnumber->result['notice_data'][]='<a target="blank" href="'.osW_Template::getInstance()->buildhrefLink(vOut('frame_current_module'), 'view=ticket&vistool=ticket&vispage=ticket_all&ticket_id='.$ticket_id.'&notice_id='.$QgetTnumber->result['notice_id'].'&download='.$i, false).'">'.$QgetTnumber->result['notice_data'.$i.'_name'].'</a>';
						}
					}
					$this->data[$name][$ticket_id]['data'][]=$QgetTnumber->result;
				}
				$this->data[$name][$ticket_id]['limitrows']=$QgetTnumber->limitrows;
			}
		}
		return $this->data[$name][$ticket_id];
	}

	public function getNoticeById($notice_id) {
		$name=__FUNCTION__;
		if (!isset($this->data[$name][$notice_id])) {
			$this->data[$name][$notice_id]=array();
			$QgetTnumber = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_notice: WHERE notice_id=:notice_id:');
			$QgetTnumber->bindTable(':table_vis_ticket_notice:', 'vis_ticket_notice');
			$QgetTnumber->bindInt(':notice_id:', $notice_id);
			$QgetTnumber->execute();
			if ($QgetTnumber->numberOfRows()==1) {
				$QgetTnumber->next();
				$this->data[$name][$notice_id]=$QgetTnumber->result;
			}
		}
		return $this->data[$name][$notice_id];
	}

	public function clearNoticeById($notice_id) {
		$name='getNoticeById';
		if (isset($this->data[$name][$notice_id])) {
			unset($this->data[$name][$notice_id]);
		}
		return true;
	}

	public function getUserMail($user_id) {
		$name=__FUNCTION__;
		if (!isset($this->data[$name][$user_id])) {
			$this->data[$name][$user_id]=array();
			$QgetUser = osW_Database::getInstance()->query('SELECT user_email FROM :table_vis_user: WHERE user_id=:user_id:');
			$QgetUser->bindTable(':table_vis_user:', 'vis_user');
			$QgetUser->bindInt(':user_id:', $user_id);
			$QgetUser->execute();
			if ($QgetUser->numberOfRows()>0) {
				while ($QgetUser->next()) {
					$this->data[$name][$user_id]=$QgetUser->value('user_email');
				}
			}
		}
		return $this->data[$name][$user_id];
	}

	public function getGroupMails($group_id) {
		$name=__FUNCTION__;
		if (!isset($this->data[$name][$group_id])) {
			$this->data[$name][$group_id]=array();
			$QgetUser = osW_Database::getInstance()->query('SELECT u.user_id, u.user_email FROM :table_vis_user: u LEFT JOIN :table_vis_ticket_user_group : ug ON u.user_id=ug.user_id WHERE ug.group_id=:group_id:');
			$QgetUser->bindTable(':table_vis_user:', 'vis_user');
			$QgetUser->bindTable(':table_vis_ticket_user_group :', 'vis_ticket_user_group ');
			$QgetUser->bindInt(':group_id:', $group_id);
			$QgetUser->execute();
			if ($QgetUser->numberOfRows()>0) {
				while ($QgetUser->next()) {
					$this->data[$name][$group_id][$QgetUser->value('user_id')]=$QgetUser->value('user_email');
				}
			}
		}
		return $this->data[$name][$group_id];
	}

	public function getDefaultMails($project_id) {
		$name=__FUNCTION__;
		if (!isset($this->data[$name][$project_id])) {
			$this->data[$name][$project_id]=array();
			$QgetUser = osW_Database::getInstance()->query('SELECT u.user_id, u.user_email FROM :table_vis_user: u LEFT JOIN :table_vis_ticket_user_group : ug ON (u.user_id=ug.user_id) LEFT JOIN :table_vis_ticket_group: g ON (g.group_id=ug.group_id) LEFT JOIN :table_vis_ticket_group_project: gp ON (gp.group_id=g.group_id) WHERE gp.project_id=:project_id: AND g.group_isdefault=:group_isdefault:');
			$QgetUser->bindTable(':table_vis_user:', 'vis_user');
			$QgetUser->bindTable(':table_vis_ticket_user_group :', 'vis_ticket_user_group ');
			$QgetUser->bindTable(':table_vis_ticket_group:', 'vis_ticket_group');
			$QgetUser->bindTable(':table_vis_ticket_group_project:', 'vis_ticket_group_project');
			$QgetUser->bindTable(':table_vis_ticket_project:', 'vis_ticket_project');
			$QgetUser->bindInt(':project_id:', $project_id);
			$QgetUser->bindInt(':group_isdefault:', 1);
			$QgetUser->execute();
			if ($QgetUser->numberOfRows()>0) {
				while ($QgetUser->next()) {
					$this->data[$name][$project_id][$QgetUser->value('user_id')]=$QgetUser->value('user_email');
				}
			}
		}
		return $this->data[$name][$project_id];
	}

	public function getGroupIdsByUserId($user_id=0) {
		if ($user_id==0) {
			$user_id=osW_VIS_User::getInstance()->getId();
		}
		if (!isset($this->data['group_ids'][$user_id])) {
			$this->data['group_ids'][$user_id]=array();

			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_user_group: WHERE user_id=:user_id:');
			$QgetData->bindTable(':table_vis_ticket_user_group:', 'vis_ticket_user_group');
			$QgetData->bindInt(':user_id:', $user_id);
			$QgetData->execute();
			if ($QgetData->numberOfRows()>0) {
				while ($QgetData->next()) {
					$this->data['group_ids'][$user_id][]=$QgetData->result['group_id'];
				}
			}
		}
		return $this->data['group_ids'][$user_id];
	}

	public function setTicketNotification($ticket_id, $user_id=0) {
		if ($user_id==0) {
			$user_id=osW_VIS_User::getInstance()->getId();
		}

		if ($this->getTicketDataByTicketId($ticket_id)==array()) {
			return false;
		}

		$time=time();

		$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_notify_ticket: WHERE ticket_id=:ticket_id: AND user_id=:user_id:');
		$QgetData->bindTable(':table_vis_ticket_notify_ticket:', 'vis_ticket_notify_ticket');
		$QgetData->bindInt(':ticket_id:', $ticket_id);
		$QgetData->bindInt(':user_id:', $user_id);
		$QgetData->execute();
		if ($QgetData->numberOfRows()>0) {
			return true;
		} else {
			$QinsertData = osW_Database::getInstance()->query('INSERT INTO :table_vis_ticket_notify_ticket: (ticket_id, user_id, notiz_create_user_id, notiz_create_time, notiz_update_user_id, notiz_update_time) VALUES (:ticket_id:, :user_id:, :notiz_create_user_id:, :notiz_create_time:, :notiz_update_user_id:, :notiz_update_time:)');
			$QinsertData->bindTable(':table_vis_ticket_notify_ticket:', 'vis_ticket_notify_ticket');
			$QinsertData->bindInt(':ticket_id:', $ticket_id);
			$QinsertData->bindInt(':user_id:', $user_id);
			$QinsertData->bindInt(':notiz_create_user_id:', $user_id);
			$QinsertData->bindInt(':notiz_create_time:', $time);
			$QinsertData->bindInt(':notiz_update_user_id:', $user_id);
			$QinsertData->bindInt(':notiz_update_time:', $time);
			$QinsertData->execute();
			return true;
		}
	}

	public function getTicketNotification($ticket_id, $user_id=0) {
		if ($user_id==0) {
			$user_id=osW_VIS_User::getInstance()->getId();
		}

		$name=__FUNCTION__.'_'.$ticket_id.'_'.$user_id;
		if (!isset($this->data[$name])) {
			if ($this->getTicketDataByTicketId($ticket_id)==array()) {
				$this->data[$name]=false;
			} else {
				$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_notify_ticket: WHERE ticket_id=:ticket_id: AND user_id=:user_id:');
				$QgetData->bindTable(':table_vis_ticket_notify_ticket:', 'vis_ticket_notify_ticket');
				$QgetData->bindInt(':ticket_id:', $ticket_id);
				$QgetData->bindInt(':user_id:', $user_id);
				$QgetData->execute();
				if ($QgetData->numberOfRows()>0) {
					$this->data[$name]=true;
				} else {
				 	$this->data[$name]=false;
				}
			}
		}
		return $this->data[$name];
	}

	public function delTicketNotification($ticket_id, $user_id=0) {
		if ($user_id==0) {
			$user_id=osW_VIS_User::getInstance()->getId();
		}

		if ($this->getTicketDataByTicketId($ticket_id)==array()) {
			return false;
		}

		$time=time();

		$name=__FUNCTION__.'_'.$ticket_id.'_'.$user_id;
		if (!isset($this->data[$name])) {
			$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_notify_ticket: WHERE ticket_id=:ticket_id: AND user_id=:user_id:');
			$QgetData->bindTable(':table_vis_ticket_notify_ticket:', 'vis_ticket_notify_ticket');
			$QgetData->bindInt(':ticket_id:', $ticket_id);
			$QgetData->bindInt(':user_id:', $user_id);
			$QgetData->execute();
			if ($QgetData->numberOfRows()==0) {
				$this->data[$name]=true;
			} else {
				$QdeleteData = osW_Database::getInstance()->query('DELETE FROM :table_vis_ticket_notify_ticket: WHERE ticket_id=:ticket_id: AND user_id=:user_id:');
				$QdeleteData->bindTable(':table_vis_ticket_notify_ticket:', 'vis_ticket_notify_ticket');
				$QdeleteData->bindInt(':ticket_id:', $ticket_id);
				$QdeleteData->bindInt(':user_id:', $user_id);
				$QdeleteData->execute();
				$this->data[$name]=true;
			}
		}
		return $this->data[$name];
	}

	public function outputMinutes($minutes) {
		$minutes=intval($minutes);
		if ($minutes==0) {
			return '---';
		}
		$d=bcdiv($minutes, vOut('vis_ticket_hour_per_day')*60);
		$minutes=bcmod($minutes, vOut('vis_ticket_hour_per_day')*60);
		$h=sprintf('%02d', bcdiv($minutes, 60));
		$m=sprintf('%02d', intval(bcmod(60)));
		$output='';
		if ($d>0) {
			$output.=$d.'d ';
		}
		if ($h>0) {
			$output.=$h.'h ';
		} elseif ($d>0) {
			$output.=$h.'h ';
		}
		$output.=$m.'m';
		return $output;
	}

	public function getCounter($query) {
		$name=__FUNCTION__.'_'.md5($query);
		if (!isset($this->data[$name])) {
			$QgetData = osW_Database::getInstance()->query('SELECT COUNT(ticket_id) AS counter FROM :table_vis_ticket: WHERE :where:');
			$QgetData->bindTable(':table_vis_ticket:', 'vis_ticket');
			$QgetData->bindRaw(':where:', $query);
			$QgetData->execute();
			$QgetData->next();
			$this->data[$name][99]=$QgetData->result['counter'];

			$QgetData = osW_Database::getInstance()->query('SELECT COUNT(ticket_id) AS counter FROM :table_vis_ticket: WHERE :where: AND ticket_create_time > :ticket_create_time:');
			$QgetData->bindTable(':table_vis_ticket:', 'vis_ticket');
			$QgetData->bindRaw(':where:', $query);
			$QgetData->bindInt(':ticket_create_time:', time()-(60*60*24*7));
			$QgetData->execute();
			$QgetData->next();
			$this->data[$name][1]=$QgetData->result['counter'];

			$QgetData = osW_Database::getInstance()->query('SELECT COUNT(ticket_id) AS counter FROM :table_vis_ticket: WHERE :where: AND ticket_update_time > :ticket_update_time:');
			$QgetData->bindTable(':table_vis_ticket:', 'vis_ticket');
			$QgetData->bindRaw(':where:', $query);
			$QgetData->bindInt(':ticket_update_time:', time()-(60*60*24*7));
			$QgetData->execute();
			$QgetData->next();
			$this->data[$name][2]=$QgetData->result['counter'];

			$QgetData = osW_Database::getInstance()->query('SELECT COUNT(ticket_id) AS counter FROM :table_vis_ticket: WHERE :where: AND status_id IN (:status_id:)');
			$QgetData->bindTable(':table_vis_ticket:', 'vis_ticket');
			$QgetData->bindRaw(':where:', $query);
			$QgetData->bindRaw(':status_id:', implode(',', (osW_VIS_Ticket::getInstance()->getStatusDataOpen(false, 'status_internal_id', 'status_internal_id'))));
			$QgetData->execute();
			$QgetData->next();
			$this->data[$name][3]=$QgetData->result['counter'];

			$QgetData = osW_Database::getInstance()->query('SELECT COUNT(ticket_id) AS counter FROM :table_vis_ticket: WHERE :where: AND status_id IN (:status_id:)');
			$QgetData->bindTable(':table_vis_ticket:', 'vis_ticket');
			$QgetData->bindRaw(':where:', $query);
			$QgetData->bindRaw(':status_id:', implode(',', (osW_VIS_Ticket::getInstance()->getStatusDataWaiting(false, 'status_internal_id', 'status_internal_id'))));
			$QgetData->execute();
			$QgetData->next();
			$this->data[$name][6]=$QgetData->result['counter'];

			$QgetData = osW_Database::getInstance()->query('SELECT COUNT(ticket_id) AS counter FROM :table_vis_ticket: WHERE :where: AND status_id IN (:status_id:)');
			$QgetData->bindTable(':table_vis_ticket:', 'vis_ticket');
			$QgetData->bindRaw(':where:', $query);
			$QgetData->bindRaw(':status_id:', implode(',', (osW_VIS_Ticket::getInstance()->getStatusDataAssigned(false, 'status_internal_id', 'status_internal_id'))));
			$QgetData->execute();
			$QgetData->next();
			$this->data[$name][4]=$QgetData->result['counter'];

			$QgetData = osW_Database::getInstance()->query('SELECT COUNT(ticket_id) AS counter FROM :table_vis_ticket: WHERE :where: AND status_id IN (:status_id:)');
			$QgetData->bindTable(':table_vis_ticket:', 'vis_ticket');
			$QgetData->bindRaw(':where:', $query);
			$QgetData->bindRaw(':status_id:', implode(',', (osW_VIS_Ticket::getInstance()->getStatusDataInWork(false, 'status_internal_id', 'status_internal_id'))));
			$QgetData->execute();
			$QgetData->next();
			$this->data[$name][7]=$QgetData->result['counter'];

			$QgetData = osW_Database::getInstance()->query('SELECT COUNT(ticket_id) AS counter FROM :table_vis_ticket: WHERE :where: AND status_id IN (:status_id:)');
			$QgetData->bindTable(':table_vis_ticket:', 'vis_ticket');
			$QgetData->bindRaw(':where:', $query);
			$QgetData->bindRaw(':status_id:', implode(',', (osW_VIS_Ticket::getInstance()->getStatusDataClosed(false, 'status_internal_id', 'status_internal_id'))));
			$QgetData->execute();
			$QgetData->next();
			$this->data[$name][5]=$QgetData->result['counter'];

		}
		return $this->data[$name];
	}

	/**
	 *
	 * @return osW_VIS_Ticket
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}

}

?>