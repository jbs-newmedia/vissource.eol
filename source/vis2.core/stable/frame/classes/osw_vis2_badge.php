<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS2 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS2_Badge extends osW_Object {

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

	public function set($page, $count) {
		$name='badge';
		$this->data[$name][$page]=intval($count);
	}

	public function get($page) {
		$name='badge';
		if (isset($this->data[$name][$page])) {
			if ($this->data[$name][$page]>999) {
				return 999;
			}
			return $this->data[$name][$page];
		}
		return -1;
	}

	public function getReal($page) {
		$name='badge';
		if (isset($this->data[$name][$page])) {
			return $this->data[$name][$page];
		}
		return -1;
	}

	/**
	 *
	 * @return osW_VIS2_Badge
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>