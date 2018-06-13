<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>ปากพนังบน</title>

  <!-- Bootstrap core CSS-->
  <link href="<?=base_url()?>asset/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="<?=base_url()?>asset/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="<?=base_url()?>asset/css/sb-admin.css" rel="stylesheet">
  <!--Unite Gallery-->
  <link href="<?=base_url()?>asset/vendor/unitegallery/css/unite-gallery.css" rel="stylesheet">
  <link href="<?=base_url()?>asset/vendor/unitegallery/themes/default/ug-theme-default.css" rel="stylesheet">

  <!-- Bootstrap core JavaScript-->
  <script src="<?=base_url()?>asset/vendor/jquery/jquery.min.js"></script>

  <!-- Include Required Prerequisites -->
  <script type="text/javascript" src="<?=base_url()?>asset/vendor/DateRangePicker/moment.min.js"></script>

  <!-- Include Date Range Picker -->
  <script src="<?=base_url()?>asset/vendor/DateRangePicker/daterangepicker.js"></script>
  <link href="<?=base_url()?>asset/vendor/DateRangePicker/daterangepicker.css" rel="stylesheet" type="text/css"/>
  
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">

<?php 

$query = (" SELECT * FROM ss_sites WHERE sitecode != 'PPN07' ORDER BY lined ASC "); 
$querySite = $this->db->query($query);
$querySite = $querySite->result_array();


?>


  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="<?=site_url()?>home">ปากพนังบน</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="ข้อมูลโดยรวม">
          <a class="nav-link" href="<?=site_url()?>home">
            <i class="fa fa-fw fa-area-chart"></i>
            <span class="nav-link-text">ข้อมูลโดยรวม</span>
          </a>
        </li>
        
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="ระบบสื่อสาร">
          <a class="nav-link" href="<?=site_url()?>Communications">
            <i class="fa fa-fw fa-podcast"></i>
            <span class="nav-link-text">ระบบสื่อสาร</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="ระดับน้ำ">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-thermometer-half"></i>
            <span class="nav-link-text">ระดับน้ำ</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents">

          <?php foreach($querySite as $site){ ?>
            <li>
              <a href="<?=site_url()?>waterlevel/site/<?=$site['sitecode']?>"><?=$site['sitecode']." ".$site['sitename']?></a>
            </li>
          <?php } ?>
          
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="ปริมาณน้ำ">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents2" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-industry"></i>
            <span class="nav-link-text">ปริมาณน้ำ</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents2">

            <li>
              <a href="<?=site_url()?>quantitywater/site/<?=$querySite['0']['sitecode']?>"><?=$querySite['0']['sitecode']." ".$querySite['0']['sitename']?></a>
            </li>

          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="ปริมาณน้ำฝน">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents3" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-tint"></i>
            <span class="nav-link-text">ปริมาณน้ำฝน</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents3">

          <?php foreach($querySite as $site){ ?>
            <li>
              <a href="<?=site_url()?>rainfall/site/<?=$site['sitecode']?>"><?=$site['sitecode']." ".$site['sitename']?></a>
            </li>
          <?php } ?>
          
          </ul>
        </li>
  
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="ภาพนิ่ง">
          <a class="nav-link" href="<?=site_url()?>slideImage">
            <i class="fa fa-fw fa-file-image-o"></i>
            <span class="nav-link-text">ภาพนิ่ง</span>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
     
    </div>
  </nav>

  <div class="content-wrapper">
   


