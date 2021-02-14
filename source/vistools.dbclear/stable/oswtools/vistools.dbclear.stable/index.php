<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Updater
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

$GLOBALS['DEBUGLIB_MAX_Y']=5000;

define('abs_path', str_replace('\\', '/', str_replace(basename(dirname(__FILE__)), '', dirname(__FILE__))));

define('serverlist', 'vis');
define('package', 'vistools.dbclear');
define('release', 'stable');

include(abs_path.'resources/includes/header.inc.php');

osW_Tool::getInstance()->initTool(package, release);

osW_Tool_Server::getInstance()->readServerList(serverlist);
osW_Tool_Server::getInstance()->updatePackageList(serverlist);
osW_Tool::getInstance()->update(serverlist);

// TOOL - BEGIN

$db=array(
	'type'=>'mysql',
	'database'=>osW_Tool::getInstance()->getFrameConfig('database_db'),
	'server'=>osW_Tool::getInstance()->getFrameConfig('database_server'),
	'username'=>osW_Tool::getInstance()->getFrameConfig('database_username'),
	'password'=>osW_Tool::getInstance()->getFrameConfig('database_password'),
	'pconnect'=>false,
	'prefix'=>osW_Tool::getInstance()->getFrameConfig('database_prefix')
);

osW_Tool_Database::addDatabase('default', $db);
$dbstatus=osW_Tool_Database::connect('default', $db);

$dbclean=array();
$dbclean2delete=array();

