<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Updater
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

define('abs_path', str_replace('\\', '/', str_replace(basename(dirname(__FILE__)), '', dirname(__FILE__))));

define('serverlist', 'vis');
define('package', 'vistools.createarray');
define('release', 'stable');

include(abs_path.'resources/includes/header.inc.php');

osW_Tool::getInstance()->initTool(package, release);

osW_Tool_Server::getInstance()->readServerList(serverlist);
osW_Tool_Server::getInstance()->updatePackageList(serverlist);
osW_Tool::getInstance()->update(serverlist);

// TOOL - BEGIN

echo osW_Tool_Template::getInstance()->outputHeader(array('../resources/css/layout_core.css'), array('../resources/js/jquery.js'));

$db=array(
	'type'=>'mysql',
	'database'=>osW_Tool::getInstance()->getFrameConfig('database_db'),
	'server'=>osW_Tool::getInstance()->getFrameConfig('database_server'),
	'username'=>osW_Tool::getInstance()->getFrameConfig('database_username'),
	'password'=>osW_Tool::getInstance()->getFrameConfig('database_password'),
	'pconnect'=>false,
	'prefix'=>osW_Tool::getInstance()->getFrameConfig('database_prefix')
);

$GLOBALS['DEBUGLIB_MAX_Y']=1000;

osW_Tool_Database::addDatabase('default', $db);
$dbstatus=osW_Tool_Database::connect('default', $db);

$tool=array();
$tool[0]='';
if ($dbstatus->error===false) {
	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_tool: WHERE 1');
	$Qget->bindTable(':table_vis_tool:', 'vis_tool');
	$Qget->execute();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			$tool[$Qget->result['tool_id']]=$Qget->result['tool_name'];
		}
	}
}

$reload=false;

