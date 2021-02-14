<div id="container_login">
	<?php echo $this->getOptimizedImage(vOut('vis_logo_login_name'), array('module'=>vOut('vis_logo_login_module'), 'title'=>vOut('vis_logo_login_title'), 'longest'=>vOut('vis_logo_login_longest'), 'height'=>vOut('vis_logo_login_height'), 'width'=>vOut('vis_logo_login_width')))?>
<h1> Willkommen bei VIS</h1>

<?php echo osW_Form::getInstance()->formStart('form_login', 'current')?>

<?php if(osW_MessageStack::getInstance()->size('session')):?>
<?php foreach (osW_MessageStack::getInstance()->getClass('session') as $type => $messages):?>
	<div class="container_msgbox container_msgbox_<?php echo $type?>">
		<div class="container_head"><?php echo tOut('message_'.$type)?></div>
		<div class="container_fields">
<?php foreach ($messages as $message):?>
		<div class="item">
			<?php echo $message['msg']?>
		</div>
<?php endforeach?>
		</div>
	</div>
<?php endforeach?>
<?php osW_MessageStack::getInstance()->deleteFromSession()?>
<?php endif?>

<?php if(osW_MessageStack::getInstance()->size('form')):?>
<?php foreach (osW_MessageStack::getInstance()->getClass('form') as $type => $messages):?>
	<div class="container_msgbox container_msgbox_<?php echo $type?>">
		<div class="container_head"><?php echo tOut('message_'.$type)?></div>
		<div class="container_fields">
<?php foreach ($messages as $message):?>
			<div class="item">
				<?php echo $message['msg']?>
			</div>
<?php endforeach?>
		</div>
	</div>
<?php endforeach?>
<?php endif?>

	<div id="container_loginbox">
		<div class="container_head">Anmelden</div>
		<div class="container_fields">
			<div class="item">
				<span class="left">E-Mail:</span>
				<span class="right"><?php echo osW_Form::getInstance()->drawTextField('vis_login_email', '', array('input_class'=>'oswinput'))?></span>
			</div>
			<div class="clear"></div>
			<div class="item">
				<span class="left">Passwort:</span>
				<span class="right"><?php echo osW_Form::getInstance()->drawPasswordField('vis_login_password', '', array('input_class'=>'oswinput'))?></span>
			</div>
			<div class="clear"></div>
	<?php if($selector===true):?>
			<div class="item">
				<span class="left">Programm:</span>
				<span class="right"><?php echo osW_Form::getInstance()->drawSelectField('vis_login_tool', osW_VIS::getInstance()->getToolsSelectLogonArray(), '', array('input_class'=>'oswselect'))?></span>
			</div>
			<div class="clear"></div>
	<?php endif?>
		</div>
		<div class="container_footer">
			<?php echo osW_Form::getInstance()->drawSubmit('btn_login', 'OK', array('input_class'=>'oswsubmit'))?>
		</div>
	</div>
<?php echo osW_Form::getInstance()->drawHiddenField ('action', 'dologin')?>
<?php echo osW_Form::getInstance()->formEnd()?>

</div>

<div id="container_version">
	v<?php echo vOut('vis_majorrelease')?>.<?php echo vOut('vis_minorrelease')?>.<?php echo vOut('vis_patchlevel')?><?php echo vOut('vis_version')?>
</div>