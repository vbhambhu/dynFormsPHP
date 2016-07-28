<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
		<?php if($this->session->flashdata('success_message')) { ?>
			<div id="alert" class="alert alert-success"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('success_message'); ?></div>
		<?php } ?>	
		<?php echo $errors; ?>
		<div class="well animated bounceInLeft">
		<h3 class="page-header">Reset password</h3>
		Enter your email address below to reset your password.
		<p>&nbsp;</p>		
		<?php echo $form; ?>
		<?php echo anchor('/', '&#8592; Back to login'); ?>
		</div>
		</div>
	</div>
</div>