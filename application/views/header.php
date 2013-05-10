<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>智能招车管理系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="dsasa">
    <meta name="author" content="dsadsadsadsadsa">

    <!-- Le styles -->

    <link href="<?php echo url('admin');?>css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo url('admin');?>css/daterangepicker.css" rel="stylesheet">
    <script src="<?=url('static')?>js/jquery.js"></script>
    <script src="<?=url('static')?>js/bootstrap.js"></script>
    <script src="<?=url('static')?>js/bootstrap-typeahead.js"></script>
    <script src="<?=url('static')?>js/bootstrap-modal.js"></script>
    <script src="<?=url('static')?>js/date.js"></script>
    <script src="<?=url('static')?>js/daterangepicker.js"></script>
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
        .font-1em {
            font-size: 1em !important;
        }
        #gototop
        {
            position: fixed;
            top: 630px;
            right: 20px;
            z-index: 1020;
            display: none;
        }
        .subnav-fixed {
          position: fixed;
          top: 40px;
          left: 0;
          right: 0;
          z-index: 1020; /* 10 less than .navbar-fixed to prevent any overlap */
          border-color: #d5d5d5;
          border-width: 0 0 1px; /* drop the border on the fixed edges */
          -webkit-border-radius: 0;
             -moz-border-radius: 0;
                  border-radius: 0;
          -webkit-box-shadow: inset 0 1px 0 #fff, 0 1px 5px rgba(0,0,0,.1);
             -moz-box-shadow: inset 0 1px 0 #fff, 0 1px 5px rgba(0,0,0,.1);
                  box-shadow: inset 0 1px 0 #fff, 0 1px 5px rgba(0,0,0,.1);
          filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); /* IE6-9 */
        }
        .subnav-fixed .nav {
          width: 862px;
          margin: 0 auto;
          padding: 0 1px;
        }
        .subnav {
          width: 100%;
          height: 36px;
          background-color: #eeeeee; /* Old browsers */
          background-repeat: repeat-x; /* Repeat the gradient */
          background-image: -moz-linear-gradient(top, #f5f5f5 0%, #eeeeee 100%); /* FF3.6+ */
          background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f5f5f5), color-stop(100%,#eeeeee)); /* Chrome,Safari4+ */
          background-image: -webkit-linear-gradient(top, #f5f5f5 0%,#eeeeee 100%); /* Chrome 10+,Safari 5.1+ */
          background-image: -ms-linear-gradient(top, #f5f5f5 0%,#eeeeee 100%); /* IE10+ */
          background-image: -o-linear-gradient(top, #f5f5f5 0%,#eeeeee 100%); /* Opera 11.10+ */
          filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f5f5f5', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */
          background-image: linear-gradient(top, #f5f5f5 0%,#eeeeee 100%); /* W3C */
          border: 1px solid #e5e5e5;
          -webkit-border-radius: 4px;
             -moz-border-radius: 4px;
                  border-radius: 4px;
        }
        .subnav .nav > li > a {
          margin: 0;
          padding-top:    11px;
          padding-bottom: 11px;
          border-left: 1px solid #f5f5f5;
          border-right: 1px solid #e5e5e5;
          -webkit-border-radius: 0;
             -moz-border-radius: 0;
                  border-radius: 0;
        }
    </style>
    <link href="<?=url('admin')?>css/bootstrap-responsive.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="<?=url('admin')?>bootstrap/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=url('static')?>bootstrap/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?=url('static')?>bootstrap/ico/apple-touch-icon-57-precomposed.png">
</head>
<body>