<!DOCTYPE html>
<html lang="en" <?php echo isset($angular_app) ? 'ng-app="'.$angular_app.'"' : ''; ?>>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title><?php echo isset($meta_title) ? $meta_title : $this->config->item('site_name'); ?></title>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
<?php if(isset($css)) { ?>
<?php foreach ($css as $css_file) { ?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/'.trim($css_file).'.css'); ?>">
<?php } ?>
<?php } ?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>
<div class="container">
<div ng-controller="appCtrl">
<div class="header clearfix">
  <nav>
    <ul class="nav nav-pills pull-xs-right">
      <li class="nav-item active">
      <?php echo anchor('/','Home', array('class'=>'nav-link') ); ?>
      </li>
      <?php if($this->session->userdata('is_admin')) { ?>
      <li class="nav-item">
      <?php echo anchor('account/user','Users', array('class'=>'nav-link') ); ?>
      </li>
      <li class="nav-item">
      <?php echo anchor('logs','Logs', array('class'=>'nav-link') ); ?>
      </li>
      <?php } ?>
      <li class="nav-item">
      <?php echo anchor('account/group','Groups', array('class'=>'nav-link') ); ?>
      </li>
      <li class="nav-item">
      <?php echo anchor('account/logout','Logout', array('class'=>'nav-link') ); ?>
      </li>
    </ul>
  </nav>
  <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('assets/images/logo.png'); ?>"></a>
</div>