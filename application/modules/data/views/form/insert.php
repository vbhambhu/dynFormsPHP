<?php echo $errors; ?>
<?php if($this->session->flashdata('success_message')) { ?>
      <div id="alert" class="alert alert-success"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('success_message'); ?></div>
    <?php } ?>  
<?php echo $form; ?>