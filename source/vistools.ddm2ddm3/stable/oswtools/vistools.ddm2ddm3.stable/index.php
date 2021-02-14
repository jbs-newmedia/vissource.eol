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
define('package', 'vistools.ddm2ddm3');
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

$tools=array();
if ($dbstatus->error===false) {
	$Qget = osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis_tool: WHERE 1');
	$Qget->bindTable(':table_vis_tool:', 'vis_tool');
	$Qget->execute();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			$tools[$Qget->result['tool_id']]=$Qget->result;
		}
	}
}

$script=array();
$script[0]='';
if (count($tools)>0) {
	foreach ($tools as $tool) {
		$scripts=glob(root_path.'/modules/vis/vistools/'.$tool['tool_name_intern'].'/php/*.php');
		if (count($scripts)>0) {
			foreach ($scripts as $script_name) {
				$script_content=file_get_contents($script_name);
				if (strstr($script_content, 'osW_DDM::getInstance()')) {
					$script[$tool['tool_name_intern'].'____'.str_replace('.inc.php', '', str_replace(root_path.'/modules/vis/vistools/'.$tool['tool_name_intern'].'/php/', '', $script_name))]=$tool['tool_name'].' -> '.str_replace(root_path.'/modules/vis/vistools/'.$tool['tool_name_intern'].'/php/', '', $script_name);
				}
			}
		}
	}
}


$reload=false;

if ((isset($_POST['action']))&&($_POST['action']=='docreate')&&(isset($_POST['script2ddm3']))&&(isset($script[$_POST['script2ddm3']]))&&($_POST['script2ddm3']!='0')) {
	$tmp=explode('____', $_POST['script2ddm3']);
	if (count($tmp)!=2) {
		break;
	}

	$content_tool=$tmp[0];
	$content_script=$tmp[1];

	$file_content=file_get_contents(root_path.'/modules/vis/vistools/'.$content_tool.'/php/'.$content_script.'.inc.php');

	#preg_match('/\$ddm_group([\ ]{0,1})=([\ ]{0,1})([\'\"]{1,1})(.*)([\'\"]{1,1})/', $file_content, $matches);
	#print_a($matches);

	#if (isset($matches[4]) {
		#$ddm_group=$matches[4];
	#}

	$start=strpos($file_content, '$ddm_group');
	$end=strpos($file_content, 'osW_DDM::getInstance()->runDDMPHP($ddm_group);');

	$file_content=substr($file_content, $start, $end-$start);
	preg_match_all('/(osW_DDM\:\:getInstance\(\)\-\>)(.*)(\)\);)/Uis', $file_content, $matches);

	$ar_ddm3=$matches[0];
	$ar_ddm3_new=array();

	foreach ($ar_ddm3 as $key => $index) {
		$ar_ddm3_new[$key]='osW_Tool_DDM'.substr($index, 7);
	}

	$output=array();

	foreach ($ar_ddm3_new as $key => $index) {
		$index=osW_Tool_DDM::getInstance()->evalCode($index);
		if ($index!=null) {
			$output[]=$index;
		}
	}

	foreach (osW_Tool_DDM::getInstance()->getElements() as $index) {
		$output[]=$index;
	}


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
		<td class="core_title">Script</td>
		<td class="core_value"><?php echo osW_Tool_Form::getInstance()->drawSelectField('script2ddm3', $script, '')?></td>
	</tr>
<?php if ((isset($_POST['action']))&&($_POST['action']=='docreate')&&(isset($_POST['script2ddm3']))&&(isset($script[$_POST['script2ddm3']]))&&($_POST['script2ddm3']!='0')):?>
	<tr class="core_data core_data_1">
		<td colspan="2" class="core_value"><?php highlight_string('<?php'."\n\n// code ...\n\n".implode("\n\n", $output)."\n\n// code ...\n\n".'?>')?></td>
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