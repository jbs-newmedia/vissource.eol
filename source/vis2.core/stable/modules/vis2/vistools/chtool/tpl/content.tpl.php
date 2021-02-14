<div class="container">
	<div class="row justify-content-center mt-5">
		<div class="col-sm-10 col-md-8 col-lg-6 text-muted justify-content-center text-center">
			<?php echo $this->getOptimizedImage(vOut('vis2_logo_login_name'), array('parameter'=>'class="mb-5 img-responsive center-block"', 'module'=>vOut('vis2_logo_login_module'), 'title'=>vOut('vis2_logo_login_title'), 'longest'=>vOut('vis2_logo_login_longest'), 'height'=>vOut('vis2_logo_login_height'), 'width'=>vOut('vis2_logo_login_width')))?>
			<?php if(vOut('vis2_logon_message')!=''):?><h1><?php echo vOut('vis2_logon_message')?></h1><?php endif?>
		</div>
	</div>
</div>

<?php echo osW_Form::getInstance()->formStart('form_login', 'current', 'vistool='.osW_VIS2::getInstance()->getTool())?>
<div class="container">
	<div class="row justify-content-center mt-5 mb-3">
		<div class="col-sm-10 col-md-8 col-lg-6">
			<div class="login-panel card card-default">
				<div class="card-header">
					Programm wählen</h3>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label for="vis2_login_email" class="form-control-label">Benutzer:</label>
						<div class="form-control  "><?php echo h()->_outputString(osW_VIS2_User::getInstance()->getName())?><span style="float:right;"><a href="<?php echo $this->buildhrefLink('current')?>">ändern</a></span></div>
					</div>
					<div class="form-group">
						<label for="vis2_login_tool" class="form-control-label">Programm:</label>
						<?php echo osW_Form::getInstance()->drawSelectField('vis2_login_tool', array(''=>'')+osW_VIS2::getInstance()->getToolsSelectLogonArraybyPermission(), '', array('input_class'=>'selectpicker form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>' data-style="btn btn-outline-default"'))?>
						<div class="invalid-feedback"><?php echo osW_Form::getInstance()->getFormErrorMessage('vis2_login_tool')?></div>
					</div>
				</div>
				<div class="card-footer">
					<?php echo osW_Form::getInstance()->drawSubmit('btn_login', 'Absenden', array('input_class'=>'btn btn-primary btn-block'))?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo osW_Form::getInstance()->drawHiddenField ('action', 'dochange')?>
<?php echo osW_Form::getInstance()->formEnd()?>

<div class="container">
	<div class="row justify-content-center mb-3">
		<div class="col-sm-10 col-md-8 col-lg-6 text-muted text-center">
			<small>v<?php echo vOut('vis2_majorrelease')?>.<?php echo vOut('vis2_minorrelease')?>.<?php echo vOut('vis2_patchlevel')?><?php echo vOut('vis2_version')?></small>
		</div>
	</div>
</div>
