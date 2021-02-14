<?php if((vOut('vis_navigation_enabled')!==false)):?>
<div id="container_tool_navigation">
	<?php echo $this->fetchFileIfExists('content_top', vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/');?>

	<?php if((vOut('vis_toolswitch')===true)&&(count(osW_VIS::getInstance()->getToolsSelectArraybyPermission())>1)):?>
	<div id="container_tool_switch">
	<?php echo osW_Form::getInstance()->drawSelectField('vis_tool_id', osW_VIS::getInstance()->getToolsSelectLogonArraybyPermission(), osW_VIS::getInstance()->getTool())?>
	</div>
	<?php endif?>
	<div id="container_tool_navigation_logo">
		<div style="text-align:<?php echo vOut('vis_logo_navi_align');?>">
		<?php echo $this->getOptimizedImage(vOut('vis_logo_navi_name'), array('module'=>vOut('vis_logo_navi_module'), 'title'=>vOut('vis_logo_navi_title'), 'longest'=>vOut('vis_logo_navi_longest'), 'height'=>vOut('vis_logo_navi_height'), 'width'=>vOut('vis_logo_navi_width')))?>
		</div>
	</div>
	<?php if (($tool_details['tool_use_mandant']==1)&&($tool_details['tool_use_mandantswitch']==1)):?>
	<div id="container_tool_navigation_mandant">
		<h1>Mandant:</h1><?php echo osW_Form::getInstance()->drawSelectField('vis_mandant_id', $mandanten, osW_VIS_Mandant::getInstance()->getSessionMandant())?>
	</div>
	<?php endif?>
	<?php echo $this->fetchFileIfExists('content_center', vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/');?>
	<div id="container_tool_navigation_list">
		<ul class="container_tool_navigation_list_level_1">
		<?php foreach(osW_VIS_Navigation::getInstance()->getNavigationWithPermission(0, 2) as $navigation_element):?>
			<?php if($navigation_element['info']['permission_link']==true):?>
				<li><h1><?php if($navigation_element['info']['permission_view']==true):?><a <?php if($navigation_element['info']['navigation_active']===true):?>class="active"<?php endif?> href="<?php echo osW_Template::getInstance()->buildHrefLink('vis', 'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.$navigation_element['info']['page_name_intern'])?>"><?php endif?><?php echo h()->_outputString($navigation_element['info']['navigation_title'])?><?php if($navigation_element['info']['permission_view']==true):?></a><?php endif?><span class="vis_navigation_toggle <?php echo $navigation_element['info']['page_name_intern']?><?php if(osW_VIS_User::getInstance()->getUserData('compressed_'.osW_VIS::getInstance()->getTool().'_'.$navigation_element['info']['page_name_intern'])==1):?> expanded<?php endif?>"><?php if(osW_VIS_User::getInstance()->getUserData('compressed_'.osW_VIS::getInstance()->getTool().'_'.$navigation_element['info']['page_name_intern'])==1):?>▼<?php else:?>▲<?php endif?></span></h1>
				<?php if(count($navigation_element['links'])>0):?>
					<ul class="container_tool_navigation_list_level_2" id="<?php echo $navigation_element['info']['page_name_intern']?>" <?php if(osW_VIS_User::getInstance()->getUserData('compressed_'.osW_VIS::getInstance()->getTool().'_'.$navigation_element['info']['page_name_intern'])==1):?> style="display:none;"<?php endif?>>
					<?php foreach($navigation_element['links'] as $navigation_element):?>
						<?php if($navigation_element['info']['permission_link']==true):?>
						<li><?php if($navigation_element['info']['permission_view']==true):?><a <?php if($navigation_element['info']['navigation_active']===true):?>class="active"<?php endif?> href="<?php echo osW_Template::getInstance()->buildHrefLink('vis', 'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.$navigation_element['info']['page_name_intern'])?>"><?php endif?><?php echo h()->_outputString($navigation_element['info']['navigation_title'])?><?php if(osW_VIS_Badge::getInstance()->get($navigation_element['info']['page_name_intern'])>=0):?><span class="vis_navigation_badge" title="<?php echo osW_VIS_Badge::getInstance()->getReal($navigation_element['info']['page_name_intern'])?>"><?php echo osW_VIS_Badge::getInstance()->get($navigation_element['info']['page_name_intern'])?></span><?php endif?><?php if($navigation_element['info']['permission_view']==true):?></a><?php endif?></li>
						<?php endif?>
					<?php endforeach?>
					</ul>
				<?php endif?>
				</li>
			<?php endif?>
		<?php endforeach?>
		</ul>
	</div>
	<?php echo $this->fetchFileIfExists('content_bottom', vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/');?>
</div>
<?php endif?>

<div id="container_tool_frame">
	<div id="container_tool_content">

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

		<?php echo $viscontent?>
	</div>
</div>