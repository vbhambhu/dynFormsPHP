

<?php if($this->session->flashdata('success_message')) { ?>
<div id="alert" class="alert alert-success"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('success_message'); ?></div>
<?php } ?>


<div class="row">
  <div class="col-xs-7"><h3>My groups</h3></div>
  <div class="col-xs-5">

  <div class="pull-xs-right">
    <?php echo anchor('account/group/create', '<i class="fa fa-plus" aria-hidden="true"></i> Add group', array('class' => 'btn btn-success btn-sm' )); ?>
</div>

</div>
</div>


<p>&nbsp;</p>







<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Members</th>
                <th>Owned by</th>
                <th>Created on</th>
                <th>Action</th>
            </tr>
        </thead>
       
        <tbody>

        <?php foreach ($my_groups as $group) { ?>
            <tr>
                <td><?php echo $group->name; ?></td>
                <td><?php echo $group->members; ?></td>
                <td><?php echo $group->owned_by; ?></td>
                <td><?php echo date("D - M j G:i",strtotime($group->created_at)); ?></td>
                <td>

                <?php if( $this->session->userdata('user_id') == $group->owner_id) { ?>
                <a href="<?php echo base_url('account/group/edit/'.$group->id); ?>" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
                <a href="<?php echo base_url('account/group/delete/'.$group->id); ?>" class="btn btn-danger btn-sm confirm" data-msg="Are you sure you want to delete this group?"  data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash" aria-hidden="true"></i></a>
                <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>



<hr>


<h3>Other groups</h3>
<p>&nbsp;</p>

<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Members</th>
                <th>Owned by</th>
                <th>Created on</th>
                <th>Action</th>
            </tr>
        </thead>
       
        <tbody>

        <?php foreach ($other_groups as $group) { ?>
            <tr>
                <td><?php echo $group->name; ?></td>
                <td><?php echo $group->members; ?></td>
                <td><?php echo $group->owned_by; ?></td>
                <td><?php echo date("D - M j G:i",strtotime($group->created_at)); ?></td>
                <td>
                  <?php if( $this->session->userdata('is_admin')) { ?>
                <a href="<?php echo base_url('account/group/edit/'.$group->id); ?>" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
                <a href="<?php echo base_url('account/group/delete/'.$group->id); ?>" class="btn btn-danger btn-sm confirm" data-msg="Are you sure you want to delete this group?"  data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash" aria-hidden="true"></i></a>
                 <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>





