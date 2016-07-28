<div class="row">
	<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
		<?php echo $errors; ?>

		<?php if($this->session->flashdata('success_message')) { ?>
			<div id="alert" class="alert alert-success"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('success_message'); ?></div>
		<?php } ?>

		<h3 class="page-header">Create new user</h3>

<?php if($this->session->flashdata('error_message')) { ?>
<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $this->session->flashdata('error_message'); ?></div>
<?php } ?>

			
			<?php echo $form; ?>
			
	
	</div>
</div>