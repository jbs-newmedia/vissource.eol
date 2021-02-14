<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS_User extends osW_Object {

	/*** VALUES ***/

	public $data=array();

	/*** METHODS CORE ***/

	public function __construct() {
		parent::__construct(1, 1);
		$this->is_logged_in=false;
	}

	public function __destruct() {
		parent::__destruct();
	}

	/*** METHODS ***/

	public function isLoggedIn() {
		if ($this->is_logged_in===true) {
			return true;
		}
		return false;
	}

	public function validateEmail($email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return false;
	}

	public function validatePassword($pwblank, $pwcrypted) {
		if (($pwblank!='')&&($pwcrypted!='')) {
			$stack=explode(':', $pwcrypted);
			if (sizeof($stack)!=2) {
				return false;
			}
			if (md5($stack[1].$pwblank)==$stack[0]) {
				return true;
			}
		}
		return false;
	}

	public function loadUser($user_token) {
		if (strlen($user_token)==32) {
			$QloadData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_user: WHERE user_token=:user_token:');
			$QloadData->bindTable(':table_vis_user:', 'vis_user');
			$QloadData->bindValue(':user_token:', $user_token);
			$QloadData->execute();
			if ($QloadData->query_handler===false) {
				$this->__initDB();
				$QloadData->execute();
			}
			if ($QloadData->numberOfRows()==1) {
				$QloadData->next();
				$this->data=$QloadData->result;

				$Quser=osW_Database::getInstance()->query('SELECT * FROM :table_vis_user_pref WHERE user_id=:user_id');
				$Quser->bindTable(':table_vis_user_pref', 'vis_user_pref');
				$Quser->bindInt(':user_id', $this->getId());
				$Quser->execute();
				if ($Quser->query_handler===false) {
					$this->__initDB();
					$Quser->execute();
				}
				if ($Quser->numberOfRows()>0) {
					while ($Quser->next()) {
						$this->data[$Quser->Value('pref_name')]=$Quser->Value('pref_'.$Quser->Value('pref_type'));
					}
				}

				$this->is_logged_in=true;
			} else {
				$this->data=array();
				$this->is_logged_in=false;
			}
		} else {
			$this->data=array();
			$this->is_logged_in=false;
		}
	}

	public function getUserData($value) {
		if (isset($this->data[$value])) {
			return $this->data[$value];
		}
		return $this->getData($value);
	}

	private function getData($value) {
		$Quser=osW_Database::getInstance()->query('SELECT * FROM :table_vis_user_pref WHERE user_id=:user_id AND pref_name=:pref_name');
		$Quser->bindTable(':table_vis_user_pref', 'vis_user_pref');
		$Quser->bindInt(':user_id', $this->getId());
		$Quser->bindValue(':pref_name', $value);
		$Quser->execute();
		if ($Quser->query_handler===false) {
			$this->__initDB();
			$Quser->execute();
		}
		if ($Quser->numberOfRows()===1) {
			$Quser->next();
			$this->data[$value]=$Quser->Value('pref_'.$Quser->Value('pref_type'));
			return $this->data[$value];
		}
		return '';
	}

	public function doLogin($user_email) {
		$user_token=md5($user_email.microtime().uniqid(microtime()));

		$QupdateData = osW_Database::getInstance()->query('UPDATE :table_vis_user: SET user_token=:user_token: WHERE user_email=:user_email:');
		$QupdateData->bindTable(':table_vis_user:', 'vis_user');
		$QupdateData->bindValue(':user_token:', $user_token);
		$QupdateData->bindValue(':user_email:', $user_email);
		$QupdateData->execute();
		if ($QupdateData->query_handler===false) {
			$this->__initDB();
			$QupdateData->execute();
		}

		osW_Session::getInstance()->set('vis_user_token', $user_token);
		return true;
	}

	public function doLogout() {
		$user_token='';

		$QupdateData = osW_Database::getInstance()->query('UPDATE :table_vis_user: SET user_token=:user_token: WHERE user_email=:user_email:');
		$QupdateData->bindTable(':table_vis_user:', 'vis_user');
		$QupdateData->bindValue(':user_token:', $user_token);
		$QupdateData->bindValue(':user_email:', $this->getEMail());
		$QupdateData->execute();
		if ($QupdateData->query_handler===false) {
			$this->__initDB();
			$QupdateData->execute();
		}

		osW_Session::getInstance()->set('vis_user_token', $user_token);
		return true;
	}

	public function getId() {
		if (isset($this->data['user_id'])) {
			return $this->data['user_id'];
		}
		return '';
	}

	public function getIdByEMail($email) {
		if (!isset($this->data['user_id_by_email'][$email])) {
			$QselectUser = osW_Database::getInstance()->query('SELECT user_id FROM :table_vis_user: WHERE user_email=:user_email:');
			$QselectUser->bindTable(':table_vis_user:', 'vis_user');
			$QselectUser->bindValue(':user_email:', $email);
			$QselectUser->execute();
			if ($QselectUser->query_handler===false) {
				$this->__initDB();
				$QselectUser->execute();
			}
			if ($QselectUser->numberOfRows()==1) {
				$QselectUser->next();
				$this->data['user_id_by_email'][$email]=$QselectUser->Value('user_id');
			} else {
				return false;
			}
		}
		return $this->data['user_id_by_email'][$email];
	}

	public function getName() {
		if (isset($this->data['user_name'])) {
			return $this->data['user_name'];
		}

		return '';
	}

	public function getEMail() {
		if (isset($this->data['user_email'])) {
			return $this->data['user_email'];
		}

		return '';
	}

	public function setUserValue($name, $value, $typ, $userid=0) {
		if ($userid==0) {
			$userid=$this->getId();
		}
		$value_integer = 0;
		$value_float = 0.00;
		$value_string = '';
		$value_text = '';
		$value_date = '';
		switch ($typ) {
			case 'integer':
				$value_integer = $value;
				break;
			case 'float':
				$value_float = $value;
				break;
			case 'string':
				$value_string = $value;
				break;
			case 'text':
				$value_text = $value;
				break;
			case 'date':
				$value_date = $value;
				break;
			default:
				return false;
				break;
		}
		$time = time();
		$Quser = osW_Database::getInstance()->query('INSERT INTO :table_vis_user_pref: (user_id, pref_name, pref_type, pref_create, pref_lastupdate, pref_integer, pref_float, pref_string, pref_text, pref_date) VALUE (:user_id:, :pref_name:, :pref_type:, :pref_create:, :pref_lastupdate:, :pref_integer:, :pref_float:, :pref_string:, :pref_text:, :pref_date:) ON DUPLICATE KEY UPDATE pref_lastupdate=:pref_lastupdate:, pref_integer=:pref_integer:, pref_float=:pref_float:, pref_string=:pref_string:, pref_text=:pref_text:, pref_date=:pref_date:');
		$Quser->bindTable(':table_vis_user_pref:', 'vis_user_pref');
		$Quser->bindInt(':user_id:', $userid);
		$Quser->bindValue(':pref_name:', $name);
		$Quser->bindValue(':pref_type:', $typ);
		$Quser->bindInt(':pref_create:', $time);
		$Quser->bindInt(':pref_lastupdate:', $time);
		$Quser->bindInt(':pref_integer:', $value_integer);
		$Quser->bindFloat(':pref_float:', $value_float);
		$Quser->bindValue(':pref_string:', $value_string);
		$Quser->bindValue(':pref_text:', $value_text);
		$Quser->bindValue(':pref_date:', $value_date);
		$Quser->execute();
		if ($Quser->query_handler===false) {
			$this->__initDB();
			$Quser->execute();
		}
	}

	public function getUserValue($pref_name, $userid=0) {
		if ($userid==0) {
			$userid=$this->getId();
		}
		$Quser = osW_Database::getInstance()->query('SELECT * FROM :table_vis_user_pref: WHERE pref_name=:pref_name: AND user_id=:user_id:');
		$Quser->bindTable(':table_vis_user_pref:', 'vis_user_pref');
		$Quser->bindValue(':pref_name:', $pref_name);
		$Quser->bindInt(':user_id:', $userid);
		$Quser->execute();
		if ($Quser->query_handler===false) {
			$this->__initDB();
			$Quser->execute();
		}
		if ($Quser->numberOfRows()==1) {
			$Quser->next();
			return $Quser->Value('pref_'.$Quser->Value('pref_type'));
		}
		return '';
	}

	public function delUserValue($pref_name, $userid=0) {
		if ($userid==0) {
			$userid=$this->getId();
		}
		$Quser = osW_Database::getInstance()->query('DELETE FROM :table_vis_user_pref: WHERE pref_name=:pref_name: AND user_id=:user_id:');
		$Quser->bindTable(':table_vis_user_pref:', 'vis_user_pref');
		$Quser->bindValue(':pref_name:', $pref_name);
		$Quser->bindInt(':user_id:', $userid);
		$Quser->execute();
		if ($Quser->query_handler===false) {
			$this->__initDB();
			$Quser->execute();
		}
		if ($Quser->numberOfRows()==1) {
			return true;
		}
		return false;
	}

	/**
	 *
	 * @return osW_VIS_User
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>