<div class="container">
	<div class="row justify-content-center mt-5">
		<div class="col-sm-10 col-md-8 col-lg-6 text-muted justify-content-center text-center">
			<?php echo $this->getOptimizedImage(vOut('vis2_logo_login_name'), array('parameter'=>'class="mb-5 img-responsive center-block"', 'module'=>vOut('vis2_logo_login_module'), 'title'=>vOut('vis2_logo_login_title'), 'longest'=>vOut('vis2_logo_login_longest'), 'height'=>vOut('vis2_logo_login_height'), 'width'=>vOut('vis2_logo_login_width')))?>
			<?php if(vOut('vis2_logon_message')!=''):?><h1><?php echo vOut('vis2_logon_message')?></h1><?php endif?>
		</div>
	</div>
</div>

<?php echo osW_Form::getInstance()->formStart('form_login', 'current')?>
<div class="container">
	<div class="row justify-content-center mt-5 mb-3">
		<div class="col-sm-10 col-md-8 col-lg-6">
			<div class="login-panel card card-default">
				<div class="card-header">
					Anmelden
				</div>
				<div class="card-body">
					<div class="form-group">
						<label for="vis2_login_email" class="form-control-label">E-Mail:</label>
						<?php echo osW_Form::getInstance()->drawTextField('vis2_login_email', '', array('input_class'=>'form-control', 'input_errorclass'=>'is-invalid'))?>
						<div class="invalid-feedback"><?php echo osW_Form::getInstance()->getFormErrorMessage('vis2_login_email')?></div>
					</div>
					<div class="form-group">
						<label for="vis2_login_password" class="form-control-label">Password:</label>
						<?php echo osW_Form::getInstance()->drawPasswordField('vis2_login_password', '', array('input_class'=>'form-control', 'input_errorclass'=>'is-invalid'))?>
						<div class="invalid-feedback"><?php echo osW_Form::getInstance()->getFormErrorMessage('vis2_login_password')?></div>
					</div>
				</div>
				<div class="card-footer">
					<?php echo osW_Form::getInstance()->drawSubmit('btn_login', 'Absenden', array('input_class'=>'btn btn-primary btn-block'))?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo osW_Form::getInstance()->drawHiddenField ('action', 'dologin')?>
<?php echo osW_Form::getInstance()->formEnd()?>

<div class="container">
	<div class="row justify-content-center mb-3">
		<div class="col-sm-10 col-md-8 col-lg-6 text-muted text-center">
			<small>v<?php echo vOut('vis2_majorrelease')?>.<?php echo vOut('vis2_minorrelease')?>.<?php echo vOut('vis2_patchlevel')?><?php echo vOut('vis2_version')?></small>
		</div>
	</div>
</div>
