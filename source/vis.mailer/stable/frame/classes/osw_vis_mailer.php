<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS_Mailer extends osW_Object {

	/*** PROPERTIES ***/

	private $data=array();
	public $variables=array();
	public $obj_mailer;

	/*** METHODS CORE ***/


	public function __construct() {
		parent::__construct(__CLASS__, 1, 1);
		$this->setIndexFile();
		$this->clearMailer();
		$this->setCharSet('utf-8');
	}

	public function __destruct() {
		parent::__destruct();
	}

	/*** METHODS ***/

	public function setIndexFile($file='index', $module='', $dir='modules') {
		return $this->setFile($file, $module, $dir, 'index');
	}

	public function setContentFile($file, $module='', $dir='modules') {
		return $this->setFile($file, $module, $dir, 'content');
	}

	public function setFile($file, $module='', $dir='modules', $part='content') {
		if ($module=='') {
			$module='vis';
		}
		$fetchfile=$file;
		if ($this->isfetchFile($fetchfile, $module, $dir)===true) {
			if ($part=='index') {
				$this->index_file=vOut('settings_abspath').$dir.'/'.$module.'/mail/'.$fetchfile.'.tpl.php';
			} else {
				$this->content_file=vOut('settings_abspath').$dir.'/'.$module.'/mail/'.$fetchfile.'.tpl.php';
			}

			return true;
		}

		$this->index_file='';
		return false;
	}

	public function getIndexFile() {
		return $this->index_file;
	}

	public function getContentFile() {
		return $this->content_file;
	}

	public function setVar($name, $value) {
		$this->variables[$name]=$value;
	}

	public function setLogo($name='', $module='', $title='', $longest=75, $height=75, $width=75) {
		if ($name=='') {
			$name=vOut('vis_logo_navi_name');
		}
		if ($module=='') {
			$module=vOut('vis_logo_navi_module');
		}
		if ($title=='') {
			$title=vOut('vis_logo_navi_title');
		}
		$this->data['logo']=array(
			'name'=>$name,
			'module'=>$module,
			'title'=>$title,
			'longest'=>$longest,
			'height'=>$height,
			'width'=>$width,
		);
	}

	public function setTool($name='', $main='VIS') {
		if ($name=='') {
			$name=osW_VIS::getInstance()->getToolName();
		}
		$this->data['tool']=array(
			'main'=>$main,
			'name'=>$name,
		);
	}

	public function isfetchFile($file, $module='', $dir='modules') {
		if ($module=='') {
			$module='vis';
		}
		if (file_exists(vOut('settings_abspath').$dir.'/'.$module.'/mail/'.$file.'.tpl.php')===true) {
			return true;
		}
		return false;
	}

	public function fetchIfExists($file) {
		if (file_exists($file)) {
			return $this->fetch($file);
		}
		return '';
	}

	public function fetch($file) {
		extract($this->variables);
		ob_start();
		include ($file);
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	public function getOptimizedImage($filename, $options=array()) {
		if (!isset($options['module'])) {
			$options['module']='';
		}
		if (!isset($options['path'])) {
			if (($options['module']=='')||($options['module']=='default')) {
				$options['module']=vOut('project_default_module');
			}
			if ($options['module']=='current') {
				$options['module']=vOut('frame_current_module');
			}

			$options['path']='modules/'.$options['module'].'/img/';
		}

		if (isset($options['subdir'])) {
			$options['path'].=$options['subdir'].'/';
		}

		$rel_file=$options['path'].$filename;
		$abs_file=vOut('settings_abspath').$rel_file;
		if (!file_exists($abs_file)) {
			$this->logMessage(__CLASS__, 'error', array('time'=>time(),'line'=>__LINE__,'function'=>__FUNCTION__,'error'=>'File not found ('.$rel_file.')'));
			return '';
		}

		$opt_options=osW_ImageOptimizer::getInstance()->getOptionsArrayFromArray($options);

		$path_filename=pathinfo($abs_file, PATHINFO_FILENAME);
		$path_extension=pathinfo($abs_file, PATHINFO_EXTENSION);

		if (!isset($options['alt'])) {
			$options['alt']=$path_filename;
		}

		if (!isset($options['title'])) {
			$options['title']='';
		}

		if (!isset($options['parameter'])) {
			$options['parameter']='';
		}

		if (vOut('imageoptimizer_protect_files')===true) {
			$opt_options['ps']=substr(md5($path_filename.'.'.$path_extension.'#'.osW_ImageOptimizer::getInstance()->getOptionsArrayToString($opt_options).'#'.vOut('settings_protection_salt')), 3, 6);
		}

		$imgopt=array();
		foreach ($opt_options as $key=>$value) {
			if (strlen($value)>0) {
				$imgopt[]=$key.'_'.$value;
			}
		}

		if (!empty($imgopt)) {
			$new_filename=$path_filename.'.'.implode('-', $imgopt).'.'.$path_extension;
		} else {
			$new_filename=$path_filename.'.'.$path_extension;
		}

		$out='';
		$out.='<img '.$options['parameter'].' src="'.osW_Seo::getInstance()->getBaseUrl().'static/'.vOut('settings_imageoptimizer').'/'.$options['path'].$new_filename;
		# TODO: height/width ermitteln und angeben
		$out.='" alt="'.h()->_outputString($options['alt']).'" title="'.h()->_outputString($options['title']).'" />';

		return $out;
	}

	public function getMailerObject() {
		if (!is_object($this->obj_mailer)) {
			$this->obj_mailer=new PHPMailer(false);
		}
		return $this->obj_mailer;
	}

	public function setSMTPDebug($value) {
		$this->getMailerObject()->SMTPDebug=$value;
	}

	public function setHost($value) {
		$this->getMailerObject()->Host=$value;
	}

	public function setPort($value) {
		$this->getMailerObject()->Port=intval($value);
	}

	public function setCharSet($value) {
		$this->getMailerObject()->CharSet=$value;
	}

	public function setEncoding($value) {
		$this->getMailerObject()->Encoding=$value;
	}

	public function setUsername($value) {
		$this->getMailerObject()->Username=$value;
	}

	public function setPassword($value) {
		$this->getMailerObject()->Password=$value;
	}

	public function setSMTPAuth($value) {
		if ($value===true) {
			$this->getMailerObject()->SMTPAuth=true;
		} else {
			$this->getMailerObject()->SMTPAuth=false;
		}
	}

	public function setSMTPSecure($value) {
		switch ($value) {
			case 'ssl':
				$this->getMailerObject()->SMTPSecure='ssl';
				break;
			case 'tls':
				$this->getMailerObject()->SMTPSecure='tls';
				break;
			default:
				$this->getMailerObject()->SMTPSecure='';
				break;
		}
	}

	public function set($name, $value) {
		return $this->getMailerObject()->set($name, $value);
	}

	public function addCustomHeader($value) {
		$this->getMailerObject()->addCustomHeader($value);
	}

	public function setSubject($value) {
		$this->getMailerObject()->Subject=$value;
	}

	public function MsgHTML($message) {
		$this->isHTML(true);
		return $this->getMailerObject()->MsgHTML($message);
	}

	public function setBody($value) {
		$this->getMailerObject()->Body=$value;
	}

	public function setAltBody($value) {
		$this->getMailerObject()->AltBody=$this->html2plain($value);
	}

	public function isMail() {
		$this->getMailerObject()->IsMail();
	}

	public function isSMTP() {
		$this->getMailerObject()->IsSMTP();
	}

	public function isHTML($ishtml=true) {
		$this->getMailerObject()->IsHTML($ishtml);
	}

	public function isSendmail() {
		$this->getMailerObject()->IsSendmail();
	}

	public function isQmail() {
		$this->getMailerObject()->IsQmail();
	}

	public function setFrom($address, $name='') {
		return $this->getMailerObject()->SetFrom($address, $name);
	}

	public function addAddress($address, $name='') {
		return $this->getMailerObject()->AddAddress($address, $name);
	}

	public function addCC($address, $name='') {
		return $this->getMailerObject()->AddCC($address, $name);
	}

	public function addBCC($address, $name='') {
		return $this->getMailerObject()->AddBCC($address, $name);
	}

	public function addReplyTo($address, $name='') {
		return $this->getMailerObject()->AddReplyTo($address, $name);
	}

	public function send() {
		if (!isset($this->data['logo'])) {
			$this->setLogo();
		}
		if (!isset($this->data['tool'])) {
			$this->setTool();
		}
		$this->setVar('logo', $this->data['logo']);
		$this->setVar('tool', $this->data['tool']);
		$this->setVar('title', $this->getMailerObject()->Subject);
		$this->setVar('content', $this->fetchIfExists($this->getContentFile()));
		$this->getMailerObject()->MsgHTML(($this->fetchIfExists($this->getIndexFile())));
		return $this->getMailerObject()->Send();
	}

	public function addAttachment($path, $name='', $encoding='base64', $type='application/octet-stream') {
		return $this->getMailerObject()->AddAttachment($path, $name, $encoding, $type);
	}

	public function addStringAttachment($string, $filename, $encoding='base64', $type='', $disposition='attachment') {
		return $this->addStringAttachment($string, $filename, $encoding, $type, $disposition);
	}

	public function addEmbeddedImage($path, $cid, $name='', $encoding='base64', $type='application/octet-stream') {
		return $this->getMailerObject()->AddEmbeddedImage($path, $cid, $name, $encoding, $type);
	}

	public function clearAddresses() {
		$this->getMailerObject()->ClearAddresses();
	}

	public function clearCCs() {
		$this->getMailerObject()->ClearCCs();
	}

	public function clearBCCs() {
		$this->getMailerObject()->ClearBCCs();
	}

	public function clearReplyTos() {
		$this->getMailerObject()->ClearReplyTos();
	}

	public function clearAllRecipients() {
		$this->getMailerObject()->ClearAllRecipients();
	}

	public function clearAttachments() {
		$this->getMailerObject()->ClearAttachments();
	}

	public function clearCustomHeaders() {
		$this->getMailerObject()->ClearCustomHeaders();
	}

	public function clearMailer() {
		$this->setSubject('');
		$this->setAltBody('');
		$this->setBody('');
		$this->ClearAddresses();
		$this->ClearCCs();
		$this->ClearBCCs();
		$this->ClearReplyTos();
		$this->ClearAllRecipients();
		$this->ClearAttachments();
		$this->ClearCustomHeaders();
		$this->setCharSet('utf-8');
	}

	public function html2plain($text) {
		$text=preg_replace('/<a[^<]*href="([^"]+)"[^<]*<\/a>/', "\\1", $text);
		$text=str_replace('&amp;', '&', $text);
		$text=str_replace('<br/>', '
', $text);
		$text=str_replace('<br/>', '
', $text);
		return strip_tags($text);
	}


	/**
	 *
	 * @return osW_VIS_Mailer
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>