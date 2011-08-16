<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/	

	require('include/phpcassa/connection.php');
    require('include/phpcassa/columnfamily.php');
	require('include/phpcassa/sysmanager.php');
	
	require('helper/ClusterHelper.php');
	require('helper/ColumnFamilyHelper.php');
	require('helper/MX4J.php');
	
	require('conf.inc.php');
	
	define('MINIMUM_THRIFT_API_VERSION_FOR_COUNTERS','19.10.0');
	
	$cluster_helper = new ClusterHelper($CASSANDRA_CLUSTERS);
	
	session_start();
	
	// Make sure the cluster index in the session still exists in the config array
	if ($cluster_helper->getClusterIndex() > $cluster_helper->getClustersCount() - 1) {
		$_SESSION['cluster_index'] = 0;
	}
	
	try {	
		$random_server = $cluster_helper->getRandomNodeForCurrentCluster();
	
		$sys_manager = new SystemManager($random_server,$cluster_helper->getCredentialsForCurrentCluster(),1500,1500);
	}
	catch (TException $e) {
		die(getHTML('header.php').getHTML('server_error.php',array('error_message' => displayErrorMessage('cassandra_server_error',array('error_message' => $e->getMessage())))).getHTML('footer.php'));
	}
	
	function getHTML($filename, $php_params = array()) {
		if (!file_exists('views/'.$filename))
			die ('The view ' . $filename . ' doesn\'t exist');

		// If we got some params to be treated in php
		extract($php_params);
		
		ob_start();
		include('views/'.$filename);
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	} 
	
	function formatBytes($bytes) {
		if ($bytes < 1024) return $bytes.' B';
		elseif ($bytes < 1048576) return round($bytes / 1024, 2).' KB';
		elseif ($bytes < 1073741824) return round($bytes / 1048576, 2).' MB';
		elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2).' GB';
		else return round($bytes / 1099511627776, 2).' TB';
	}
	
	function redirect($url) {
		header('Location: '.$url);
		exit();
	}
	
	function displaySuccessMessage($index,$params = array()) {
		if ($index == 'create_keyspace') {
			$return = 'Keyspace '.$params['keyspace_name'].' has been created successfully!<br />Query took '.$params['query_time'];
		}
		elseif ($index == 'edit_keyspace') {
			$return = 'Keyspace '.$params['keyspace_name'].' has been edited successfully!<br />Query took '.$params['query_time'];
		}
		elseif ($index == 'drop_keyspace') {
			$return = 'Keyspace '.$params['keyspace_name'].' has been dropped successfully!<br />Query took '.$params['query_time'];
		}
		elseif ($index == 'create_columnfamily') {
			$return = 'Column family '.$params['columnfamily_name'].' has been created successfully!<br />Query took '.$params['query_time'];
		}
		elseif ($index == 'edit_columnfamily') {
			$return = 'Column family '.$params['columnfamily_name'].' has been edited successfully!<br />Query took '.$params['query_time'];
		}
		elseif ($index == 'drop_columnfamily') {
			$return = 'Column family dropped successfully!';
		}
		elseif ($index == 'get_key') {
			$return = 'Successfully got key "'.implode(',',$params['keys']).'"<br />Query took '.$params['query_time'];
		}
		elseif ($index == 'create_secondary_index') {
			$return = 'Secondary index on column '.$params['column_name'].' has been created succesfully!<br />Query took '.$params['query_time'];
		}
		elseif ($index == 'insert_row') {
			$return = 'Row inserted successfully!<br />Query took '.$params['query_time'];
		}
		elseif ($index == 'edit_row') {
			$return = 'Row "'.$params['key'].'" edited successfully!<br />Query took '.$params['query_time'];
		}
		elseif ($index == 'edit_counter') {
			$return = 'Counter row edited successfully. Value is now '.$params['value'].'!';
		}
		elseif ($index == 'invoke_garbage_collector') {
			$return = 'Garbage collector was invoked succesfully!';
		}
		elseif ($index == 'invoke_force_major_compaction') {
			$return = 'Force major compaction was invoked succesfully!';
		}
		elseif ($index == 'invoke_invalidate_key_cache') {
			$return = 'Invalidate key cache was invoked succesfully!';
		}
		elseif ($index == 'invoke_invalidate_row_cache') {
			$return = 'Invalidate row cache was invoked succesfully!';
		}
		elseif ($index == 'invoke_force_flush') {
			$return = 'Force flush was invoked succesfully!';
		}
		elseif ($index == 'invoke_disable_auto_compaction') {
			$return = 'Disable auto compaction was invoked succesfully!';
		}
		elseif ($index == 'invoke_estimate_keys') {
			$return = 'Estimate keys was invoked succesfully! Estimated keys value is : '.$params['nb_keys'];
		}
		elseif ($index == 'query_secondary_index') {
			$return = 'Successfully got '.$params['nb_results'].' rows from secondary index<br />Query took '.$params['query_time'];
		}
		
		return '<div class="success_message">'.$return.'</div>';
	}
	
	function displayInfoMessage($index,$params = array()) {
		if ($index == 'edit_keyspace_increased_replication_factor') {
			$return = 'Tips: Looks like you increased the replication factor.<br />You might want to run "nodetool -h localhost repair" on all your Cassandra nodes.';
		}
		elseif ($index == 'edit_keyspace_decreased_replication_factor') {
			$return = 'Tips: Looks like you decreased the replication factor.<br />You might want to run "nodetool -h localhost cleanup" on all your Cassandra nodes.';
		}
		elseif ($index == 'get_key_doesnt_exists') {
			$return = 'Key "'.$params['key'].'" doesn\'t exists';
		}
		elseif ($index == 'insert_row_not_empty') {
			$return = 'Key must not be empty';
		}
		
		return '<div class="info_message">'.$return.'</div>';
	}

	function displayErrorMessage($index,$params = array()) {
		if ($index == 'create_keyspace') {
			$return = 'Keyspace '.$params['keyspace_name'].' couldn\'t be created.<br /> Reason: '.$params['message'];
		}
		elseif ($index == 'edit_keyspace') {
			$return = 'Keyspace '.$params['keyspace_name'].' couldn\'t be edited.<br /> Reason: '.$params['message'];
		}
		elseif ($index == 'drop_keyspace') {
			$return = 'Keyspace '.$params['keyspace_name'].' couldn\'t be dropped.<br /> Reason: '.$params['message'];
		}
		elseif ($index == 'create_columnfamily') {
			$return = 'Column family '.$params['columnfamily_name'].' couldn\'t be created.<br /> Reason: '.$params['message'];
		}
		elseif ($index == 'edit_columnfamily') {
			$return = 'Column family '.$params['columnfamily_name'].' couldn\'t be edited.<br /> Reason: '.$params['message'];
		}
		elseif ($index == 'get_key') {
			$return = 'Error during getting key: '.$params['message'];
		}
		elseif ($index == 'insert_row') {
			$return = 'Error while inserting row: '.$params['message'];
		}
		elseif ($index == 'create_secondary_index') {
			$return = 'Couldn\'t create secondary index on column '.$params['column_name'].'<br /> Reason: '.$params['message'];
		}
		elseif ($index == 'keyspace_doesnt_exists') {
			$return = 'Keyspace "'.$params['keyspace_name'].'" doesn\'t exists';
		}
		elseif ($index == 'columnfamily_doesnt_exists') {
			$return = 'Column family "'.$params['column_name'].'" doesn\'t exists';
		}
		elseif ($index == 'keyspace_name_must_be_specified') {
			$return = 'You must specify a keyspace name';
		}
		elseif ($index == 'columnfamily_name_must_be_specified') {
			$return = 'You must specify a column family name';
		}
		elseif ($index == 'login_wrong_username_password') {
			$return = 'Wrong username and/or password!';
		}
		elseif ($index == 'you_must_be_logged') {
			$return = 'You must be logged to access Cassandra Cluster Admin!';
		}
		elseif ($index == 'invalid_action_specified') {
			$return = 'Invalid action: '.$params['action'];
		}
		elseif ($index == 'no_action_specified') {
			$return = 'No action specified';
		}
		elseif ($index == 'cassandra_server_error') {
			$return = 'An error occured while connecting to your Cassandra server: '.$params['error_message'];
		}
		elseif ($index == 'insert_row_incomplete_fields') {
			$return = 'Some fields are empty';
		}
		elseif ($index == 'drop_columnfamily') {
			$return = 'Error while dropping column family: '.$params['message'];
		}
		elseif ($index == 'something_wrong_happened') {
			$return = 'Something wrong happened: '.$params['message'];
		}
		elseif ($index == 'invoke_garbage_collector') {
			$return = 'Invoking garbage collector failed.';
		}		
		elseif ($index == 'invoke_force_major_compaction') {
			$return = 'Invoking Force major compaction failed.';
		}
		elseif ($index == 'invoke_invalidate_key_cache') {
			$return = 'Invoking invalidate key cache failed.';
		}
		elseif ($index == 'invoke_invalidate_row_cache') {
			$return = 'Invoking invalidate row cache failed.';
		}
		elseif ($index == 'invoke_force_flush') {
			$return = 'Invoking force flush failed.';
		}
		elseif ($index == 'invoke_disable_auto_compaction') {
			$return = 'Invoking disable auto compaction failed.';
		}
		elseif ($index == 'invoke_estimate_keys') {
			$return = 'Invoking estimate keys failed.';
		}
		elseif ($index == 'query_secondary_index') {
			$return = 'Error while querying secondary index: '.$params['message'];
		}
		
		return '<div class="error_message">'.$return.'</div>';
	}
	
	$current_page_title = 'Cassandra Cluster Admin';
	
	function getPageTitle() {
		global $current_page_title;
		
		return $current_page_title;
	}
	
	function getQueryTime($time_start,$time_end) {
		return round($time_end - $time_start,4).'sec';
	}
?>