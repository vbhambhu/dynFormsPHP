

<div class="pull-xs-right">
    <?php echo anchor('account/user/create', '<i class="fa fa-plus" aria-hidden="true"></i> Add user', array('class' => 'btn btn-success btn-sm' )); ?>
</div>
<div class="clearfix"></div>




<hr>
<?php if($this->session->flashdata('success_message')) { ?>
<div id="alert" class="alert alert-success"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('success_message'); ?></div>
<?php } ?>


<?php if($this->session->flashdata('error_message')) { ?>
<div id="alert" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('error_message'); ?></div>
<?php } ?>


<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Last login</th>
                <th>Created on</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
				<th>Name</th>
				<th>Username</th>
				<th>Email</th>
				<th>Status</th>
				<th>Last login</th>
				<th>Created on</th>
				<th>Action</th>
            </tr>
        </tfoot>
        <tbody>

        <?php foreach ($users as $user) { ?>
        	<tr>
                <td><?php echo $user->name; ?></td>
                <td><?php echo $user->username; ?></td>
                <td><?php echo $user->email; ?></td>
                <td><?php echo ($user->status == 1) ? 'Active' : 'Disabled'; ?></td>
                <td><?php echo $user->last_login; ?></td>
                <td><?php echo $user->created_at; ?></td>
                <td>

                
                    <a href="<?php echo base_url('account/user/edit/'.$user->id); ?>" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-secondary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
             

               

                <a href="<?php echo base_url('account/user/delete/'.$user->id); ?>" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm confirm" data-msg="Are you sure you want to delete this user?" ><i class="fa fa-trash" aria-hidden="true"></i></a>

               


                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>