if ((isset($_POST['action']))&&($_POST['action']=='docreate')&&(isset($_POST['tool_id']))&&(isset($tool[$_POST['tool_id']]))&&($_POST['tool_id']!=0)) {
	$output=array();
	$output[]='';
	$output[]='$_vis_script=array();';
	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_tool: WHERE tool_id=:tool_id:');
	$Qget->bindTable(':table_vis_tool:', 'vis_tool');
	$Qget->bindInt(':tool_id:', $_POST['tool_id']);
	$Qget->execute();
	if ($Qget->numberOfRows()==1) {
		$Qget->next();

		$output[]='$_vis_script[\'tool\']=array(';
		$output[]='	\'tool_name\'=>\''.$Qget->result['tool_name'].'\',';
		$output[]='	\'tool_name_intern\'=>\''.$Qget->result['tool_name_intern'].'\',';
		$output[]='	\'tool_description\'=>\''.$Qget->result['tool_description'].'\',';
		$output[]='	\'tool_status\'=>'.$Qget->result['tool_status'].',';
		$output[]='	\'tool_hide_logon\'=>'.$Qget->result['tool_hide_logon'].',';
		$output[]='	\'tool_hide_navigation\'=>'.$Qget->result['tool_hide_navigation'].',';
		$output[]=');';
	}

	$ar_groups=array();
	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_group: WHERE tool_id=:tool_id:');
	$Qget->bindTable(':table_vis_group:', 'vis_group');
	$Qget->bindInt(':tool_id:', $_POST['tool_id']);
	$Qget->execute();
	if ($Qget->numberOfRows()>0) {
		$output[]='$_vis_script[\'group\']=array();';
		while ($Qget->next()) {
			$ar_groups[]=$Qget->result['group_id'];
			$output[]='$_vis_script[\'group\']['.$Qget->result['group_id'].']=array(';
			$output[]='	\'group_name\'=>\''.$Qget->result['group_name'].'\',';
			$output[]='	\'group_name_intern\'=>\''.$Qget->result['group_name_intern'].'\',';
			$output[]='	\'group_description\'=>\''.$Qget->result['group_description'].'\',';
			$output[]='	\'group_status\'=>'.$Qget->result['group_status'].',';
			$output[]=');';
		}
	}

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_permission: WHERE tool_id=:tool_id:');
	$Qget->bindTable(':table_vis_permission:', 'vis_permission');
	$Qget->bindInt(':tool_id:', $_POST['tool_id']);
	$Qget->execute();
	if ($Qget->numberOfRows()>0) {
		$output[]='$_vis_script[\'permission\']=array();';
		while ($Qget->next()) {
			$output[]='$_vis_script[\'permission\'][]=array(';
			$output[]='	\'permission_flag\'=>\''.$Qget->result['permission_flag'].'\',';
			$output[]='	\'permission_title\'=>\''.$Qget->result['permission_title'].'\',';
			$output[]='	\'permission_ispublic\'=>'.$Qget->result['permission_ispublic'].',';
			$output[]=');';
		}
	}

	# level 0
	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_navigation: WHERE tool_id=:tool_id: AND navigation_parent_id=:navigation_parent_id: ORDER BY navigation_sortorder ASC');
	$Qget->bindTable(':table_vis_navigation:', 'vis_navigation');
	$Qget->bindInt(':tool_id:', $_POST['tool_id']);
	$Qget->bindInt(':navigation_parent_id:', 0);
	$Qget->execute();
	$output[]='$_vis_script[\'navigation\']=array();';
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			$output[]='$_vis_script[\'navigation\'][]=array(';
			$output[]='	\'navigation_parent_id\'=>0,';
			$output[]='	\'navigation_title\'=>\''.$Qget->result['navigation_title'].'\',';
			$output[]='	\'navigation_sortorder\'=>'.$Qget->result['navigation_sortorder'].',';
			$output[]='	\'navigation_ispublic\'=>'.$Qget->result['navigation_ispublic'].',';
			$QgetPage = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_page: WHERE tool_id=:tool_id: AND page_id=:page_id:');
			$QgetPage->bindTable(':table_vis_page:', 'vis_page');
			$QgetPage->bindInt(':tool_id:', $_POST['tool_id']);
			$QgetPage->bindInt(':page_id:', $Qget->result['page_id']);
			$QgetPage->execute();
			if ($QgetPage->numberOfRows()==1) {
				$QgetPage->next();
				$output[]='	\'page\'=>array(';
				$output[]='		\'page_name\'=>\''.$QgetPage->result['page_name'].'\',';
				$output[]='		\'page_name_intern\'=>\''.$QgetPage->result['page_name_intern'].'\',';
				$output[]='		\'page_description\'=>\''.$QgetPage->result['page_description'].'\',';
				$output[]='		\'page_ispublic\'=>'.$QgetPage->result['page_ispublic'].',';

				$QgetPerm = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_page_permission: WHERE tool_id=:tool_id: AND page_id=:page_id:');
				$QgetPerm->bindTable(':table_vis_page_permission:', 'vis_page_permission');
				$QgetPerm->bindInt(':tool_id:', $_POST['tool_id']);
				$QgetPerm->bindInt(':page_id:', $Qget->result['page_id']);
				$QgetPerm->execute();
				$ar_perm=array();
				if ($QgetPerm->numberOfRows()>0) {
					while ($QgetPerm->next()) {
						$ar_perm[$QgetPerm->result['permission_flag']]=$QgetPerm->result['permission_flag'];
					}
				}
				if ($ar_perm==array()) {
					$output[]='		\'permission\'=>array(),';
				} else {
					$output[]='		\'permission\'=>array(\''.implode('\',\'', $ar_perm).'\'),';
				}
				$output[]='	),';
				$output[]='	\'permission\'=>array(';

				foreach ($ar_groups as $group_id) {
					$QgetPerm = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_group_permission: WHERE group_id=:group_id: AND permission_page=:permission_page:');
					$QgetPerm->bindTable(':table_vis_group_permission:', 'vis_group_permission');
					$QgetPerm->bindInt(':group_id:', $group_id);
					$QgetPerm->bindValue(':permission_page:', $QgetPage->result['page_name_intern']);
					$QgetPerm->execute();
					$ar_perm=array();
					if ($QgetPerm->numberOfRows()>0) {
						while ($QgetPerm->next()) {
							$ar_perm[$QgetPerm->result['permission_flag']]=$QgetPerm->result['permission_flag'];
						}
					}
					if ($ar_perm!=array()) {
						$output[]='		'.$group_id.'=>array(\''.implode('\',\'', $ar_perm).'\'),';
					}
				}
				$output[]='	),';
			}
			$output[]=');';

			# level 1
			if (isset($Qget->result['navigation_id'])) {
				$Qget2 = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_navigation: WHERE tool_id=:tool_id: AND navigation_parent_id=:navigation_parent_id: ORDER BY navigation_sortorder ASC');
				$Qget2->bindTable(':table_vis_navigation:', 'vis_navigation');
				$Qget2->bindInt(':tool_id:', $_POST['tool_id']);
				$Qget2->bindInt(':navigation_parent_id:', $Qget->result['navigation_id']);
				$Qget2->execute();
				if ($Qget2->numberOfRows()>0) {
					while ($Qget2->next()) {
						$output[]='$_vis_script[\'navigation\'][]=array(';
						$output[]='	\'navigation_parent_id\'=>\''.$QgetPage->result['page_name_intern'].'\',';
						$output[]='	\'navigation_title\'=>\''.$Qget2->result['navigation_title'].'\',';
						$output[]='	\'navigation_sortorder\'=>'.$Qget2->result['navigation_sortorder'].',';
						$output[]='	\'navigation_ispublic\'=>'.$Qget2->result['navigation_ispublic'].',';
						$QgetPage2 = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_page: WHERE tool_id=:tool_id: AND page_id=:page_id:');
						$QgetPage2->bindTable(':table_vis_page:', 'vis_page');
						$QgetPage2->bindInt(':tool_id:', $_POST['tool_id']);
						$QgetPage2->bindInt(':page_id:', $Qget2->result['page_id']);
						$QgetPage2->execute();
						if ($QgetPage2->numberOfRows()==1) {
							$QgetPage2->next();
							$output[]='	\'page\'=>array(';
							$output[]='		\'page_name\'=>\''.$QgetPage2->result['page_name'].'\',';
							$output[]='		\'page_name_intern\'=>\''.$QgetPage2->result['page_name_intern'].'\',';
							$output[]='		\'page_description\'=>\''.$QgetPage2->result['page_description'].'\',';
							$output[]='		\'page_ispublic\'=>'.$QgetPage2->result['page_ispublic'].',';

							$QgetPerm = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_page_permission: WHERE tool_id=:tool_id: AND page_id=:page_id:');
							$QgetPerm->bindTable(':table_vis_page_permission:', 'vis_page_permission');
							$QgetPerm->bindInt(':tool_id:', $_POST['tool_id']);
							$QgetPerm->bindInt(':page_id:', $Qget2->result['page_id']);
							$QgetPerm->execute();
							$ar_perm=array();
							if ($QgetPerm->numberOfRows()>0) {
								while ($QgetPerm->next()) {
									$ar_perm[$QgetPerm->result['permission_flag']]=$QgetPerm->result['permission_flag'];
								}
							}
							if ($ar_perm==array()) {
								$output[]='		\'permission\'=>array(),';
							} else {
								$output[]='		\'permission\'=>array(\''.implode('\',\'', $ar_perm).'\'),';
							}
							$output[]='	),';
							$output[]='	\'permission\'=>array(';

							foreach ($ar_groups as $group_id) {
								$QgetPerm = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_group_permission: WHERE group_id=:group_id: AND permission_page=:permission_page:');
								$QgetPerm->bindTable(':table_vis_group_permission:', 'vis_group_permission');
								$QgetPerm->bindInt(':group_id:', $group_id);
								$QgetPerm->bindValue(':permission_page:', $QgetPage2->result['page_name_intern']);
								$QgetPerm->execute();
								$ar_perm=array();
								if ($QgetPerm->numberOfRows()>0) {
									while ($QgetPerm->next()) {
										$ar_perm[$QgetPerm->result['permission_flag']]=$QgetPerm->result['permission_flag'];
									}
								}
								if ($ar_perm!=array()) {
									$output[]='		'.$group_id.'=>array(\''.implode('\',\'', $ar_perm).'\'),';
								}
							}
							$output[]='	),';
						}
						$output[]=');';

						# level 2
						if (isset($Qget2->result['navigation_id'])) {
							$Qget3 = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_navigation: WHERE tool_id=:tool_id: AND navigation_parent_id=:navigation_parent_id: ORDER BY navigation_sortorder ASC');
							$Qget3->bindTable(':table_vis_navigation:', 'vis_navigation');
							$Qget3->bindInt(':tool_id:', $_POST['tool_id']);
							$Qget3->bindInt(':navigation_parent_id:', $Qget2->result['navigation_id']);
							$Qget3->execute();
							if ($Qget3->numberOfRows()>0) {
								while ($Qget3->next()) {
									$output[]='$_vis_script[\'navigation\'][]=array(';
									$output[]='	\'navigation_parent_id\'=>\''.$QgetPage2->result['page_name_intern'].'\',';
									$output[]='	\'navigation_title\'=>\''.$Qget3->result['navigation_title'].'\',';
									$output[]='	\'navigation_sortorder\'=>'.$Qget3->result['navigation_sortorder'].',';
									$output[]='	\'navigation_ispublic\'=>'.$Qget3->result['navigation_ispublic'].',';
									$QgetPage3 = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_page: WHERE tool_id=:tool_id: AND page_id=:page_id:');
									$QgetPage3->bindTable(':table_vis_page:', 'vis_page');
									$QgetPage3->bindInt(':tool_id:', $_POST['tool_id']);
									$QgetPage3->bindInt(':page_id:', $Qget3->result['page_id']);
									$QgetPage3->execute();
									if ($QgetPage3->numberOfRows()==1) {
										$QgetPage3->next();
										$output[]='	\'page\'=>array(';
										$output[]='		\'page_name\'=>\''.$QgetPage3->result['page_name'].'\',';
										$output[]='		\'page_name_intern\'=>\''.$QgetPage3->result['page_name_intern'].'\',';
										$output[]='		\'page_description\'=>\''.$QgetPage3->result['page_description'].'\',';
										$output[]='		\'page_ispublic\'=>'.$QgetPage3->result['page_ispublic'].',';

										$QgetPerm = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_page_permission: WHERE tool_id=:tool_id: AND page_id=:page_id:');
										$QgetPerm->bindTable(':table_vis_page_permission:', 'vis_page_permission');
										$QgetPerm->bindInt(':tool_id:', $_POST['tool_id']);
										$QgetPerm->bindInt(':page_id:', $Qget3->result['page_id']);
										$QgetPerm->execute();
										$ar_perm=array();
										if ($QgetPerm->numberOfRows()>0) {
											while ($QgetPerm->next()) {
												$ar_perm[$QgetPerm->result['permission_flag']]=$QgetPerm->result['permission_flag'];
											}
										}
										if ($ar_perm!=array()) {
											$output[]='		\'permission\'=>array(\''.implode('\',\'', $ar_perm).'\'),';
										}
										$output[]='	),';
										$output[]='	\'permission\'=>array(';

										foreach ($ar_groups as $group_id) {
											$QgetPerm = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_group_permission: WHERE group_id=:group_id: AND permission_page=:permission_page:');
											$QgetPerm->bindTable(':table_vis_group_permission:', 'vis_group_permission');
											$QgetPerm->bindInt(':group_id:', $group_id);
											$QgetPerm->bindValue(':permission_page:', $QgetPage3->result['page_name_intern']);
											$QgetPerm->execute();
											$ar_perm=array();
											if ($QgetPerm->numberOfRows()>0) {
												while ($QgetPerm->next()) {
													$ar_perm[$QgetPerm->result['permission_flag']]=$QgetPerm->result['permission_flag'];
												}
											}
											if ($ar_perm!=array()) {
												$output[]='		'.$group_id.'=>array(\''.implode('\',\'', $ar_perm).'\'),';
											}
										}
										$output[]='	),';
									}
									$output[]=');';
								}
							}
						}
					}
				}
			}
		}
	}

	$output[]='';

	$script=eval(implode("\n", $output));

	#$reload=true;
}

