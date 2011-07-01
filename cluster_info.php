<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/
	require_once('include/kernel.inc.php');
	require_once('include/verify_login.inc.php');
	
	$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
	$vw_vars['partitioner'] = $sys_manager->describe_partitioner();
	$vw_vars['snitch'] = $sys_manager->describe_snitch();
	$vw_vars['thrift_api_version'] = $sys_manager->describe_version();
			
	$keyspaces = $sys_manager->describe_keyspaces();
	$keyspaces_name = array();
	$keyspaces_details = array();
	foreach ($keyspaces as $keyspace) {
		$keyspaces_name[] = $keyspace->name;
		
		$columnfamilys_name = array();
		
		foreach ($keyspace->cf_defs as $columnfamily) {
			$columnfamilys_name[] = $columnfamily->name;
		}
		
		$keyspaces_details[] = array('columnfamilys_name' => $columnfamilys_name);
	}
	
	$vw_vars['keyspaces_name'] = $keyspaces_name;
	$vw_vars['keyspaces_details'] = $keyspaces_details;
	
	echo getHTML('cluster_info.php',$vw_vars);
?>