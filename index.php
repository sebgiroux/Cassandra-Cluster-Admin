<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/
	
	require('include/kernel.inc.php');
		
	/*
		Display success message
	*/
	$vw_vars['success_message'] = '';
	
	if (isset($_GET['success_message'])) {
		$success_message = $_GET['success_message'];
		
		if ($success_message == 'drop_keyspace') {
			$vw_vars['success_message'] = displaySuccessMessage('drop_keyspace',array('keyspace_name' => $_SESSION['keyspace_name']));
		}
	}	
	
	/*
		Display error message
	*/
	$vw_vars['error_message'] = '';
	
	if (isset($_GET['error_message'])) {
		$error_message = $_GET['error_message'];
		
		if ($error_message == 'drop_keyspace') {
			$vw_vars['error_message'] = displayErrorMessage('drop_keyspace',array('keyspace_name' => $_SESSION['keyspace_name'],'message' => $_SESSION['message']));
		}
	}	
	
	echo getHTML('header.php');
	include('cluster_info.php');
	echo getHTML('footer.php');

?>