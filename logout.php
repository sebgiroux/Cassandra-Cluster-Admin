<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/
	
	require('include/kernel.inc.php');
	
	unset($_SESSION['cca_login']);
	redirect('login.php');
?>