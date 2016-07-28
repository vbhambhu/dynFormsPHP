<?php if($this->session->flashdata('success_message')) { ?>
<div id="alert" class="alert alert-success"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('success_message'); ?></div>
<?php } ?>

<h3><i class="fa fa-folder-o" aria-hidden="true"></i> <?php echo $doc->name; ?></h3>
<hr>


 <div class="row">
    <div class="col-sm-6">
      


<?php if($this->session->flashdata('error_message')) { ?>
<div id="alert" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('error_message'); ?></div>
<?php } ?>


<?php echo $errors; ?>
<?php echo $form; ?>


    </div>
 </div>




<p>&nbsp;</p>
<?php if($permissions){ ?>

<h3>All permissions</h3>

<table class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Permission</th>
                <th>Action</th>
            </tr>
        </thead>
       

        <tbody>

        <?php foreach ($permissions as $permission) { ?>
          <tr>
                <td><?php echo $permission->type_name; ?></td>
                <td><?php echo $permission->type; ?></td>
                <td>
                <span class="label <?php echo (substr($permission->permissions, 0, 1) == 1) ? 'label-success' : 'label-default'; ?>">View</span>
                <span class="label <?php echo (substr($permission->permissions, 1, 1) == 1) ? 'label-success' : 'label-default'; ?>">Edit</span>
                <span class="label <?php echo (substr($permission->permissions, 2, 1) == 1) ? 'label-success' : 'label-default'; ?>">Download</span>
                <span class="label <?php echo (substr($permission->permissions, 3, 1) == 1) ? 'label-success' : 'label-default'; ?>">Upload</span>
                </td>
                <td>

                <?php if($permission->is_protected == 0){  ?>
                <a href="<?php echo base_url('docs/delete_permission/'.$permission->id); ?>" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm confirm" data-msg="Are you sure you want to delete this permission?"><i class="fa fa-trash" aria-hidden="true"></i></a>
                <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php } else { ?>
  <h3>No permission set to this folder yet.</h3>
<?php } ?>

