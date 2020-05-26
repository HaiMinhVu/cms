<?php
if(!isset($_SESSION['user'])){
	header('location:../index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
  	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	    <title>Sellmark Brands</title>
		
		<!-- All CSS -->
    <!-- All CSS -->
        <!-- general -->
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/googleapi-font.css">
        <link rel="stylesheet" href="../css/ekko-lightbox.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <!--<link rel="stylesheet" href="../css/select2.min.css" />-->
        <link rel="stylesheet" href="../css/jquery-ui.css">
        <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css" /> 
        <link rel="stylesheet" href="../css/selectize.default.css">
        <link rel="stylesheet" href="../css/bootstrap-select.min.css">
        <link rel="stylesheet" href="../css/select2.css" />
        <link rel="stylesheet" href="../css/jstree/style.min.css" />

        <!--<link rel="stylesheet" href="../css/daterangepicker.css" />
        <link rel="stylesheet" href="../css/bootstrap-select.min.css">-->
        <!----------- filepond upload images --------->
        <!--<link rel='stylesheet' href='../css/filepond-plugin-image-preview.min.css'>
        <link rel='stylesheet' href='../css/filepond.min.css'>
        <link rel="stylesheet" href="../css/filepond.css">-->
        <!----------- WYSIWYG --------->
        <!--<link rel="stylesheet" href="../css/summernote.css">-->
        

        <!-- navigation -->
        <!--<link rel="stylesheet" href="../css/nav/normalize.css">
        <link rel="stylesheet" href="../css/nav/themify-icons.css">
        <link rel="stylesheet" href="../css/nav/flag-icon.min.css">
        <link rel="stylesheet" href="../css/nav/cs-skin-elastic.css">-->
        <link rel="stylesheet" href="../css/nav/style.css">

       
    <!-- All JS -->
        <!-- general -->
        <script src="../js/jquery.min.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/ekko-lightbox.min.js"></script>
        
        <script src="../js/cookies.js"></script> <!-- save cookies and scroll down point -->

        <!-- dialog pop up -->
        <script src="../js/jquery-ui.js"></script>
        <!-- bootstrap selectpicker -->
        <script src="../js/bootstrap-select.min.js"></script>
         <!-- data table -->
        <script src="../js/dataTables.jquery.min.js"></script>
        <script src="../js/dataTables.bootstrap.min.js"></script> 
        <!-- selectize -->
        <script src="../js/selectize.min.js"></script>
        <!-- select2 -->
        <script src="../js/select2.js"></script>
        <!-- treeview for category -->
        <script src="../js/jstree.min.js"></script>
        <script src="../js/jstreegrid.js"></script>
        <!-- date range picker-->
        <!--<script src="../js/daterangepicker-moment.min.js"></script>
        <script src="../js/daterangepicker.min.js"></script>-->


        <!-- filepond to upload images and documents-->
        <!--<script src='../js/filepond-plugin-file-encode.min.js'></script>
        <script src='../js/filepond-plugin-file-validate-size.min.js'></script>
        <script src='../js/filepond-plugin-image-exif-orientation.min.js'></script>
        <script src="../js/filepond-plugin-image-preview.min.js"></script>
        <script src="../js/filepond.min.js"></script>-->
        <!----------- WYSIWYG --------->
        <!--<script src="../js/summernote.js"></script>-->

        <!-- navigation -->
        <script src="../js/nav/main.js"></script>

        
        
	</head>
	<body onload="loadScroll()" onunload="saveScroll()">
		<!--Navigation -->
    	<?php include ("../navsub1.php"); ?>
    	<!--End of navigation --> 

