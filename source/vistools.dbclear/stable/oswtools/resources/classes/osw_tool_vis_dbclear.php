<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */
class osW_Tool_VIS_DBClear extends osW_Tool_Object {

	public $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	public function clearEntry($key, $entry) {
		switch ($key) {
			case 'vis_group':
				$Qdel = osW_Tool_Database::getInstance()->query('DELETE FROM :table: WHERE group_id=:group_id:');
				$Qdel->bindTable(':table:', $key);
				$Qdel->bindInt(':group_id:', $entry['group_id']);
				$Qdel->execute();
			break;
			case 'vis_group_permission':
				$Qdel = osW_Tool_Database::getInstance()->query('DELETE FROM :table: WHERE group_id=:group_id: AND permission_page=:permission_page: AND permission_flag=:permission_flag:');
				$Qdel->bindTable(':table:', $key);
				$Qdel->bindInt(':group_id:', $entry['group_id']);
				$Qdel->bindValue(':permission_page:', $entry['permission_page']);
				$Qdel->bindValue(':permission_flag:', $entry['permission_flag']);
				$Qdel->execute();
			break;
			case 'vis_mandant':
				$Qdel = osW_Tool_Database::getInstance()->query('DELETE FROM :table: WHERE mandant_id=:mandant_id:');
				$Qdel->bindTable(':table:', $key);
				$Qdel->bindInt(':mandant_id:', $entry['mandant_id']);
				$Qdel->execute();
				break;
			case 'vis_navigation':
				$Qdel = osW_Tool_Database::getInstance()->query('DELETE FROM :table: WHERE navigation_id=:navigation_id:');
				$Qdel->bindTable(':table:', $key);
				$Qdel->bindInt(':navigation_id:', $entry['navigation_id']);
				$Qdel->execute();
				break;
			case 'vis_page':
				$Qdel = osW_Tool_Database::getInstance()->query('DELETE FROM :table: WHERE page_id=:page_id:');
				$Qdel->bindTable(':table:', $key);
				$Qdel->bindInt(':page_id:', $entry['page_id']);
				$Qdel->execute();
				break;
			case 'vis_page_permission':
				$Qdel = osW_Tool_Database::getInstance()->query('DELETE FROM :table: WHERE page_id=:page_id: AND tool_id=:tool_id: AND permission_flag=:permission_flag:');
				$Qdel->bindTable(':table:', $key);
				$Qdel->bindInt(':page_id:', $entry['page_id']);
				$Qdel->bindInt(':tool_id:', $entry['tool_id']);
				$Qdel->bindValue(':permission_flag:', $entry['permission_flag']);
				$Qdel->execute();
				break;
			case 'vis_permission':
				$Qdel = osW_Tool_Database::getInstance()->query('DELETE FROM :table: WHERE permission_id=:permission_id:');
				$Qdel->bindTable(':table:', $key);
				$Qdel->bindInt(':permission_id:', $entry['permission_id']);
				$Qdel->execute();
				break;
			case 'vis_user_group':
				$Qdel = osW_Tool_Database::getInstance()->query('DELETE FROM :table: WHERE user_id=:user_id: AND group_id=:group_id: AND tool_id=:tool_id:');
				$Qdel->bindTable(':table:', $key);
				$Qdel->bindInt(':user_id:', $entry['user_id']);
				$Qdel->bindInt(':group_id:', $entry['group_id']);
				$Qdel->bindInt(':tool_id:', $entry['tool_id']);
				$Qdel->execute();
				break;
			case 'vis_user_pref':
				$Qdel = osW_Tool_Database::getInstance()->query('DELETE FROM :table: WHERE user_id=:user_id: AND pref_name=:pref_name:');
				$Qdel->bindTable(':table:', $key);
				$Qdel->bindInt(':user_id:', $entry['user_id']);
				$Qdel->bindValue(':pref_name:', $entry['pref_name']);
				$Qdel->execute();
				break;
			case 'vis_user_tool':
				$Qdel = osW_Tool_Database::getInstance()->query('DELETE FROM :table: WHERE user_id=:user_id: AND tool_id=:tool_id:');
				$Qdel->bindTable(':table:', $key);
				$Qdel->bindInt(':user_id:', $entry['user_id']);
				$Qdel->bindInt(':tool_id:', $entry['tool_id']);
				$Qdel->execute();
				break;
		}

	}

	/**
	 *
	 * @return osW_Tool_VIS_DBClear
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>