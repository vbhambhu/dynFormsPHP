</div>
</div>
<footer class="footer">
<div class="container">
<p class="text-muted">&nbsp;</p>
</div>
</footer>


<!-- javascripts -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>
<script src="<?php echo base_url('assets/js/tether.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<?php if(isset($js_full_foot)) { ?>
<?php foreach ($js_full_foot as $js_full_foot_file) { ?>
<script type="text/javascript" src="<?php echo $js_full_foot_file; ?>"></script>
<?php } ?>
<?php } ?>
<?php if(isset($js_foot)) { ?>
<?php foreach ($js_foot as $js_foot_file) { ?>
<script type="text/javascript" src="<?php echo base_url('assets/js/'.trim($js_foot_file).'.js'); ?>"></script>
<?php } ?>
<?php } ?>
<script type="text/javascript" src="<?php echo base_url('assets/js/frontend.js'); ?>"></script>
<!-- // javascripts -->
</body>
</html>