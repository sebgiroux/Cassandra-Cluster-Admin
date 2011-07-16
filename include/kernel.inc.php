<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/	

	require('include/phpcassa/connection.php');
    require('include/phpcassa/columnfamily.php');
	require('include/phpcassa/sysmanager.php');
	
	require('conf.inc.php');
	
	define('MINIMUM_THRIFT_API_VERSION_FOR_COUNTERS','19.10.0');
	
	session_start();
	
	// Make sure the cluster index in the session still exists in the config array
	if (getClusterIndex() > count($CASSANDRA_CLUSTERS) - 1) {
		$_SESSION['cluster_index'] = 0;
	}
	
	try {	
		$random_server = getRandomNodeForCurrentCluster();
	
		$sys_manager = new SystemManager($random_server,getCredentialsForCurrentCluster(),1500,1500);
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
	
	function getCFInKeyspace($keyspace_name,$columnfamily_name) {
		global $sys_manager;
		
		try {
			$describe_keyspace = $sys_manager->describe_keyspace($keyspace_name);
		}
		catch(cassandra_NotFoundException $e) {
			return null;
		}
		
		$found = false;
		
		foreach ($describe_keyspace->cf_defs as $one_cf) {
			if ($one_cf->name == $columnfamily_name) {
				$found = true;
				break;
			}
		}
		
		if ($found) return $one_cf;
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
	}
	
	function displayCFRow($row_key,$keyspace_name,$columnfamily_name,$row,$scf_key = null,$is_counter_column = false) {		
		$vw_vars['scf_key'] = $scf_key;
		$vw_vars['row'] = $row;
		$vw_vars['keyspace_name'] = $keyspace_name;
		$vw_vars['columnfamily_name'] = $columnfamily_name;
		$vw_vars['row_key'] = $row_key;
		
		// If it's a column family of counter
		if ($is_counter_column) {			
			return getHTML('columnfamily_row_counter.php',$vw_vars);
		}
		else {		
			return getHTML('columnfamily_row.php',$vw_vars);
		}
	}
	
	function displaySCFRow($row_key,$keyspace_name,$columnfamily_name,$row,$is_counter_column = false) {
		$output = '';
		
		foreach ($row as $key => $value) {
			$output .= displayCFRow($row_key,$keyspace_name,$columnfamily_name,$value,$key,$is_counter_column);
		}	
		
		return $output;
	}
	
	$current_page_title = 'Cassandra Cluster Admin';
	
	function getPageTitle() {
		global $current_page_title;
		
		return $current_page_title;
	}
	
	/*
		Cluster helper function
	*/
	
	function getClusterIndex() {
		if (isset($_SESSION['cluster_index'])) {
			return $_SESSION['cluster_index'];
		}
		
		return 0;
	}
		
	function getClusterNameForIndex($index) {
		try {
			$random_server = getRandomNodeForIndex($index);
			$credentials = getCredentialsForIndex($index);
	
			$sys_manager = new SystemManager($random_server,$credentials,1500,1500);
			
			return $sys_manager->describe_cluster_name();
		}
		catch (TException $e) {
			return null;
		}		
	}
	
	function getArrayOfNodesForCurrentCluster() {
		global $CASSANDRA_CLUSTERS;
		
		$all_nodes = $CASSANDRA_CLUSTERS[getClusterIndex()]['nodes'];
		
		return $all_nodes;
	}
	
	function getRandomNodeForIndex($index) {
		global $CASSANDRA_CLUSTERS;
		
		$all_nodes = $CASSANDRA_CLUSTERS[$index]['nodes'];
		$random_server = $all_nodes[array_rand($all_nodes)];
		
		return $random_server;
	}
	
	function getRandomNodeForCurrentCluster() {
		return getRandomNodeForIndex(getClusterIndex());
	}
	
	function getCredentialsForIndex($index) {
		global $CASSANDRA_CLUSTERS;
		
		$cluster = $CASSANDRA_CLUSTERS[$index];
		
		$username = $cluster['username'];
		$password = $cluster['password'];
		
		if ($username == '' && $password == '') {
			return null;
		}
		
		return array('username' => $username, 'password' => $password);
	}
	
	function getCredentialsForCurrentCluster() {
		return getCredentialsForIndex(getClusterIndex());
	}
?>