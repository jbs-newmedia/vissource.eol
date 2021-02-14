<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS2 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS2_Protect extends osW_Object {

	/*** VALUES ***/

	public $data=array();

	/*** METHODS CORE ***/

	public function __construct() {
		parent::__construct(1, 0);
	}

	public function __destruct() {
		parent::__destruct();
	}

	/*** METHODS ***/

	public function add($user_id, $tool_id) {
		$time=time();
		$QinsertData = osW_Database::getInstance()->query('INSERT INTO :table_vis2_protect: (user_id, tool_id, protect_create_time, protect_create_user_id, protect_update_time, protect_update_user_id) VALUES (:user_id:, :tool_id:, :protect_create_time:, :protect_create_user_id:, :protect_update_time:, :protect_update_user_id:)');
		$QinsertData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QinsertData->bindInt(':user_id:', $user_id);
		$QinsertData->bindInt(':tool_id:', $tool_id);
		$QinsertData->bindInt(':protect_create_time:', $time);
		$QinsertData->bindInt(':protect_create_user_id:', 0);
		$QinsertData->bindInt(':protect_update_time:', $time);
		$QinsertData->bindInt(':protect_update_user_id:', 0);
		$QinsertData->execute();
		if ($QinsertData->query_handler===false) {
			$this->__initDB();
			$QinsertData->execute();
		}
	}

	public function clear($user_id) {
		$QinsertData = osW_Database::getInstance()->query('DELETE FROM :table_vis2_protect: WHERE user_id=:user_id:');
		$QinsertData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QinsertData->bindInt(':user_id:', $user_id);
		$QinsertData->execute();
		if ($QinsertData->query_handler===false) {
			$this->__initDB();
			$QinsertData->execute();
		}
	}

	public function check($user_id) {
		$QgetData = osW_Database::getInstance()->query('SELECT COUNT(protect_id) AS counter FROM :table_vis2_protect: WHERE user_id=:user_id:');
		$QgetData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QgetData->bindInt(':user_id:', $user_id);
		$QgetData->execute();
		if ($QgetData->query_handler===false) {
			$this->__initDB();
			$QgetData->execute();
		}
		$QgetData->next();

		$attempts=$QgetData->result['counter'];

		if ($attempts==0) {
			return true;
		}

		if ($attempts<vOut('vis2_protect_attempts')) {
			return true;
		}

		if ($attempts>=vOut('vis2_protect_attempts_max')) {
			return 0;
		}

		$attempts=bcdiv($attempts, vOut('vis2_protect_attempts'));
		$time=vOut('vis2_protect_time')*pow(2, $attempts-1);

		$QgetData = osW_Database::getInstance()->query('SELECT protect_create_time FROM :table_vis2_protect: WHERE user_id=:user_id: ORDER BY protect_id ASC LIMIT :start:, 1');
		$QgetData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QgetData->bindInt(':user_id:', $user_id);
		$QgetData->bindInt(':start:', ($attempts*vOut('vis2_protect_attempts'))-1);
		$QgetData->execute();
		if ($QgetData->query_handler===false) {
			$this->__initDB();
			$QgetData->execute();
		}
		$QgetData->next();
		if (($QgetData->result['protect_create_time']+$time)<=time()) {
			return true;
		}

		return ($QgetData->result['protect_create_time']+$time);
	}

	/**
	 *
	 * @return osW_VIS2_Protect
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>