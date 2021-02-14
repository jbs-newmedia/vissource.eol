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
class osW_Tool_DDM extends osW_Tool_Object {

	public $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	private function callback_markVars($matches) {
		$this->data['vars'][md5($matches[2])]=$matches[2];
		return '=>OSW_START_'.md5($matches[2]).'_END_OSW,';
	}

	public function strip($c) {
		$c=preg_replace_callback('/(=>)((?!\').*)(,)/', array($this,'callback_markVars'), $c);
		return $c;
	}

	public function set($name, $value) {
		$this->variables[$name]=$value;
	}


	public function evalCode($code) {
		$code=$this->strip($code);
		error_reporting(0);
		ob_start();
		eval($code);
		$code=ob_get_contents();
		ob_end_clean();
		error_reporting(E_ALL);

		foreach ($this->data['vars'] as $key => $value) {
			$code=str_replace('OSW_START_'.$key.'_END_OSW', $value, $code);
		}

		return $code;
	}


	public function addGroup($ddm_group='$ddm_group', $options=array()) {
		$output=array();
		$output[]='osW_DDM3::getInstance()->addGroup($ddm_group, array(';
		if (isset($options['messages'])) {
			$output[]='	\'messages\'=>array(';
			foreach ($options['messages'] as $key => $value) {
				$output[]='		\''.$key.'\'=>\''.$value.'\',';
			}
			$output[]='	),';
		}

		$general=array();
		if (isset($options['engine'])) {
			$general['engine']=$options['engine'];
		}
		if (isset($options['cache'])) {
			$general['cache']=$options['cache'];
		}
		if (isset($options['db_epp'])) {
			$general['elements_per_page']=$options['db_epp'];
		}
		if ($general!=array()) {
			$output[]='	\'general\'=>array(';
			foreach ($general as $key => $value) {
				if (strstr($value, 'OSW_START')) {
					$output[]='		\''.$key.'\'=>'.$value.',';
				} else {
					$output[]='		\''.$key.'\'=>\''.$value.'\',';
				}
			}
			$output[]='	),';
		}

		$direct=array();
		if (isset($options['direct_module'])) {
			$direct['module']=$options['direct_module'];
		}
		if (isset($options['direct_parameters'])) {
			$direct['parameters']=$options['direct_parameters'];
		}
		if ($direct!=array()) {
			$output[]='	\'direct\'=>array(';
			foreach ($direct as $key => $value) {
				if (is_array($value)) {
					$output[]='		\''.$key.'\'=>array(';
					foreach ($value as $_key => $_value) {
						if (strstr($_value, 'OSW_START')) {
							$output[]='			\''.$_key.'\'=>'.$_value.',';
						} else {
							$output[]='			\''.$_key.'\'=>\''.$_value.'\',';
						}
					}
					$output[]='		),';
				} elseif (strstr($value, 'OSW_START')) {
					$output[]='		\''.$key.'\'=>'.$value.',';
				} else {
					$output[]='		\''.$key.'\'=>\''.$value.'\',';
				}
			}
			$output[]='	),';
		}

		$database=array();
		if (isset($options['db_table'])) {
			$database['table']=$options['db_table'];
		}
		$database['alias']='tbl1';
		if (isset($options['db_index'])) {
			$database['index']=$options['db_index'];
		}
		if (isset($options['db_index_type'])) {
			$database['index_type']=$options['db_index_type'];
		}
		if (isset($options['db_order'])) {
			$_order=$options['db_order'];
			$_order=explode('__', $_order);
			if (count($_order)==2) {
				$database['order'][$_order[0]]=strtolower($_order[1]);
			}
		}
		if ($database!=array()) {
			$output[]='	\'database\'=>array(';
			foreach ($database as $key => $value) {
				if (is_array($value)) {
					$output[]='		\''.$key.'\'=>array(';
					foreach ($value as $_key => $_value) {
						if (strstr($_value, 'OSW_START')) {
							$output[]='			\''.$_key.'\'=>'.$_value.',';
						} else {
							$output[]='			\''.$_key.'\'=>\''.$_value.'\',';
						}
					}
					$output[]='		),';
				} elseif (strstr($value, 'OSW_START')) {
					$output[]='		\''.$key.'\'=>'.$value.',';
				} else {
					$output[]='		\''.$key.'\'=>\''.$value.'\',';
				}
			}
			$output[]='	),';
		}
		$output[]='));';
		echo implode("\n", $output);
	}

