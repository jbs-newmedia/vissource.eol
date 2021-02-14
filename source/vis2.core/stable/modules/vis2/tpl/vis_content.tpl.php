<?php if(h()->_catch('modal', '0', 'pg')=='1'):?>
	<?php echo $vis2ontent?>
<?php else:?>
<div id="wrapper">
	<ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

		<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo osW_Template::getInstance()->buildHrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage=vis_dashboard')?>">
			<div class="sidebar-brand-icon">
				<?php echo $this->getOptimizedImage(vOut('vis2_logo_navi_name'), array('module'=>vOut('vis2_logo_navi_module'), 'title'=>vOut('vis2_logo_navi_title'), 'height'=>36))?>
			</div>
			<div class="sidebar-brand-text mx-3"><?php if(vOut('vis2_tool_'.osW_VIS2::getInstance()->getTool().'_title')!=''):?><?php echo vOut('vis2_tool_'.osW_VIS2::getInstance()->getTool().'_title')?><?php else:?><?php echo osW_VIS2::getInstance()->getToolName()?><?php endif?></div>
		</a>

		<hr class="sidebar-divider my-0">

		<li class="nav-item<?php if(osW_VIS2_Navigation::getInstance()->getPage()=='vis_dashboard'):?> active<?php endif?>">
			<a class="nav-link" href="<?php echo osW_Template::getInstance()->buildHrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage=vis_dashboard')?>">
				<span>Dashboard</span>
			</a>
		</li>

<?php if((vOut('vis2_navigation_enabled')!==false)):?>
	<?php foreach(osW_VIS2_Navigation::getInstance()->getNavigationWithPermission(0, 2) as $navigation_element):?>
		<?php if($navigation_element['info']['permission_link']==true):?>
		<hr class="sidebar-divider my-0">
		<li class="nav-item<?php if($navigation_element['info']['navigation_active']==true):?> active<?php endif?>">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#navi_vis2_<?php echo $navigation_element['info']['navigation_id']?>" aria-expanded="true" aria-controls="navi_vis2_<?php echo $navigation_element['info']['navigation_id']?>">
				<span><?php echo h()->_outputString($navigation_element['info']['navigation_title'])?></span>
			</a>
			<?php if(count($navigation_element['links'])>0):?>

			<div id="navi_vis2_<?php echo $navigation_element['info']['navigation_id']?>" class="collapse<?php if($navigation_element['info']['navigation_active']===true):?> show<?php endif?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
				<?php foreach($navigation_element['links'] as $navigation_element):?>
					<?php if($navigation_element['info']['permission_view']==true):?>
					<a class="collapse-item<?php if($navigation_element['info']['navigation_active']===true):?> active<?php endif?>" href="<?php echo osW_Template::getInstance()->buildHrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.$navigation_element['info']['page_name_intern'])?>"><?php endif?><?php echo h()->_outputString($navigation_element['info']['navigation_title'])?><?php if(osW_VIS2_Badge::getInstance()->get($navigation_element['info']['page_name_intern'])>=0):?><span class="vis2_navigation_badge" title="<?php echo osW_VIS2_Badge::getInstance()->getReal($navigation_element['info']['page_name_intern'])?>"><?php echo osW_VIS2_Badge::getInstance()->get($navigation_element['info']['page_name_intern'])?></span><?php endif?><?php if($navigation_element['info']['permission_view']==true):?></a>
					<?php endif?>
				<?php endforeach?>
				</div>
			</div>
			<?php endif?>
		</li>
		<?php endif?>
	<?php endforeach?>
