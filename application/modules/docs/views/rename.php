
<div class="row">
  <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">

<h3>Rename</h3>

  <?php if($this->session->flashdata('error_message')) { ?>
<div id="alert" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('error_message'); ?></div>
<?php } ?>


<?php echo $errors; ?>
<?php echo $form; ?>

  </div>
</div>