<!DOCTYPE html>
<html lang="en">
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
<link rel="stylesheet" href="<?php echo base_url('assets/css/backend.css'); ?>">
</head>
<body ng-app="dynForm">
<div class="container-fluid">


<?php echo anchor('/','Dashboard', array('class'=>'nav-link') ); ?> | 
<?php echo anchor('form','Forms', array('class'=>'nav-link') ); ?> | 
<?php echo anchor('data/import','Import', array('class'=>'nav-link') ); ?> | 
<?php echo anchor('data/view','Data view', array('class'=>'nav-link') ); ?> | 
<?php echo anchor('data/analyse','Data analyse', array('class'=>'nav-link') ); ?>


<hr>