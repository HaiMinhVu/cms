<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">

        <div class="navbar-header">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" ><img src="images/sellmarklogo.png" width=150dp height=40dp alt="Logo"></a>
        </div>

        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
            	<li>
                    <a href="websiteadmin.php"><i class="menu-icon fa fa-dashboard"></i>Dashboard</a>
                </li>

                <h3 class="menu-title">Sites Management</h3>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-laptop"></i>CMS</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-home"></i><a href="product/product.php">Product</a></li>
                            <li><i class="fa fa-share-alt"></i><a href="element/navigation.php">Site Elements</a></li>
                            <li><i class="fa fa-sitemap"></i><a href="manage/category.php">Manager</a></li>
                        </ul>
                    </li>
                
                <!--
                <h3 class="menu-title">Marketing</h3>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-buysellads"></i>Media Planning</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-buysellads"></i><a href="#">AdBuys</a></li>
                            <li><i class="fa fa-tasks"></i><a href="#">AdSpots</a></li>
                            <li><i class="fa fa-building"></i><a href="#">Vendors</a></li>
                            <li><i class="fa fa-television"></i><a href="#">Channels</a></li>
                            <li><i class="fa fa-user"></i><a href="#">Contacts</a></li>
                            <li><i class="fa fa-cogs"></i><a href="#">Configs</a></li>
                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i>Media Relation</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-opera"></i><a href="#">Product Orders</a></li>
                            <li><i class="fa fa-product-hunt"></i><a href="#">Programs</a></li>
                            <li><i class="fa fa-tasks"></i><a href="#">Placements</a></li>
                            <li><i class="fa fa-building"></i><a href="#">Vendors</a></li>
                            <li><i class="fa fa-television"></i><a href="#">Channels</a></li>
                            <li><i class="fa fa-user"></i><a href="#">Contacts</a></li>
                        </ul>
                    </li>
                -->
                <h3 class="menu-title">Admin</h3>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="menu-icon fa fa-users"></i>Users</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-id-card"></i><a href="admin/users.php">User Manager</a></li>
                            
                        </ul>
                    </li>
                
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside><!-- /#left-panel -->
<!-- Left Panel -->


<!-- Right Panel -->
<div id="right-panel" class="right-panel">
    <header id="header" class="header"> <!-- Begining of Right Panel Header-->
        <div class="header-menu">
            <div class="col-sm-2">
                <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
            </div>
            <div class="col-sm-5">
                <span id="page_title"></span>
            </div>
            <!--
            <div class="col-sm-2">
                
                <div class="header-left">
                    <button class="search-trigger"><i class="fa fa-search"></i></button>
                    <div class="form-inline">
                        <form class="search-form" action="search_result.php" method="get">
                            <input type="text" name="file_name" class="form-control mr-sm-2"  placeholder="Search files ..." >
                            <button class="btn btn-outline-info btn-sm" type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            -->

            <div class="col-sm-5" align="right">
                <div class="user-area dropdown float-right">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-user"></i> <?php echo $_SESSION['user']?>
                    </a>
                    <div class="user-menu dropdown-menu">
                        <a class="nav-link" href="profile.php"><i class="fa fa-eye"></i> My Profile</a>
                        <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>

    </header> <!-- End of Right Panel Header-->
    <body>

