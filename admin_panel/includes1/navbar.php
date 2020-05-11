<style>
.fa-caret-down{
    color: black;
    padding: 9px;
    font-size: 31px;
}
.header-light .navbar-nav .avatar::before, .header-dark .navbar-nav .avatar::before {
    background: #51d2b700;
}
</style>
<nav class="navbar">
<!-- Logo Area -->
<div class="navbar-header">
<a href="index.php" class="navbar-brand">
<p class="logo-expand">Koo Families</p>
<p class="logo-collapse">Koo</p>
<!-- <p>OSCAR</p> -->
</a>
</div>
<!-- /.navbar-header -->
<!-- Left Menu & Sidebar Toggle -->
<ul class="nav navbar-nav">
<li class="sidebar-toggle"><a href="javascript:void(0)" class="ripple"><i class="fa fa-bars" aria-hidden="true"></i></a>
</li>
</ul>
<!-- /.navbar-left -->
<div class="spacer"></div>

<!-- User Image with Dropdown -->
<ul class="nav navbar-nav">
<li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle ripple" data-toggle="dropdown">
<span class="avatar thumb-sm"><img src="./Dashboard_files/user-image.png" class="rounded-circle" alt=""> 
<i class="fa fa-caret-down" aria-hidden="true"></i></span></a>
<div class="dropdown-menu dropdown-left dropdown-card-dark text-inverse" style="padding:8px">
	<!-- logout menu -->
	<a href="logout.php">Logout</a>
	<!-- // logout menu -->
</div>
</li>
</ul>
<!-- /.navbar-right -->
</nav>
