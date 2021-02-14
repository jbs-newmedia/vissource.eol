<?php

/*
 * Author: Juergen Schwind
 * Copyright: 2011 Juergen Schwind
 *
 */

class osW_VIS_Lab extends osW_Object {

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

	/**
	 *
	 * @return osW_VIS_Lab
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>