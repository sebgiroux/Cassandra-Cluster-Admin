<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	require('include/kernel.inc.php');
	require('include/verify_login.inc.php');
	
	$keyspace_name = '';
	if (isset($_GET['keyspace_name'])) {
		$keyspace_name = $_GET['keyspace_name'];
	}
	
	$columnfamily_name = '';
	if (isset($_GET['columnfamily_name'])) {
		$columnfamily_name = $_GET['columnfamily_name'];
	}
	
	echo getHTML('header.php');
	
	// Make sure a keyspace name has been specified
	if ($keyspace_name == '') {
		echo displayErrorMessage('keyspace_name_must_be_specified');
	}
	else {
		if ($columnfamily_name == '') {
			echo displayErrorMessage('columnfamily_name_must_be_specified');
		}
		else {					
			$one_cf = getCFInKeyspace($keyspace_name,$columnfamily_name);
			
			// Make sure the column family exists in this keyspace
			if ($one_cf) {	
				$vw_vars['success_message'] = '';
				if (isset($_GET['new_value'])) {
					$new_value = $_GET['new_value'];
					$vw_vars['success_message'] = displaySuccessMessage('edit_counter',array('value' => $new_value));
				}
			
				$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
				$vw_vars['keyspace_name'] = $keyspace_name;
				$vw_vars['columnfamily_name'] = $columnfamily_name;
				
				echo getHTML('counters.php',$vw_vars);				
			}
			else {
				echo displayErrorMessage('columnfamily_doesnt_exists',array('column_name' => $columnfamily_name));
			}
		}
	}
	
	echo getHTML('footer.php');
?>