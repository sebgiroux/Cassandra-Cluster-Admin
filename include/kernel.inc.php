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
	
		return '<div class="error_message">'.nl2br(getLang('form_error_'.$index,$params)).'</div>';
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
?>