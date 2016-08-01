<?php echo anchor('data/cube/create','Create new'); ?>


<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
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

		<?php foreach ($cubes as $cube) { ?>
			<tr>
                <td><?php echo $cube->name; ?></td>
                <td><?php echo $cube->description; ?></td>
                <td>23</td>
                <td>Vinod Kumar</td>
                <td><?php echo $cube->created_at; ?></td>
                <td><?php echo anchor('/data/record?id='.$cube->id , 'record'); ?> | <?php echo anchor('/data/cube/edit?id='.$cube->id , 'edit'); ?>
                    | <?php echo anchor('/data/cube/edit?id='.$cube->id , 'edit'); ?>
                </td>
            </tr>
		<?php } ?>
        </tbody>
    </table>


