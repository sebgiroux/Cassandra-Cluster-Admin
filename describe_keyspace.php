<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	require('include/kernel.inc.php');
	
	echo getHTML('header.php');
	
	$vw_vars = array();
	
	$keyspace_name = '';
	if (isset($_GET['keyspace_name'])) {
		$keyspace_name = $_GET['keyspace_name'];
	}
	
	$describe_keyspace = $sys_manager->describe_keyspace($keyspace_name);
	
	$vw_vars['strategy_class'] = $describe_keyspace->strategy_class;
	$vw_vars['strategy_options'] = $describe_keyspace->strategy_options;
	$vw_vars['replication_factor'] = $describe_keyspace->replication_factor;
	
	$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
	$vw_vars['keyspace_name'] = $keyspace_name;
	
	$vw_vars['cf_defs'] = $describe_keyspace->cf_defs;
	
	$vw_vars['ring'] = array();
	
	try {
		$vw_vars['ring'] = $sys_manager->describe_ring($keyspace_name);
	}
	catch(Exception $e) {
		$vw_vars['ring'] = $e->getMessage();
	}
	
	echo getHTML('describe_keyspace.php',$vw_vars);
	echo getHTML('footer.php');
?>