if ($reload===true) {
	header('Location: '.$_SERVER['PHP_SELF']);
}

?>

<div class="toolbox toolbox_core toolbox_cacheclear">
	<h1><a title="Tools" href="../">osWFrame</a> - <?php echo osW_Tool::getInstance()->getToolValue('name');?><span><a href="javascript:window.location.reload()">↻</a></span></h1>
<form name="form_clear" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table class="core_table">
	<tr class="core_data core_data_1">
		<td class="core_title">Tool</td>
		<td class="core_value"><?php echo osW_Tool_Form::getInstance()->drawSelectField('tool_id', $tool, '')?></td>
	</tr>
<?php if ((isset($_POST['action']))&&($_POST['action']=='docreate')&&(isset($_POST['tool_id']))&&(isset($tool[$_POST['tool_id']]))&&($_POST['tool_id']!=0)):?>
	<tr class="core_data core_data_1">
		<td colspan="2" class="core_value"><?php highlight_string('<?php'."\n".implode("\n", $output)."\n".'?>')?></td>
	</tr>
<?php endif?>
	<tr class="core_data core_submit">
		<td class="core_title">&nbsp;</td>
		<td class="core_value"><input type="submit" name="create" value="create"/></td>
	</tr>
</table>
<input type="hidden" name="action" value="docreate"/>
</form>
</div>

<?php

echo osW_Tool_Template::getInstance()->outputInfo();

echo osW_Tool_Template::getInstance()->outputFooter();

?>