<?php endif?>

		<?php if (($tool_details['tool_use_mandant']==1)&&($tool_details['tool_use_mandantswitch']==1)&&($mandanten!=array())):?>
		<hr class="sidebar-divider my-0">

		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#vis2_mandant" aria-expanded="true" aria-controls="vis2_mandant">
				<span>Mandant wechseln</span>
			</a>

			<div id="vis2_mandant" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
				<?php foreach ($mandanten as $mandant_id => $mandant):?>
				<?php if ($mandant_id>0):?>
					<a class="collapse-item<?php if(osW_VIS2_Mandant::getInstance()->getMandantId()==$mandant_id):?> active<?php endif?>" href="<?php echo osW_Template::getInstance()->buildhrefLink('current', 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage().'&vis2_mandant_id='.$mandant_id)?>"><?php echo h()->_outputString($mandant)?></a>
				<?php endif?>
				<?php endforeach?>
				</div>
			</div>
		</li>
		<?php endif?>

		<?php if((vOut('vis_toolswitch')===true)&&(count(osW_VIS2::getInstance()->getToolsSelectLogonArraybyPermission())>1)):?>
		<hr class="sidebar-divider my-0">

		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#vis2_tool" aria-expanded="true" aria-controls="vis2_tool">
				<span>Tool wechseln</span>
			</a>

			<div id="vis2_tool" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
				<?php foreach(osW_VIS2::getInstance()->getToolsSelectLogonArraybyPermission() as $vis_tool => $vis_tool_name):?>
					<a class="collapse-item<?php if(osW_VIS2::getInstance()->getTool()==$vis_tool):?> active<?php endif?>" href="<?php echo osW_Template::getInstance()->buildHrefLink(vOut('frame_current_module'), 'vistool='.$vis_tool.'&vispage='.osW_VIS2_Navigation::getInstance()->getPage())?>"><?php echo h()->_outputString($vis_tool_name)?></a>
				<?php endforeach?>
				</div>
			</div>
		</li>
		<?php endif?>

		<hr class="sidebar-divider d-none d-md-block">

		<div class="text-center d-none d-md-inline">
			<button class="rounded-circle border-0" id="sidebarToggle"></button>
		</div>

	</ul>







	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">

			<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

				<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
					<i class="fa fa-bars"></i>
				</button>

				<ul class="navbar-nav ml-auto">

<?php if (($tool_details['tool_use_mandant']==1)&&($tool_details['tool_use_mandantswitch']==1)&&($mandanten!=array())):?>



		<li class="nav-item">
			<a class="nav-link disabled">
				<span><?php echo h()->_outputString(osW_VIS2_Mandant::getInstance()->getMandantTitle())?></span>
			</a>
		</li>
<?php endif?>

					<div class="topbar-divider d-none d-sm-block"></div>

					<li class="nav-item dropdown no-arrow">
						<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo h()->_outputString(osW_VIS2_User::getInstance()->getDisplayName(false))?></span>
							<img class="img-profile rounded-circle" src="modules/vis2/img/profile.png">
						</a>
						<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
							<a class="dropdown-item<?php if(osW_VIS2_Navigation::getInstance()->getPage()=='vis_profile'):?> active<?php endif?>" href="<?php echo osW_Template::getInstance()->buildHrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage=vis_profile')?>">
								<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
								Profil
							</a>
							<a class="dropdown-item<?php if(osW_VIS2_Navigation::getInstance()->getPage()=='vis_settings'):?> active<?php endif?>" href="<?php echo osW_Template::getInstance()->buildHrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage=vis_settings')?>">
								<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
								Einstellungen
							</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo osW_Template::getInstance()->buildHrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage=vis_logout')?>">
								<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
								Abmelden
							</a>
						</div>
					</li>
				</ul>
			</nav>

			<div class="container-fluid">

				<?php if(osW_MessageStack::getInstance()->size('form')):?>
				<?php foreach (osW_MessageStack::getInstance()->getClass('form') as $type => $messages):?>
				<?php if($type=='error') { $btype='danger'; } else { $btype=$type; } ?>
				<div class="alert alert-<?php echo $btype?>">
					<strong><?php echo tOut('message_'.$type)?></strong>
					<?php foreach ($messages as $message):?>
					<div class="item">
						<?php echo $message['msg']?>
					</div>
				<?php endforeach?>
				</div>
				<?php endforeach?>
				<?php endif?>

				<?php echo $vis2ontent?>

			</div>
		</div>
	</div>
</div>

<a class="scroll-to-top rounded" href="#">
	<i class="fas fa-angle-up"></i>
</a>
<?php endif?>