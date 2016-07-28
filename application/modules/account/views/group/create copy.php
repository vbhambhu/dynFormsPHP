




{{folders || json}}


<div class="row">
<div class="col-md-6 col-md-offset-3">
<h3 class="page-header">Create new group</h3>

<?php echo form_open(); ?>


<?php
$input = array(
'name'        => 'group_name',
'id'          => 'username',
'value'       =>  set_value('group_name') ,
'maxlength'   => '100',
'size'        => '50',
'class'        => 'form-control',
'placeholder'       => 'New group name..',
);


$user_read = array(
    'name'        => 'user_read',
    'value'       => set_value('user_read'),
    'checked'     => TRUE
    );


?>
<fieldset class="form-group">
    <label>Group name</label>
    <?php echo form_input($input); ?>
    <?php echo form_error('group_name'); ?>
  </fieldset>



<p>&nbsp;</p>

<h4 class="page-header">General privileges</h4>



<div class="row">
<div class="col-xs-6">
<h4>Users</h4>
<div class="checkbox">
  <label><input type="checkbox" name="user_read" value="1" <?php echo set_checkbox('user_read', '1'); ?> > Read user list</label>
</div>
<div class="checkbox">
  <label><input type="checkbox" name="user_add" value="1" <?php echo set_checkbox('user_add', '1'); ?>> Add new user</label>
</div>
<div class="checkbox">
  <label><input type="checkbox" name="user_edit" value="1" <?php echo set_checkbox('user_edit', '1'); ?>> Edit user </label>
</div>
<div class="checkbox">
  <label><input type="checkbox" name="user_delete" value="1" <?php echo set_checkbox('user_delete', '1'); ?>> Delete user</label>
</div>
</div>


<div class="col-xs-6">
<h4>Groups</h4>
<div class="checkbox">
  <label><input type="checkbox" name="group_read" value="1" <?php echo set_checkbox('group_read', '1'); ?>> Read group list</label>
</div>
<div class="checkbox">
  <label><input type="checkbox" name="group_add" value="1" <?php echo set_checkbox('group_add', '1'); ?>> Add new group</label>
</div>
<div class="checkbox">
  <label><input type="checkbox" name="group_edit" value="1" <?php echo set_checkbox('group_edit', '1'); ?>> Edit group </label>
</div>
<div class="checkbox">
  <label><input type="checkbox" name="group_delete" value="1" <?php echo set_checkbox('group_delete', '1'); ?>> Delete group</label>
</div>
</div>


</div>

<p>&nbsp;</p>
<h4 class="page-header">Folders Privileges</h4>

Expand and click on folders blow to set group permission. Please note, if you set permission to top folder all sub folders and file will be assigned as parent folder permissions.



<?php print_r($this->session->userdata('folder_ids')); ?>

<p>&nbsp;</p>


 <div id="jstree_demo_div">
   



   <?php echo to_tree($folders) ?>





 </div>


<div id="ttt"></div>



<hr>

<button type="submit" class="btn btn-success">Submit</button>
</form>


</div>
</div>




<!-- Modal -->
<div class="modal fade" id="permissionWindow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Set permission</h4>
      </div>
      <div class="modal-body">

      <div id="fid"></div>
        
        <div class="checkbox"><label><input type="checkbox"  ng-model="folders.status"  > Read </label></div>
        <div class="checkbox"><label><input type="checkbox" ng-click="setPermission(2)"> Upload </label></div>
        <div class="checkbox"><label><input type="checkbox" name="users" ng-click="setPermission(3)"> Download </label></div>
        <div class="checkbox"><label><input type="checkbox" name="useww" ng-click="setPermission(4)"> Delete </label></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>
