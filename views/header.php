<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
		<title><?php echo getPageTitle(); ?></title>
	
		<meta name="title" content="Cassandra Cluster Admin" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.flot.js"></script>
		<script type="text/javascript" src="js/jquery.simpletip.js"></script>
		<script type="text/javascript" src="js/cassandra_cluster_admin.js"></script>
		
		<link rel="stylesheet" type="text/css" href="css/style.css" />
	</head>
	
	<body>
		<h1 id="cca_title">Cassandra Cluster Admin</h1>
		<?php if (CCA_LOGIN_REQUIRED): ?><div class="float_right"><a href="logout.php">Logout</a></div><?php endif; ?>
		<div class="clear_both"></div>