	public function addDataViewElement($ddm_group='$ddm_group', $element='', $options=array()) {
		if ($element=='') {
			die('element_name');
		}
		$this->data['View'][$element]=$options;
	}

	public function addDataListElement($ddm_group='$ddm_group', $element='', $options=array()) {
		if ($element=='') {
			die('element_name');
		}
		$this->data['Data']['List'][$element]=$options;
	}

	public function addFormSearchElement($ddm_group='$ddm_group', $element='', $options=array()) {
		if ($element=='') {
			die('element_name');
		}
		$this->data['Data']['Search'][$element]=$options;
	}

	public function addFormAddElement($ddm_group='$ddm_group', $element='', $options=array()) {
		if ($element=='') {
			die('element_name');
		}
		$this->data['Data']['Add'][$element]=$options;
	}

	public function addFormEditElement($ddm_group='$ddm_group', $element='', $options=array()) {
		if ($element=='') {
			die('element_name');
		}
		$this->data['Data']['Edit'][$element]=$options;
	}

	public function addFormDeleteElement($ddm_group='$ddm_group', $element='', $options=array()) {
		if ($element=='') {
			die('element_name');
		}
		$this->data['Data']['Delete'][$element]=$options;
	}

	public function addFormFinishElement($ddm_group='$ddm_group', $element='', $options=array()) {
		if ($element=='') {
			die('element_name');
		}
		$this->data['Finish'][$element]=$options;
	}

