<ol class="breadcrumb">
<?php if(!is_null($breadcrumb)){ ?>
<?php foreach ($breadcrumb as $blink) { ?>
<?php if(isset($blink['slug'])){ ?>
<li><a href="<?php echo base_url('docs/'. $blink['slug']); ?>"><?php echo $blink['name']; ?></a></li>
<?php } else { ?>
<li class="active"><?php echo $blink['name']; ?></li>
<?php } ?>
<?php } ?>
<?php } ?>
</ol>

<?php echo $errors; ?>
<?php if($permissions['upload']) { ?>

<div class="form-inline pull-xs-right">
  <input type="hidden" name="dir_id" value="<?php echo $dir_id; ?>">
  <?php echo $form; ?>
</div>

<br clear="both"/>






<?php } ?>







<hr>


<?php if($this->session->flashdata('success_message')) { ?>
  <div id="alert" class="alert alert-success"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('success_message'); ?></div>
<?php } ?>



<?php if (!$docs) { ?>
<div id="empty-folder">

<i class="fa fa-folder-open fa-3x" aria-hidden="true"></i>
<h3>This folder is empty.</h3>
</div>
<?php } else if(isset($perm_message)){ ?>


<div class="alert alert-danger" role="alert">
  <strong>Read permission error :</strong> <?php echo $perm_message; ?>
</div>

<?php } else { ?>


<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>Name</th>
      <th>Owner</th>
      <th>Type</th>
      <th>Created on</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($docs as $doc) { ?>

    <?php $perms = $doc->permissions; ?>

    <?php if($perms['read']) { ?>

  	 <tr>
      <td>
      	
     <?php if ($doc->type == 1) { ?>
	<i class="fa fa-folder fa-lg"></i> <?php echo anchor('docs/'.$doc->slug, $doc->name); ?> <br>
	<?php } else { ?>
	<i class="fa fa-file fa-lg" aria-hidden="true"></i> <?php echo $doc->name; ?> <br>
	<?php } ?>

      </td>
      <td><?php echo $doc->owner; ?></td>
      <td><?php echo ($doc->type == 1) ? 'folder' : 'file'; ?></td>
      <td><?php echo date("D - M j G:i",strtotime($doc->created_at)); ?></td>
       <td>



<div class="btn-group btn-group-sm" role="group">

<a href="<?php echo base_url('docs/history/'.$doc->slug);?>" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="History"><i class="fa fa-history" aria-hidden="true"></i></a>

  <?php if($perms['edit'] || $perms['download'] || $doc->type == 1 && $doc->owner_id == $this->session->userdata('user_id')) { ?>
  <div class="btn-group btn-group-sm" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      More
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

        <?php if($perms['download']) { ?>
        <a href="<?php echo base_url('docs/download/'.$doc->slug);?>" class="dropdown-item"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
        <?php } ?>

        <?php if($perms['edit']) { ?>
        <a href="<?php echo base_url('docs/rename/'.$doc->slug);?>" class="dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i> Rename</a>
        <a href="<?php echo base_url('docs/delete/'.$doc->slug);?>" class="dropdown-item confirm" data-msg="Are you sure you want to delete this <?php echo ($doc->type == 1) ? ' folder? This action will also delete containing files and other folders inside.' : 'file?'; ?> "><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
        <?php } ?>


        <?php if($doc->type == 1 && $doc->owner_id == $this->session->userdata('user_id')) { ?>
         <a href="<?php echo base_url('docs/permission/'.$doc->slug);?>" class="dropdown-item"><i class="fa fa-globe" aria-hidden="true"></i> Permissions</a>
        <?php } ?>
    </div>
  </div>
<?php } ?>
</div>


    </tr>
  <?php } ?>
  <?php } ?>
  </tbody>
</table>
<?php } ?>




<!-- file upload model -->
<div class="modal fade" id="fileUploader" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">File uploader</h4>
      </div>
      <div class="modal-body">
        


<div ng-repeat="f in files">
<div><strong>File:</strong> {{f.name}}</div>
<progress class="progress progress-striped progress-success" value="{{f.progress}}" max="100">100%</progress>
</div>


      <div ng-repeat="f in errFiles">
      <div><strong>File upload error:</strong> {{f.name}} - <strong> {{f.$error}} {{f.$errorParam}}</strong></div>
      <progress class="progress progress-striped progress-danger" value="100" max="100">100%</progress>
      </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onClick="window.location.reload()">Close</button>
      </div>
    </div>
  </div>
</div>