if ($dbstatus->error===false) {

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_tool: WHERE 1');
	$Qget->bindTable(':table_vis_tool:', 'vis_tool');
	$Qget->execute();
	$dbclean2delete['vis_tool']=array();
	$dbclean['vis_tool']=array();
	$dbclean['vis_tool_array']=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			$dbclean['vis_tool'][$Qget->result['tool_id']]=$Qget->result;
			$dbclean['vis_tool_array'][]=$Qget->result['tool_id'];
		}
	}

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_mandant: WHERE 1');
	$Qget->bindTable(':table_vis_mandant:', 'vis_mandant');
	$Qget->execute();
	$dbclean2delete['vis_mandant']=array();
	$dbclean['vis_mandant']=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			if (in_array($Qget->result['tool_id'], $dbclean['vis_tool_array'])) {
				$dbclean['vis_mandant'][$Qget->result['mandant_id']]=$Qget->result;
				$dbclean['vis_mandant'][$Qget->result['tool_id']][]=$Qget->result['mandant_id'];
			} else {
				$dbclean2delete['vis_mandant'][]=$Qget->result;
			}
		}
	}

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_permission: WHERE 1');
	$Qget->bindTable(':table_vis_permission:', 'vis_permission');
	$Qget->execute();
	$dbclean2delete['vis_permission']=array();
	$dbclean['vis_permission']=array();
	$dbclean['vis_permission_array']=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			if (in_array($Qget->result['tool_id'], $dbclean['vis_tool_array'])) {
				$dbclean['vis_permission'][$Qget->result['permission_id']]=$Qget->result;
				$dbclean['vis_permission_array'][$Qget->result['tool_id']][]=$Qget->result['permission_flag'];
			} else {
				$dbclean2delete['vis_permission'][]=$Qget->result;
			}
		}
	}

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_page: WHERE 1');
	$Qget->bindTable(':table_vis_page:', 'vis_page');
	$Qget->execute();
	$dbclean2delete['vis_page']=array();
	$dbclean['vis_page']=array();
	$dbclean['vis_page_array']=array();
	$dbclean['vis_page_array_name']=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			if (in_array($Qget->result['tool_id'], $dbclean['vis_tool_array'])) {
				$dbclean['vis_page'][$Qget->result['page_id']]=$Qget->result;
				$dbclean['vis_page_array'][$Qget->result['tool_id']][]=$Qget->result['page_id'];
				$dbclean['vis_page_array_name'][$Qget->result['tool_id']][]=$Qget->result['page_name_intern'];
			} else {
				$dbclean2delete['vis_page'][]=$Qget->result;
			}
		}
	}

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_page_permission: WHERE 1');
	$Qget->bindTable(':table_vis_page_permission:', 'vis_page_permission');
	$Qget->execute();
	$dbclean2delete['vis_page_permission']=array();
	$dbclean['vis_page_permission']=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			if ((in_array($Qget->result['tool_id'], $dbclean['vis_tool_array']))&&(in_array($Qget->result['page_id'], $dbclean['vis_page_array'][$Qget->result['tool_id']]))&&(in_array($Qget->result['permission_flag'], $dbclean['vis_permission_array'][$Qget->result['tool_id']]))) {
				if (isset($dbclean['vis_page_permission'][$Qget->result['page_id']])) {
					$Qget->result['permission_flags']=array_merge($dbclean['vis_page_permission'][$Qget->result['page_id']]['permission_flags'], array($Qget->result['permission_flag']));
					$dbclean['vis_page_permission'][$Qget->result['page_id']]=$Qget->result;
				} else {
					$dbclean['vis_page_permission'][$Qget->result['page_id']]=$Qget->result;
					$dbclean['vis_page_permission'][$Qget->result['page_id']]['permission_flags']=array($Qget->result['permission_flag']);
				}
			} else {
				$dbclean2delete['vis_page_permission'][]=$Qget->result;
			}
		}
	}

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_navigation: WHERE 1 ORDER BY navigation_parent_id ASC');
	$Qget->bindTable(':table_vis_navigation:', 'vis_navigation');
	$Qget->execute();
	$dbclean2delete['vis_navigation']=array();
	$dbclean['vis_navigation']=array();
	$dbclean['vis_navigation_array']=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			if ((in_array($Qget->result['tool_id'], $dbclean['vis_tool_array']))&&(($Qget->result['navigation_parent_id']==0)||(in_array($Qget->result['navigation_parent_id'], $dbclean['vis_navigation_array'][$Qget->result['tool_id']])))) {
				$dbclean['vis_navigation'][$Qget->result['navigation_id']]=$Qget->result;
				$dbclean['vis_navigation_array'][$Qget->result['tool_id']][]=$Qget->result['navigation_id'];
			} else {
				$dbclean2delete['vis_navigation'][]=$Qget->result;
			}
		}
	}

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_group: WHERE 1');
	$Qget->bindTable(':table_vis_group:', 'vis_group');
	$Qget->execute();
	$dbclean2delete['vis_group']=array();
	$dbclean['vis_group']=array();
	$dbclean['vis_group_array']=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			if (in_array($Qget->result['tool_id'], $dbclean['vis_tool_array'])) {
				$dbclean['vis_group'][$Qget->result['group_id']]=$Qget->result;
				$dbclean['vis_group_array'][$Qget->result['tool_id']][]=$Qget->result['group_id'];
			} else {
				$dbclean2delete['vis_group'][]=$Qget->result;
			}
		}
	}

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_group_permission: AS gp LEFT JOIN :table_vis_group: AS g ON (gp.group_id=g.group_id) LEFT JOIN :table_vis_page: AS p ON (p.page_name_intern=gp.permission_page AND p.tool_id=g.tool_id) WHERE 1');
	$Qget->bindTable(':table_vis_group_permission:', 'vis_group_permission');
	$Qget->bindTable(':table_vis_group:', 'vis_group');
	$Qget->bindTable(':table_vis_page:', 'vis_page');
	$Qget->execute();
	$vis_group_cache=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			$vis_group_cache[]=$Qget->result;
		}
	}

	$dbclean2delete['vis_group_permission']=array();
	$dbclean['vis_group_permission']=array();
	foreach ($dbclean['vis_group'] as $group) {
		foreach ($vis_group_cache as $key => $vis_group_permission) {
			if ((isset($dbclean['vis_group_array'][$group['tool_id']]))&&(isset($dbclean['vis_page_array_name'][$group['tool_id']]))&&(isset($dbclean['vis_permission_array'][$group['tool_id']]))&&(isset($dbclean['vis_page_permission'][$vis_group_permission['page_id']]))&&(in_array($vis_group_permission['group_id'], $dbclean['vis_group_array'][$group['tool_id']]))&&(in_array($vis_group_permission['permission_page'], $dbclean['vis_page_array_name'][$group['tool_id']]))&&(in_array($vis_group_permission['permission_flag'], $dbclean['vis_permission_array'][$group['tool_id']]))&&(in_array($vis_group_permission['permission_flag'], $dbclean['vis_page_permission'][$vis_group_permission['page_id']]['permission_flags']))) {
				$dbclean['vis_group_permission'][$group['tool_id']][]=$vis_group_permission;
				unset($vis_group_cache[$key]);
			}
		}
	}

	foreach ($vis_group_cache as $vis_group_permission) {
		$dbclean2delete['vis_group_permission'][]=$vis_group_permission;
	}

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_user: WHERE 1');
	$Qget->bindTable(':table_vis_user:', 'vis_user');
	$Qget->execute();
	$dbclean2delete['vis_user']=array();
	$dbclean['vis_user']=array();
	$dbclean['vis_user_array']=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			$dbclean['vis_user'][]=$Qget->result;
			$dbclean['vis_user_array'][]=$Qget->result['user_id'];
		}
	}

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_user_pref: WHERE 1');
	$Qget->bindTable(':table_vis_user_pref:', 'vis_user_pref');
	$Qget->execute();
	$dbclean2delete['vis_user_pref']=array();
	$dbclean['vis_user_pref']=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			if (in_array($Qget->result['user_id'], $dbclean['vis_user_array'])) {
				$dbclean['vis_user_pref'][]=$Qget->result;
			} else {
				$dbclean2delete['vis_user_pref'][]=$Qget->result;
			}
		}
	}

	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_user_tool: WHERE 1');
	$Qget->bindTable(':table_vis_user_tool:', 'vis_user_tool');
	$Qget->execute();
	$dbclean2delete['vis_user_tool']=array();
	$dbclean['vis_user_tool']=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			if ((in_array($Qget->result['tool_id'], $dbclean['vis_tool_array']))&&(in_array($Qget->result['user_id'], $dbclean['vis_user_array']))) {
				$dbclean['vis_user_tool'][$Qget->result['tool_id']]=$Qget->result;
			} else {
				$dbclean2delete['vis_user_tool'][]=$Qget->result;
			}
		}
	}



	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_user_group: WHERE 1');
	$Qget->bindTable(':table_vis_user_group:', 'vis_user_group');
	$Qget->execute();
	$vis_user_group_cache=array();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			$vis_user_group_cache[]=$Qget->result;
		}
	}

	$dbclean2delete['vis_user_group']=array();
	$dbclean['vis_user_group']=array();
	foreach ($dbclean['vis_group'] as $group) {
		foreach ($vis_user_group_cache as $key => $vis_user_group) {
			if ((in_array($vis_user_group['group_id'], $dbclean['vis_group_array'][$group['tool_id']]))&&(in_array($vis_user_group['user_id'], $dbclean['vis_user_array']))&&(in_array($group['tool_id'], $dbclean['vis_tool_array']))) {
				$dbclean['vis_user_group'][]=$vis_user_group;
				unset($vis_user_group_cache[$key]);
			}
		}
	}

	foreach ($vis_user_group_cache as $vis_user_group) {
		$dbclean2delete['vis_user_group'][]=$vis_user_group;
	}


	ksort($dbclean2delete);
}

