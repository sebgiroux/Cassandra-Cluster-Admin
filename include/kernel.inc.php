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
			return '<div class="success_message">Keyspace '.$params['keyspace_name'].' has been created successfully!</div>';
		}
		elseif ($index == 'edit_keyspace') {
			return '<div class="success_message">Keyspace '.$params['keyspace_name'].' has been edited successfully!</div>';
		}
		elseif ($index == 'drop_keyspace') {
			return '<div class="success_message">Keyspace '.$params['keyspace_name'].' has been dropped successfully!</div>';
		}
		elseif ($index == 'create_columnfamily') {
			return '<div class="success_message">Column family '.$params['columnfamily_name'].' has been created successfully!</div>';
		}
		elseif ($index == 'edit_columnfamily') {
			return '<div class="success_message">Column family '.$params['columnfamily_name'].' has been edited successfully!</div>';
		}
		elseif ($index == 'drop_columnfamily') {
			return '<div class="success_message">Column family dropped successfully!</div>';
		}
		elseif ($index == 'get_key') {
			return '<div class="success_message">Successfully got key "'.$params['key'].'"</div>';
		}
		elseif ($index == 'create_secondary_index') {
			return '<div class="success_message">Secondary index on column '.$params['column_name'].' has been created succesfully!</div>';
		}
		elseif ($index == 'insert_row') {
			return '<div class="success_message">Row inserted successfully!</div>';
		}
		elseif ($index == 'edit_row') {
			return '<div class="success_message">Row "'.$params['key'].'" edited successfully!</div>';
		}
		elseif ($index == 'edit_counter') {
			return '<div class="success_message">Counter row edited successfully. Value is now '.$params['value'].'!</div>';
		}
		elseif ($index == 'invoke_garbage_collector') {
			return '<div class="success_message">Garbage collector was invoked succesfully!</div>';
		}
		elseif ($index == 'invoke_force_major_compaction') {
			return '<div class="success_message">Force major compaction was invoked succesfully!</div>';
		}
		elseif ($index == 'invoke_invalidate_key_cache') {
			return '<div class="success_message">Invalidate key cache was invoked succesfully!</div>';
		}
		elseif ($index == 'invoke_invalidate_row_cache') {
			return '<div class="success_message">Invalidate row cache was invoked succesfully!</div>';
		}
		elseif ($index == 'invoke_force_flush') {
			return '<div class="success_message">Force flush was invoked succesfully!</div>';
		}
		elseif ($index == 'invoke_disable_auto_compaction') {
			return '<div class="success_message">Disable auto compaction was invoked succesfully!</div>';
		}
		elseif ($index == 'invoke_estimate_keys') {
			return '<div class="success_message">Estimate keys was invoked succesfully! Estimated keys value is : '.$params['nb_keys'].'</div>';
		}
		elseif ($index == 'query_secondary_index') {
			return '<div class="success_message">Successfully got '.$params['nb_results'].' rows from secondary index</div>';
		}
	}
	
	function displayInfoMessage($index,$params = array()) {
		if ($index == 'edit_keyspace_increased_replication_factor') {
			return '<div class="info_message">Tips: Looks like you increased the replication factor.<br />You might want to run "nodetool -h localhost repair" on all your Cassandra nodes.</div>';
		}
		elseif ($index == 'edit_keyspace_decreased_replication_factor') {
			return '<div class="info_message">Tips: Looks like you decreased the replication factor.<br />You might want to run "nodetool -h localhost cleanup" on all your Cassandra nodes.</div>';
		}
		elseif ($index == 'get_key_doesnt_exists') {
			return '<div class="info_message">Key "'.$params['key'].'" doesn\'t exists</div>';
		}
		elseif ($index == 'insert_row_not_empty') {
			return '<div class="info_message">Key must not be empty</div>';
		}
	}

	function displayErrorMessage($index,$params = array()) {
		if ($index == 'create_keyspace') {
			return '<div class="error_message">Keyspace '.$params['keyspace_name'].' couldn\'t be created.<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'edit_keyspace') {
			return '<div class="error_message">Keyspace '.$params['keyspace_name'].' couldn\'t be edited.<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'drop_keyspace') {
			return '<div class="error_message">Keyspace '.$params['keyspace_name'].' couldn\'t be dropped.<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'create_columnfamily') {
			return '<div class="error_message">Column family '.$params['columnfamily_name'].' couldn\'t be created.<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'edit_columnfamily') {
			return '<div class="error_message">Column family '.$params['columnfamily_name'].' couldn\'t be edited.<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'get_key') {
			return '<div class="error_message">Error during getting key: '.$params['message'].'</div>';
		}
		elseif ($index == 'insert_row') {
			return '<div class="error_message">Error while inserting row: '.$params['message'].'</div>';
		}
		elseif ($index == 'create_secondary_index') {
			return '<div class="error_message">Couldn\'t create secondary index on column '.$params['column_name'].'<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'keyspace_doesnt_exists') {
			return '<div class="error_message">Keyspace "'.$params['keyspace_name'].'" doesn\'t exists</div>';
		}
		elseif ($index == 'columnfamily_doesnt_exists') {
			return '<div class="error_message">Column family "'.$params['column_name'].'" doesn\'t exists</div>';
		}
		elseif ($index == 'keyspace_name_must_be_specified') {
			return '<div class="error_message">You must specify a keyspace name</div>';
		}
		elseif ($index == 'columnfamily_name_must_be_specified') {
			return '<div class="error_message">You must specify a column family name</div>';
		}
		elseif ($index == 'login_wrong_username_password') {
			return '<div class="error_message">Wrong username and/or password!</div>';
		}
		elseif ($index == 'you_must_be_logged') {
			return '<div class="error_message">You must be logged to access Cassandra Cluster Admin!</div>';
		}
		elseif ($index == 'invalid_action_specified') {
			return '<div class="error_message">Invalid action: '.$params['action'].'</div>';
		}
		elseif ($index == 'no_action_specified') {
			return '<div class="error_message">No action specified</div>';
		}
		elseif ($index == 'cassandra_server_error') {
			return '<div class="error_message">An error occured while connecting to your Cassandra server: '.$params['error_message'].'</div>';
		}
		elseif ($index == 'insert_row_incomplete_fields') {
			return '<div class="error_message">Some fields are empty</div>';
		}
		elseif ($index == 'drop_columnfamily') {
			return '<div class="error_message">Error while dropping column family: '.$params['message'].'</div>';
		}
		elseif ($index == 'something_wrong_happened') {
			return '<div class="error_message">Something wrong happened: '.$params['message'].'</div>';
		}
		elseif ($index == 'invoke_garbage_collector') {
			return '<div class="error_message">Invoking garbage collector failed.</div>';
		}		
		elseif ($index == 'invoke_force_major_compaction') {
			return '<div class="error_message">Invoking Force major compaction failed.</div>';
		}
		elseif ($index == 'invoke_invalidate_key_cache') {
			return '<div class="error_message">Invoking invalidate key cache failed.</div>';
		}
		elseif ($index == 'invoke_invalidate_row_cache') {
			return '<div class="error_message">Invoking invalidate row cache failed.</div>';
		}
		elseif ($index == 'invoke_force_flush') {
			return '<div class="error_message">Invoking force flush failed.</div>';
		}
		elseif ($index == 'invoke_disable_auto_compaction') {
			return '<div class="error_message">Invoking disable auto compaction failed.</div>';
		}
		elseif ($index == 'invoke_estimate_keys') {
			return '<div class="error_message">Invoking estimate keys failed.</div>';
		}
		elseif ($index == 'query_secondary_index') {
			return '<div class="error_message">Error while querying secondary index: '.$params['message'].'</div>';
		}
	}
	
	$current_page_title = 'Cassandra Cluster Admin';
	
	function getPageTitle() {
		global $current_page_title;
		
		return $current_page_title;
	}
?>