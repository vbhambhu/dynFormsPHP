





<div class="row">
<div class="col-md-6 col-md-offset-3">
<h3 class="page-header">Create new group</h3>


<?php if($this->session->flashdata('success_message')) { ?>
<div id="alert" class="alert alert-success"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('success_message'); ?></div>
<?php } ?>


<?php echo $errors; ?>
<?php echo $form; ?>


</div>
</div>
