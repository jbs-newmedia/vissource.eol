<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS_Mandant extends osW_Object {

	/*** PROPERTIES ***/

	private $data=array();

	/*** METHODS CORE ***/


	public function __construct() {
		parent::__construct(1, 0);
	}

	public function __destruct() {
		parent::__destruct();
	}

	/*** METHODS ***/

	public function getMandanten() {
		if (!isset($this->data['mandanten_'.osW_VIS::getInstance()->getToolId()])) {
			$QselectMandanten = osW_Database::getInstance()->query('SELECT * FROM :table_vis_mandant: WHERE tool_id=:tool_id: AND mandant_status=:mandant_status: ORDER BY mandant_name ASC');
			$QselectMandanten->bindTable(':table_vis_mandant:', 'vis_mandant');
			$QselectMandanten->bindInt(':tool_id:', osW_VIS::getInstance()->getToolId());
			$QselectMandanten->bindInt(':mandant_status:', 1);
			$QselectMandanten->execute();
			if ($QselectMandanten->query_handler===false) {
				$this->__initDB();
				$QselectMandanten->execute();
			}
			$this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]=array();
			$this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]['list']=array();
			$this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]['all']=array();
			if ($QselectMandanten->numberOfRows()>0) {
				$this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]['list'][0]='Bitte wählen';
				while ($QselectMandanten->next()) {
					$this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]['list'][$QselectMandanten->Value('mandant_id')]=$QselectMandanten->Value('mandant_name');
					$this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]['all'][$QselectMandanten->Value('mandant_id')]=$QselectMandanten->result;
				}
			}
		}
		return $this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]['list'];
	}

	public function checkMandant() {
		$mandant_id=$this->getSessionMandant();
		if (($mandant_id>0)&&(isset($this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]['list'][$mandant_id]))) {
			//ok
		} else {
			osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Bitte Mandanten wählen.'));
			h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS::getInstance()->getTool()));
		}
	}

	public function getMandant() {
		$mandant_id=$this->getSessionMandant();
		if (($mandant_id>0)&&(isset($this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]['list'][$mandant_id]))) {
			return 'Bitte wählen';
		} else {
			return $this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]['list'][$mandant_id];
		}
	}

	public function getMandantNumber() {
		$mandant_id=$this->getSessionMandant();
		if (($mandant_id>0)&&(isset($this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]['all'][$mandant_id]))) {
			return $this->data['mandanten_'.osW_VIS::getInstance()->getToolId()]['all'][$mandant_id]['mandant_number'];
		} else {
			return 0;
		}
	}

	public function getSessionMandant() {
		return intval(osW_Session::getInstance()->value($this->getSessionMandantName()));
	}

	public function getSessionMandantName() {
		return osW_VIS::getInstance()->getTool().'_mandant_id';
	}

	/**
	 *
	 * @return osW_VIS_Mandant
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>