	public function getElements() {
		$output=array();

		$options_values=array();
		$options_values['hidden_value']='default_value';
		$options_values['message_all']='text_all';
		$options_values['message_yes']='text_yes';
		$options_values['message_no']='text_no';
		$options_values['list_view']='view';

		// VIEW
		$output[]='// View';
		foreach ($this->data['View'] as $element_name => $element_data) {
			$data=$element_data;
			if ((isset($data['enabled']))&&($data['enabled']=='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW')) {
				unset($data['enabled']);
			}
			$_output=array();
			$_output[]='osW_DDM3::getInstance()->addViewElement($ddm_group, \''.$element_name.'\', array(';
			foreach ($data as $key => $value) {
				if (is_array($value)) {
					$_output[]='	\''.$key.'\'=>array(';
					foreach ($value as $_key => $_value) {
						if (strstr($_value, 'OSW_START')) {
							$_output[]='		\''.$_key.'\'=>'.$_value.',';
						} else {
							$_output[]='		\''.$_key.'\'=>\''.$_value.'\',';
						}
					}
					$_output[]='	),';
				} elseif (strstr($value, 'OSW_START')) {
					$_output[]='	\''.$key.'\'=>'.$value.',';
				} else {
					$_output[]='	\''.$key.'\'=>\''.$value.'\',';
				}
			}
			$_output[]='));';
			$_output=implode("\n", $_output);
			foreach ($this->data['vars'] as $key => $value) {
				$_output=str_replace('OSW_START_'.$key.'_END_OSW', $value, $_output);
			}
			$output[]=$_output;
		}


		// ADD
		$data_elements=array();
		foreach ($this->data['Data']['Add'] as $element_name => $element_data) {
			$_data=$element_data;
			if ((isset($_data['enabled']))&&($_data['enabled']=='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW')) {
				unset($_data['enabled']);
			}

			$data=array();

			if (isset($_data['module'])) {
				$data['module']=$_data['module'];
				unset($_data['module']);
			}
			if (isset($_data['title'])) {
				$data['title']=$_data['title'];
				unset($_data['title']);
			}
			if (isset($_data['formdata_name'])) {
				$data['name']=$_data['formdata_name'];
				unset($_data['formdata_name']);
			}

			$data['options']=$_data;

			if (isset($data['options']['validation'])) {
				$validation=$data['options']['validation'];
				unset($data['options']['validation']);
				if ((isset($validation['enabled']))&&($validation['enabled']=='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW')) {
					unset($validation['enabled']);
				}
				if (isset($validation['validation_reg'])) {
					$validation['preg']=$validation['validation_reg'];
					unset($validation['validation_reg']);
				}
				if (isset($validation['validation_filter'])) {
					$filter=$validation['validation_filter'];
					unset($validation['validation_filter']);
					$filter=explode(';', $filter);
					$validation['filter']=array();
					foreach ($filter as $f) {
						$validation['filter'][$f]=array();
					}
				}
				$data['validation']=$validation;
			}

			foreach ($options_values as $key => $value) {
				if (isset($data['options'][$key])) {
					$data['options'][$value]=$data['options'][$key];
					unset($data['options'][$key]);
				}
			}

			$data_elements[$element_name]=$data;
		}


		// EDIT
		foreach ($this->data['Data']['Edit'] as $element_name => $element_data) {
			$_data=$element_data;
			if ((isset($_data['enabled']))&&($_data['enabled']=='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW')) {
				unset($_data['enabled']);
			}

			$data=array();

			if (isset($_data['module'])) {
				$data['module']=$_data['module'];
				unset($_data['module']);
			}
			if (isset($_data['title'])) {
				$data['title']=$_data['title'];
				unset($_data['title']);
			}
			if (isset($_data['formdata_name'])) {
				$data['name']=$_data['formdata_name'];
				unset($_data['formdata_name']);
			}

			$data['options']=$_data;

			if (isset($data['options']['validation'])) {
				$validation=$data['options']['validation'];
				unset($data['options']['validation']);
				if ((isset($validation['enabled']))&&($validation['enabled']=='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW')) {
					unset($validation['enabled']);
				}
				if (isset($validation['validation_reg'])) {
					$validation['preg']=$validation['validation_reg'];
					unset($validation['validation_reg']);
				}
				if (isset($validation['validation_filter'])) {
					$filter=$validation['validation_filter'];
					unset($validation['validation_filter']);
					$filter=explode(';', $filter);
					$validation['filter']=array();
					foreach ($filter as $f) {
						$validation['filter'][$f]=array();
					}
				}
				$data['validation']=$validation;
			}

			foreach ($options_values as $key => $value) {
				if (isset($data['options'][$key])) {
					$data['options'][$value]=$data['options'][$key];
					unset($data['options'][$key]);
				}
			}

			if (!isset($data_elements[$element_name])) {
				if (!isset($_last_element)) {
					$data_elements_new[$element_name]=$data;
					$data_elements=$data_elements_new+$data_elements;
				} else {
					foreach ($data_elements as $key => $value) {
						$data_elements_new[$key]=$value;
						if ($key==$_last_element) {
							$data_elements_new[$element_name]=$data;
						}
					}
					$data_elements=$data_elements_new;
				}
			} else {
				if ($data_elements[$element_name]!=$data) {
					foreach ($data as $key => $value) {
						if (is_array($value)) {
							foreach ($value as $_key => $_value) {
								if ($data_elements[$element_name][$key][$_key]!=$_value) {
									if (!isset($data_elements[$element_name]['_edit'])) {
										$data_elements[$element_name]['_edit']=array();
									}
									$data_elements[$element_name]['_edit'][$key][$_key]=$_value;
								}
							}
						} else {
							if ($data_elements[$element_name][$key]!=$value) {
								if (!isset($data_elements[$element_name]['_edit'])) {
									$data_elements[$element_name]['_edit']=array();
								}
								$data_elements[$element_name]['_edit'][$key]=$value;
							}
						}
					}
				}
#				$data_elements[$element_name]=$data;

			}

		}


		// DELETE
		foreach ($this->data['Data']['Delete'] as $element_name => $element_data) {
			$_data=$element_data;
			if ((isset($_data['enabled']))&&($_data['enabled']=='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW')) {
				unset($_data['enabled']);
			}

			$data=array();

			if (isset($_data['module'])) {
				$data['module']=$_data['module'];
				unset($_data['module']);
			}
			if (isset($_data['title'])) {
				$data['title']=$_data['title'];
				unset($_data['title']);
			}
			if (isset($_data['formdata_name'])) {
				$data['name']=$_data['formdata_name'];
				unset($_data['formdata_name']);
			}

			$data['options']=$_data;

			foreach ($options_values as $key => $value) {
				if (isset($data['options'][$key])) {
					$data['options'][$value]=$data['options'][$key];
					unset($data['options'][$key]);
				}
			}

			if (!isset($data_elements[$element_name])) {
				if (!isset($_last_element)) {
					$data_elements_new[$element_name]=$data;
					$data_elements=$data_elements_new+$data_elements;
				} else {
					foreach ($data_elements as $key => $value) {
						$data_elements_new[$key]=$value;
						if ($key==$_last_element) {
							$data_elements_new[$element_name]=$data;
						}
					}
					$data_elements=$data_elements_new;
				}
			} else {
				if ($data_elements[$element_name]!=$data) {
					foreach ($data as $key => $value) {
						if (is_array($value)) {
							foreach ($value as $_key => $_value) {
								if ($data_elements[$element_name][$key][$_key]!=$_value) {
									if (!isset($data_elements[$element_name]['_delete'])) {
										$data_elements[$element_name]['_delete']=array();
									}
									$data_elements[$element_name]['_delete'][$key][$_key]=$_value;
								}
							}
						} else {
							if ($data_elements[$element_name][$key]!=$value) {
								if (!isset($data_elements[$element_name]['_delete'])) {
									$data_elements[$element_name]['_delete']=array();
								}
								$data_elements[$element_name]['_delete'][$key]=$value;
							}
						}
					}
				}
				#				$data_elements[$element_name]=$data;

			}

		}


		// SEARCH
		foreach ($this->data['Data']['Search'] as $element_name => $element_data) {
			$_data=$element_data;
			if ((isset($_data['enabled']))&&($_data['enabled']=='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW')) {
				unset($_data['enabled']);
			}

			$data=array();

			if (isset($_data['module'])) {
				$data['module']=$_data['module'];
				unset($_data['module']);
			}
			if (isset($_data['title'])) {
				$data['title']=$_data['title'];
				unset($_data['title']);
			}
			if (isset($_data['formdata_name'])) {
				$data['name']=$_data['formdata_name'];
				unset($_data['formdata_name']);
			}

			$data['options']=$_data;

			foreach ($options_values as $key => $value) {
				if (isset($data['options'][$key])) {
					$data['options'][$value]=$data['options'][$key];
					unset($data['options'][$key]);
				}
			}

			if (!isset($data_elements[$element_name])) {
				if (!isset($_last_element)) {
					$data_elements_new[$element_name]=$data;
					$data_elements=$data_elements_new+$data_elements;
				} else {
					foreach ($data_elements as $key => $value) {
						$data_elements_new[$key]=$value;
						if ($key==$_last_element) {
							$data_elements_new[$element_name]=$data;
						}
					}
					$data_elements=$data_elements_new;
				}
			} else {
				if ($data_elements[$element_name]!=$data) {
					foreach ($data as $key => $value) {
						if (is_array($value)) {
							foreach ($value as $_key => $_value) {
								if (@$data_elements[$element_name][$key][$_key]!=$_value) {
									if (!isset($data_elements[$element_name]['_search'])) {
										$data_elements[$element_name]['_search']=array();
									}
									$data_elements[$element_name]['_search'][$key][$_key]=$_value;
								}
							}
						} else {
							if ($data_elements[$element_name][$key]!=$value) {
								if (!isset($data_elements[$element_name]['_search'])) {
									$data_elements[$element_name]['_search']=array();
								}
								$data_elements[$element_name]['_search'][$key]=$value;
							}
						}
					}
				}
				#				$data_elements[$element_name]=$data;

			}

		}


		// LIST
		foreach ($this->data['Data']['List'] as $element_name => $element_data) {
			$_data=$element_data;
			if ((isset($_data['enabled']))&&($_data['enabled']=='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW')) {
				unset($_data['enabled']);
			}

			$data=array();

			if (isset($_data['module'])) {
				$data['module']=$_data['module'];
				unset($_data['module']);
			}
			if (isset($_data['title'])) {
				$data['title']=$_data['title'];
				unset($_data['title']);
			}
			if (isset($_data['formdata_name'])) {
				$data['name']=$_data['formdata_name'];
				unset($_data['formdata_name']);
			}

			$data['options']=$_data;

			foreach ($options_values as $key => $value) {
				if (isset($data['options'][$key])) {
					$data['options'][$value]=$data['options'][$key];
					unset($data['options'][$key]);
				}
			}

			if (!isset($data_elements[$element_name])) {
				if (!isset($_last_element)) {
					$data_elements_new[$element_name]=$data;
					$data_elements=$data_elements_new+$data_elements;
				} else {
					foreach ($data_elements as $key => $value) {
						$data_elements_new[$key]=$value;
						if ($key==$_last_element) {
							$data_elements_new[$element_name]=$data;
						}
					}
					$data_elements=$data_elements_new;
				}
			} else {
				if ($data_elements[$element_name]!=$data) {
					$_last_element=$element_name;
					foreach ($data as $key => $value) {
						if (is_array($value)) {
							foreach ($value as $_key => $_value) {
								if ((isset($data_elements[$element_name][$key][$_key]))&&($data_elements[$element_name][$key][$_key]!=$_value)) {
									$data_elements[$element_name]['_list'][$key][$_key]=$_value;
								} else {
									$data_elements[$element_name]['_list'][$key][$_key]=$_value;
								}
							}
						} else {
							if ($data_elements[$element_name][$key]!=$value) {
								if (!isset($data_elements[$element_name]['_list'])) {
									$data_elements[$element_name]['_list']=array();
								}
								$data_elements[$element_name]['_list'][$key]=$value;
							}
						}
					}
				}
			}
		}

		foreach ($data_elements as $key => $value) {
			if ((isset($value['_list']))&&(isset($value['_list']['options']))) {
				if (isset($value['_list']['options']['view'])) {
					unset($value['_list']['options']['view']);
				}
				if ((isset($value['_list']['options']['order']))&&($value['_list']['options']['order']=='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW')) {
					unset($value['_list']['options']['order']);
					$data_elements[$key]['options']['order']='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW';
				}
				if ($value['_list']['options']==array()) {
					unset($data_elements[$key]['_list']['options']);
				} else {
					$data_elements[$key]['_list']['options']=$value['_list']['options'];
				}
			} else {
				$data_elements[$key]['_list']['enabled']='OSW_START_68934a3e9455fa72420237eb05902327_END_OSW';
			}
			if ((isset($data_elements[$key]['options']))&&($data_elements[$key]['options']==array())) {
				unset($data_elements[$key]['options']);
			}
			if ((isset($data_elements[$key]['validation']))&&($data_elements[$key]['validation']==array())) {
				unset($data_elements[$key]['validation']);
			}
			if ((isset($data_elements[$key]['_list']))&&($data_elements[$key]['_list']==array())) {
				unset($data_elements[$key]['_list']);
			}
		}


		foreach ($data_elements as $key => $value) {
			if (!isset($this->data['Data']['Add'][$key])) {
				$data_elements[$key]['_add']['enabled']='OSW_START_68934a3e9455fa72420237eb05902327_END_OSW';
			}
			if (!isset($this->data['Data']['Edit'][$key])) {
				$data_elements[$key]['_edit']['enabled']='OSW_START_68934a3e9455fa72420237eb05902327_END_OSW';
			}
			if (!isset($this->data['Data']['Delete'][$key])) {
				$data_elements[$key]['_delete']['enabled']='OSW_START_68934a3e9455fa72420237eb05902327_END_OSW';
			}
			if (!isset($this->data['Data']['Search'][$key])) {
				$data_elements[$key]['_search']['enabled']='OSW_START_68934a3e9455fa72420237eb05902327_END_OSW';
			}
		}


		// DATA
		$output[]='// Data';
		foreach ($data_elements as $element_name => $element_data) {
			$data=$element_data;
			if ((isset($data['enabled']))&&($data['enabled']=='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW')) {
				unset($data['enabled']);
			}
			$_output=array();
			$_output[]='osW_DDM3::getInstance()->addDataElement($ddm_group, \''.$element_name.'\', array(';
			foreach ($data as $key => $value) {
				if ($value==array()) {
					$_output[]='	\''.$key.'\'=>array(),';
				} else {
					if (is_array($value)) {
						$_output[]='	\''.$key.'\'=>array(';
						foreach ($value as $_key => $_value) {
							if (is_array($_value)) {
								if ($_value==array()) {
									$_output[]='		\''.$_key.'\'=>array(),';
								} else {
									$_output[]='		\''.$_key.'\'=>array(';
									foreach ($_value as $__key => $__value) {
										if (is_array($__value)) {
											if ($__value==array()) {
												$_output[]='			\''.$__key.'\'=>array(),';
											} else {
												$_output[]='			\''.$__key.'\'=>array(';
												foreach ($__value as $___key => $___value) {


													if (is_array($___value)) {
														if ($___value==array()) {
															$_output[]='					\''.$___key.'\'=>array(),';
														} else {
															$_output[]='					\''.$___key.'\'=>array(';
															print_a($___value);
															foreach ($___value as $____key => $____value) {
																if (strstr($____value, 'OSW_START')) {
																	$_output[]='						\''.$____key.'\'=>'.$____value.',';
																} else {
																	$_output[]='						\''.$____key.'\'=>\''.$____value.'\',';
																}
															}
															$_output[]='				),';
														}
													} elseif (strstr($___value, 'OSW_START')) {
														$_output[]='				\''.$___key.'\'=>'.$___value.',';
													} else {
														$_output[]='				\''.$___key.'\'=>\''.$___value.'\',';
													}


												}
												$_output[]='			),';
											}
										} elseif (strstr($__value, 'OSW_START')) {
											$_output[]='			\''.$__key.'\'=>'.$__value.',';
										} else {
											$_output[]='			\''.$__key.'\'=>\''.$__value.'\',';
										}
									}
									$_output[]='		),';
								}
							} elseif (strstr($_value, 'OSW_START')) {
								$_output[]='		\''.$_key.'\'=>'.$_value.',';
							} else {
								$_output[]='		\''.$_key.'\'=>\''.$_value.'\',';
							}
						}
						$_output[]='	),';
					} elseif (strstr($value, 'OSW_START')) {
						$_output[]='	\''.$key.'\'=>'.$value.',';
					} else {
						$_output[]='	\''.$key.'\'=>\''.$value.'\',';
					}
				}
			}
			$_output[]='));';
			$_output=implode("\n", $_output);
			foreach ($this->data['vars'] as $key => $value) {
				$_output=str_replace('OSW_START_'.$key.'_END_OSW', $value, $_output);
			}
			$output[]=$_output;
		}






		// FINISH

		$output[]='// Finish';
		foreach ($this->data['Finish'] as $element_name => $element_data) {
			$data=$element_data;
			if ((isset($data['enabled']))&&($data['enabled']=='OSW_START_b326b5062b2f0e69046810717534cb09_END_OSW')) {
				unset($data['enabled']);
			}
			$_output=array();
			$_output[]='osW_DDM3::getInstance()->addFinishElement($ddm_group, \''.$element_name.'\', array(';
			foreach ($data as $key => $value) {
				if (is_array($value)) {
					$_output[]='	\''.$key.'\'=>array(';
					foreach ($value as $_key => $_value) {
						if (strstr($_value, 'OSW_START')) {
							$_output[]='		\''.$_key.'\'=>'.$_value.',';
						} else {
							$_output[]='		\''.$_key.'\'=>\''.$_value.'\',';
						}
					}
					$_output[]='	),';
				} elseif (strstr($value, 'OSW_START')) {
					$_output[]='	\''.$key.'\'=>'.$value.',';
				} else {
					$_output[]='	\''.$key.'\'=>\''.$value.'\',';
				}
			}
			$_output[]='));';
			$_output=implode("\n", $_output);
			foreach ($this->data['vars'] as $key => $value) {
				$_output=str_replace('OSW_START_'.$key.'_END_OSW', $value, $_output);
			}
			$output[]=$_output;
		}


		return $output;
	}

	/**
	 *
	 * @return osW_Tool_DDM
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>