$reload=false;

if ((isset($_POST['action']))&&($_POST['action']=='doclear')) {
	if(count($dbclean2delete)>0) {
		foreach ($dbclean2delete as $key => $entries) {
			if (count($entries)>0) {
				foreach ($entries as $entry) {
					osW_Tool_VIS_DBClear::getInstance()->clearEntry($key, $entry);
				}
			}
		}
	}

	$reload=true;
}

if ($reload===true) {
	header('Location: '.$_SERVER['PHP_SELF']);
}

echo osW_Tool_Template::getInstance()->outputHeader(array('../resources/css/layout_core.css', '../resources/css/vis.dbclear.css'), array('../resources/js/jquery.js'));

?>

<div class="toolbox toolbox_core toolbox_cacheclear">
	<h1><a title="Tools" href="../">osWFrame</a> - <?php echo osW_Tool::getInstance()->getToolValue('name');?><span><a href="javascript:window.location.reload()">↻</a></span></h1>
<form name="form_clear" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table class="core_table">
	<tr class="core_data core_header">
		<th class="core_title">Table</th>
		<th class="core_value">Entries</th>
	</tr>
<?php if(count($dbclean2delete)>0):?>
<?php $i=0;foreach ($dbclean2delete as $key=>$values):$i++;?>
	<tr class="core_data core_data_<?php echo bcmod($i, 2);?>">
		<td class="core_title"><?php echo $key?></td>
		<td class="core_value"><?php echo count($values)?></td>
	</tr>
<?php endforeach?>
	<tr class="core_data core_submit">
		<td class="core_title">&nbsp;</td>
		<td class="core_value"><input type="submit" name="clear" value="clear"/></td>
	</tr>
<?php else:?>
	<tr class="core_data core_blank">
		<td colspan="2">no entries to delete</td>
	</tr>
<?php endif?>
</table>
<input type="hidden" name="action" value="doclear"/>
</form>
</div>

<?php

echo osW_Tool_Template::getInstance()->outputInfo();

echo osW_Tool_Template::getInstance()->outputFooter();

?>