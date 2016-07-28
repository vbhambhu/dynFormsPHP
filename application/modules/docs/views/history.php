<?php if (!$history) { ?>
<h3 id="empty-data">No history associated with this folder.</h3>
<?php } else { ?>
<table class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>Event</th>
      <th>Name</th>
      <th>User</th>
      <th>IP Address</th>
      <th>User Agent</th>
      <th>Created On</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($history as $item) { ?>
  	 <tr>
      <td><?php echo $item->event; ?></td>
      <td><?php echo $item->doc_name; ?></td>
      <td><?php echo $item->name; ?></td>
      <td><?php echo $item->ip_address; ?></td>
      <td><?php echo $item->user_agent; ?></td>
      <td><?php echo date("D - M j G:i",strtotime($item->created_at)); ?></td>  
    </tr>
  <?php } ?>
  </tbody>
</table>
<?php } ?>