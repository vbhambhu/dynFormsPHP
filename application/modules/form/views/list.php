<?php echo anchor('form/create','Create new', array('class' => 'btn btn-success btn-sm')); ?>
<br><br>

<?php if($this->session->flashdata('success_message')) { ?>
            <div id="alert" class="alert alert-success"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert">&times;</button><?php echo $this->session->flashdata('success_message'); ?></div>
        <?php } ?>  
        
<table id="datatable" class="table table-striped table-bordered" nosort="6" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Total records</th>
                <th>Owner</th>
                <th>Created at</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Total records</th>
                <th>Owner</th>
                <th>Created at</th>
                <th>Action</th>
            </tr>
        </tfoot>
        <tbody>

		<?php foreach ($forms as $form) { ?>
			<tr>
                <td><?php echo $form->name; ?></td>
                <td><?php echo $form->description; ?></td>
                <td>23</td>
                <td>Vinod Kumar</td>
                <td><?php echo $form->created_at; ?></td>
                <td>
                <?php echo anchor('/record/entry/'.$form->slug , 'record'); ?> | 
                <?php echo anchor('/form/edit/'.$form->slug , 'edit'); ?> | 
                <?php echo anchor('/form/delete/'.$form->slug , 'delete'); ?> | 
                <?php echo anchor('/form/data?id='.$form->id , 'data'); ?>
                </td>
            </tr>
		<?php } ?>
        </tbody>
    </table>


