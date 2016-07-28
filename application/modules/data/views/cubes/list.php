
<?php echo anchor('data/cube/create','Create'); ?>

<?php foreach ($cubes as $cube) { ?>

<p><?php echo $cube->name; ?></p>

<?php } ?>