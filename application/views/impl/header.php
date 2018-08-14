<!DOCTYPE html>
<html lang="en">
<?php
// require $_SERVER[ 'DOCUMENT_ROOT' ] . '/vendor/autoload.php';
$nav_path = '';
if(!empty($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
    $nav_path = explode('/',$_SERVER['REQUEST_URI']);
}
?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>COMS - Client Order Monitoring System</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url();?>/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <!--<link href="../dist/css/coms-style.css" rel="stylesheet"> Light Theme-->
    <link href="<?php echo base_url();?>css/coms-style-dark.css" rel="stylesheet"><!-- Dark Theme-->
    <link href="<?php echo base_url();?>/css/sb-admin-2.css" rel="stylesheet">


    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url();?>/vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url();?>/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- DataTables CSS -->
    <link href="<?php echo base_url();?>css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>css/dataTables.responsive.css" rel="stylesheet">

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	
    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
	<!-- Clockpicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url()?>main">COMS</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a name="notify_btn" class="dropdown-toggle" data-toggle="dropdown" >
                        <i name="notify_counter" class="fa fa-bell fa-fw"></i>
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu" style="width: 310px;">
                      <li>
                        <span style="margin-left: 10px;"><b>Notifications</b></span>
                        <span name="mark_all_notify_as_read" style="float: right; margin-right: 10px; color:#d15a3e;"><a>Mark All as Read</a></span>
                      </li>
                    </ul>
                    <ul name="latest_notify_div" style="top: 75px;height: 240px; overflow:scroll" class="dropdown-menu dropdown-alerts scrollable"></ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" >
                        <?php echo decrypt($this->session->userdata('user_data'))['username']; ?>
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?php echo base_url()?>profile"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url()?>logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <!-- system down terminal -->
            <ul class="nav navbar-top-links" style="text-align: center; padding-top: 12px;" name="announcement_div">
              <li><img alt="announcement" title="announcement" class="shake-vertical-slow shake-constant shake-constant--hover" src="/images/announce.png" width="24" height="24" data-pin-nopin="true"></li>
              <a href="#" id="announce" data-type="select2" data-pk="1" data-url="AdminPanel/updateAnnouncement" data-title="Select keywords" <?php echo $can_access_admin_panel == TRUE ? 'data-disabled="false"' : 'data-disabled="true"';?> ></a>
            </ul>
            <!-- / system down terminal -->


            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <?php if(can_create_request(decrypt($this->session->userdata('user_data'))['user_role'])){?>
                          <li class="sidebar-create">
                              <!-- <a href="<?php echo base_url()?>create" class="btn btn-lg"><i class="fa fa fa-pencil"></i> <span>Create</span></a> -->
                              <a <?php echo $can_create_request == TRUE ? 'href="'.base_url().'create"': '' ?> class="btn btn-lg"><i class="fa fa fa-pencil"></i> <span>Create</span></a>
                          </li>
                        <?php } ?>
                        <li>
                            <a href="/main" <?php echo ( empty($nav_path) || (trim($nav_path[1]) == 'main')) ? 'active' : ''; ?>><i class="fa fa-dashboard fa-fw"></i> <span>Dashboard</span></a>
                        </li>
                        <?php if(can_view_report(decrypt($this->session->userdata('user_data'))['user_role'])){?>
                          <li>
                              <!-- <a href="<?php echo base_url()?>create" class="btn btn-lg"><i class="fa fa fa-pencil"></i> <span>Create</span></a> -->
                              <a <?php echo $can_view_report == TRUE ? 'href="'.base_url().'report"': '' ?> <?php echo ( empty($nav_path) || (trim($nav_path[1]) == 'report')) ? 'active' : ''; ?>><i class="fa fa-bar-chart-o fa-fw"></i> <span>Report</span></a>
                          </li>
                        <?php } ?>
                        <?php if(can_monitor_user(decrypt($this->session->userdata('user_data'))['user_role'])){?>
                          <li>
                              <a <?php echo $can_monitor_user == TRUE ? 'href="'.base_url().'monitor"': '' ?> <?php echo ( empty($nav_path) || (trim($nav_path[1]) == 'monitor')) ? 'active' : ''; ?>><i class="fa fa-search fa-fw"></i> <span>Monitor</span></a>
                          </li>
                        <?php }?>
                        <?php if(can_access_admin_panel(decrypt($this->session->userdata('user_data'))['user_role'])){?>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Admin Panel<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                              <?php if(is_administrator(decrypt($this->session->userdata('user_data'))['user_role'])){?>
                                <li>
                                    <a href="#"><i class="fa fa-users"></i> <span>Users</span><span class="fa arrow"></a>
                                    <ul class="nav nav-third-level">
                                      <li>
                                          <a href="<?php echo base_url().'AdminPanel/user'?>" <?php echo ( empty($nav_path) || (trim($nav_path[1]) == 'AdminPanel/user') || (trim($nav_path[1]) == 'AdminPanel/updateUser') ) ? 'active' : ''; ?>><i class="fa fa-cog"></i> <span>Manage</span></a>
                                      </li>
                                      <li>
                                        <a href="<?php echo base_url().'AdminPanel/addUser'?>" <?php echo ( empty($nav_path) || (trim($nav_path[1]) == 'AdminPanel/addUser') ) ? 'active' : ''; ?>><i class="fa fa-plus"></i> <span>Add new</span></a>
                                      </li>
                                    </ul>
                                </li>
                              <?php } ?>
                                <li>
                                    <a href="#"><i class="fa fa-address-card-o"></i> <span>Bank Accounts</span><span class="fa arrow"></a>
                                    <ul class="nav nav-third-level">
                                      <li>
                                          <a href="<?php echo base_url().'AdminPanel/bankAccount'?>" <?php echo ( empty($nav_path) || (trim($nav_path[1]) == 'AdminPanel/bankAccount') || (trim($nav_path[1]) == 'AdminPanel/updateBankAccount') ) ? 'active' : ''; ?>><i class="fa fa-cog"></i> <span>Manage</span></a>
                                      </li>
                                      <li>
                                        <a href="<?php echo base_url().'AdminPanel/addBankAccount'?>" <?php echo ( empty($nav_path) || (trim($nav_path[1]) == 'AdminPanel/addBankAccount') ) ? 'active' : ''; ?>><i class="fa fa-plus"></i> <span>Add new</span></a>
                                      </li>
                                    </ul>
                                </li>
                                <!-- <li>
                                    <a href="<?php echo base_url().'AdminPanel/announcement'?>" <?php echo ( empty($nav_path) || (trim($nav_path[1]) == 'AdminPanel/announcement') ) ? 'active' : ''; ?>><i class="fa fa-bullhorn"></i> <span>Announcement</span></a>
                                </li> -->
                                <!-- <li>
                                    <a href="<?php echo base_url().'AdminPanel/website'?>" <?php echo ( empty($nav_path) || (trim($nav_path[1]) == 'AdminPanel/website') ) ? 'active' : ''; ?>><i class="fa fa-globe"></i> <span>Manage Websites</span></a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'AdminPanel/game'?>" <?php echo ( empty($nav_path) || (trim($nav_path[1]) == 'AdminPanel/game') ) ? 'active' : ''; ?>><i class="fa fa-gamepad"></i> <span>Manage Games</span></a>
                                </li> -->
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php } ?>
						 <?php if(can_view_report(decrypt($this->session->userdata('user_data'))['user_role'])){?>
                        <li>
                            <a href="#"><i class="fa fa-money"></i> <span>PPR</span><span class="fa arrow"></a>
                            <ul class="nav nav-third-level">
                              <li>
                                <a <?php echo $can_view_report == TRUE ? 'href="'.base_url().'PprAdjustment"': '' ?> <?php echo ( empty($nav_path) || (trim($nav_path[1]) == 'ppradjustment')) ? 'active' : ''; ?>><i class="fa fa-cogs"></i> <span>Adjustment</span></a>
                              </li>
                              <li>
                                  <a <?php echo $can_view_report == TRUE ? 'href="'.base_url().'PprAutoReport" target="_blank"': '' ?> ><i class="fa fa-bar-chart-o fa-fw"></i> <span>Report</span></a>
                              </li>
                              <li>
                                  <a <?php echo $can_view_report == TRUE ? 'href="'.base_url().'Ppr" target="_blank"': '' ?>><i class="fa fa-television"></i> <span>TV</span></a>
                              </li>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
