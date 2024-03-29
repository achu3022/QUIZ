<?php 
session_start();
include 'dbcon.php'; 

if (!isset($_SESSION['admin']))
 //   header("location:index.php");
?>

<!DOCTYPE html>
<html lang="en">
<!-- BEGIN HEAD -->

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<meta name="description" content="Learning Management System" />
	<meta name="author" content="Learning Management System" />
	<title>Learning Management System</title>
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
	<!-- icons -->
	<link href="fonts/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
	<link href="fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="fonts/font-awesome/v6/css/all.css" rel="stylesheet" type="text/css" />
	<link href="fonts/material-design-icons/material-icon.css" rel="stylesheet" type="text/css" />
	<!--bootstrap -->
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/summernote/summernote.css" rel="stylesheet">
	<!-- Material Design Lite CSS -->
	<link rel="stylesheet" href="assets/plugins/material/material.min.css">
	<link rel="stylesheet" href="assets/css/material_style.css">
	<!-- inbox style -->
	<link href="assets/css/pages/inbox.min.css" rel="stylesheet" type="text/css" />
	<!-- Theme Styles -->
	<link href="assets/css/theme/light/theme_style.css" rel="stylesheet" id="rt_style_components" type="text/css" />
	<link href="assets/css/plugins.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/theme/light/style.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/theme/light/theme-color.css" rel="stylesheet" type="text/css" />
	<!-- favicon -->
	<link rel="shortcut icon" href="assets/img/favicon.png" />
	<!-- Bootstrap Date Picker-->
	<link rel="stylesheet" type="text/css" href="assets/dist/bootstrap-clockpicker.css">
<link rel="stylesheet" type="text/css" href="assets/css/github.min.css">
</head>
<!-- END HEAD -->

<body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md header-white white-sidebar-color logo-indigo">
	
<div class="page-wrapper">
<!-- start header -->

<div class="page-header navbar navbar-fixed-top">
<div class="page-header-inner ">
<!-- logo start -->

<div class="page-logo" style="padding:0px;height:auto">
<a href="https://www.aimri.in/" target="_blank">
<img src="https://www.aimri.in/assets/icons/AIMRI-Logo.png" class="img-responsive"> </a>
</div>
<!-- logo end -->

<ul class="nav navbar-nav navbar-left in">
<li><a href="#" class="menu-toggler sidebar-toggler"><i data-feather="menu"></i></a>
</li>
</ul>
				
<!-- start mobile menu -->
<a class="menu-toggler responsive-toggler" data-bs-toggle="collapse" 
data-bs-target=".navbar-collapse">
<span></span>
</a>
<!-- end mobile menu -->
<!-- start header menu -->
				
<div class="top-menu">
<ul class="nav navbar-nav pull-right">
<li><a class="fullscreen-btn"><i data-feather="maximize"></i></a></li>
						<li class="dropdown dropdown-user">
											
							<a href="javascript:;" class="dropdown-toggle" data-bs-toggle="dropdown"

								data-hover="dropdown" data-close-others="true">

								<img alt="" class="img-circle " src="assets/img/dp.jpg" />

								<span class="username username-hide-on-mobile"> Super Admin

							</a>
						
							<ul class="dropdown-menu dropdown-menu-default">

								<li>

									<a href="logout.php">

										<i class="icon-logout"></i> Log Out </a>

								</li>

							</ul>

						</li>


<li class="dropdown dropdown-quick-sidebar-toggler">
<a id="headerSettingButton" class="mdl-button mdl-js-button mdl-button--icon pull-right"
data-upgraded="MaterialButton">
<i class="material-icons">more_vert</i>
</a>
</li>

</ul>
</div>
</div>
</div>
<!-- end header -->


<!-- start color quick setting -->
<!-- <div class="settingSidebar">
<a href="javascript:void(0)" class="settingPanelToggle"> 
<i class="fa fa-spin fa-cog"></i>
</a>

</div> -->
		<!-- end color quick setting -->
<!-- start page container -->
<div class="page-container">
<!-- start sidebar menu -->
<div class="sidebar-container">

<div class="sidemenu-container navbar-collapse collapse fixed-menu">
<div id="remove-scroll" class="left-sidemenu">
						

<ul class="sidemenu  page-header-fixed slimscroll-style" data-keep-expanded="false"
data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">

<li class="sidebar-toggler-wrapper hide">
<div class="sidebar-toggler">
<span></span>
</div>
</li>

<li class="sidebar-user-panel">
<div class="sidebar-user">
<div class="sidebar-user-picture">
<img alt="image" src="assets/img/dp.jpg">
</div>
<div class="sidebar-user-details">
<div class="user-name">Super Admin</div>
<!-- <div class="user-role">Administrator</div> -->
</div>
</div>
</li>



<li class="nav-item">
<a href="admin-dashboard.php" class="nav-link nav-toggle"> <i data-feather="calendar"></i>
<span class="title">Dashboard</span>
</a>
</li>

<li class="nav-item">
<a href="list-courses.php" class="nav-link nav-toggle"> <i data-feather="calendar"></i>
<span class="title">Courses</span>
</a>
</li>

<li class="nav-item">
<a href="view-course-category.php" class="nav-link nav-toggle"> <i data-feather="calendar"></i>
<span class="title">Course Categories</span>
</a>
</li>

<li class="nav-item">
<a href="list-course-material.php" class="nav-link nav-toggle"> <i data-feather="calendar"></i>
<span class="title">Course Materials</span>
</a>
</li>

<li class="nav-item">
<a href="list-assignments.php" class="nav-link nav-toggle"> <i data-feather="calendar"></i>
<span class="title">Assignments</span>
</a>
</li>

<li class="nav-item">
<a href="list-events.php" class="nav-link nav-toggle"> <i data-feather="calendar"></i>
<span class="title">Events</span>
</a>
</li>







</ul>
</div>
</div>
</div>

<!-- end sidebar menu -->