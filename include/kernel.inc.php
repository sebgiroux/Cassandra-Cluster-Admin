<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/	

	require('include/phpcassa/connection.php');
    require('include/phpcassa/columnfamily.php');
	require('include/phpcassa/sysmanager.php');
	
	require('include/lang/english.php');
	
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
	
	/*
		Get the specified view and replace the php variables
	*/	
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
	
	/*
		Return a human readable file format from a number of bytes
	*/
	function formatBytes($bytes) {
		if ($bytes < 1024) return $bytes.' B';
		elseif ($bytes < 1048576) return round($bytes / 1024, 2).' KB';
		elseif ($bytes < 1073741824) return round($bytes / 1048576, 2).' MB';
		elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2).' GB';
		else return round($bytes / 1099511627776, 2).' TB';
	}
	
	/*
		Redirect the user to the specified URL
	*/
	function redirect($url) {
		header('Location: '.$url);
		exit();
	}
	
	/*
		Return the text for the index in the current language the user is using
	*/
	function getLang($index,$params) {	
		global $lang;
		
		$output = $lang[$index];
		
		foreach ($params as $key => $value) {
			$output = str_replace('%'.$key.'%',$value,$output);
		}
		
		return $output;
	}
	
	/*
		Return a message for a form success
	*/
	function displaySuccessMessage($index,$params = array()) {
		global $lang;
	
		return '<div class="success_message">'.getLang('form_success_'.$index,$params).'</div>';
	}
	
	/*
		Return a message for a form info
	*/
	function displayInfoMessage($index,$params = array()) {
		global $lang;
	
		return '<div class="info_message">'.getLang('form_info_'.$index,$params).'</div>';
	}

	/*
		Return a message for a form error
	*/
	function displayErrorMessage($index,$params = array()) {
		global $lang;
	
		$message = nl2br(getLang('form_error_'.$index,$params));
	
		if ($index == 'something_wrong_happened' && isset($params['message'])) {
			$message .= ' '.getCassandraMessage($params['message']);
		}
	
		return '<div class="error_message">'.$message.'</div>';
	}
	
	$current_page_title = 'Cassandra Cluster Admin';
	
	/*
		Get the currrent page title for the HTML page
	*/
	function getPageTitle() {
		global $current_page_title;
		
		return $current_page_title;
	}
	
	/*
		Return true if a keyspace is read-only, false otherwise
	*/	
	function isReadOnlyKeyspace($keyspace_name) {
		return in_array($keyspace_name,explode(',',READ_ONLY_KEYSPACES));
	}
	
	/*
		Return the number of seconds elapsed between the time start and time end
	*/
	function getQueryTime($time_start,$time_end) {
		return round($time_end - $time_start,4).'sec';
	}
	
	/*
		Return the column family definition in a user-readable format
	*/
	function displayOneCfDef($key) {
		return ucwords(str_replace('_',' ',$key));
	}
	
	function getCassandraMessage($exception_message) {
		preg_match('/The last error was (.*):/',$exception_message,$matches);
	
		if (isset($matches[1])) {
			switch ($matches[1]) {
				case 'cassandra_NotFoundException':
					return 'A specific column was requested that does not exist.';

				case 'cassandra_InvalidRequestException':
					return 'Invalid request could mean keyspace or column family does not exist, required parameters are missing, or a parameter is malformed. why contains an associated error message.';

				case 'cassandra_UnavailableException':
					return 'Not all the replicas required could be created and/or read.';

				case 'cassandra_TimedOutException':
					return 'The node responsible for the write or read did not respond during the rpc interval specified in your configuration (default 10s). This can happen if the request is too large, the node is oversaturated with requests, or the node is down but the failure detector has not yet realized it (usually this takes < 30s).';

				case 'cassandra_TApplicationException':
					return 'Internal server error or invalid Thrift method (possible if you are using an older version of a Thrift client with a newer build of the Cassandra server).';

				case 'cassandra_AuthenticationException':
					return 'Invalid authentication request (user does not exist or credentials invalid)';

				case 'cassandra_AuthorizationException':
					return 'Invalid authorization request (user does not have access to keyspace)';

				case 'cassandra_SchemaDisagreementException':
					return 'Schemas are not in agreement across all nodes';					
			}
			
			return '';
		}
	}
?>