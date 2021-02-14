<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS_Notify extends osW_Object {

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

	public function add($user_id, $message, $style='default', $time=0, $lifetime=0) {
		switch ($style) {
			case 'success':
			case 'info':
			case 'error':
				break;
			default:
				$style='default';
		}

		if ($time<0) {
			$time=0;
		} elseif ($time>60*60*6) {
			$time=60*60*6;
		}
		$time=$time*1000;

		if ($lifetime<0) {
			$lifetime=0;
		}

		$Qinsert=osW_Database::getInstance()->query('INSERT INTO :table_vis_notify: (user_id, notify_message, notify_style, notify_time, notify_lifetime, notify_create_time, notify_create_user_id, notify_update_time, notify_update_user_id) VALUES (:user_id:, :notify_message:, :notify_style:, :notify_time:, :notify_lifetime:, :notify_create_time:, :notify_create_user_id:, :notify_update_time:, :notify_update_user_id:)');
		$Qinsert->bindTable(':table_vis_notify:', 'vis_notify');
		$Qinsert->bindInt(':user_id:', $user_id);
		$Qinsert->bindValue(':notify_message:', $message);
		$Qinsert->bindValue(':notify_style:', $style);
		$Qinsert->bindInt(':notify_time:', $time);
		$Qinsert->bindInt(':notify_lifetime:', $lifetime);
		$Qinsert->bindInt(':notify_create_time:', time());
		$Qinsert->bindInt(':notify_create_user_id:', 0);
		$Qinsert->bindInt(':notify_update_time:', time());
		$Qinsert->bindInt(':notify_update_user_id:', 0);
		$Qinsert->execute();
	}

	public function clear() {
		$Qdelete=osW_Database::getInstance()->query('DELETE FROM :table_vis_notify: WHERE notify_lifetime>:notify_lifetime_null: AND notify_lifetime<:notify_lifetime:');
		$Qdelete->bindTable(':table_vis_notify:', 'vis_notify');
		$Qdelete->bindInt(':notify_lifetime_null:', 0);
		$Qdelete->bindInt(':notify_lifetime:', time());
		$Qdelete->execute();
	}

	public function delete($notify_id) {
		$Qdelete=osW_Database::getInstance()->query('DELETE FROM :table_vis_notify: WHERE notify_id=:notify_id:');
		$Qdelete->bindTable(':table_vis_notify:', 'vis_notify');
		$Qdelete->bindInt(':notify_id:', $notify_id);
		$Qdelete->execute();
	}

	public function get($user_id) {
		$this->clear();

		$data=array();
		$Qselect=osW_Database::getInstance()->query('SELECT * FROM :table_vis_notify: WHERE user_id=:user_id: ORDER BY notify_id ASC LIMIT 3');
		$Qselect->bindTable(':table_vis_notify:', 'vis_notify');
		$Qselect->bindInt(':user_id:', $user_id);
		$Qselect->execute();
		if ($Qselect->numberOfRows()>0) {
			while($Qselect->next()) {
				$data[]=$Qselect->result;
				$this->delete($Qselect->result['notify_id']);
			}
		}
		return $data;
	}

	/**
	 *
	 * @return osW_VIS_Notify
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>