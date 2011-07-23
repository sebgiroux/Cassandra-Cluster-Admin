<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/
	require_once('include/kernel.inc.php');
	require_once('include/verify_login.inc.php');
	
	$vw_vars['cluster_details'] = $CASSANDRA_CLUSTERS;
	$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
	$vw_vars['partitioner'] = $sys_manager->describe_partitioner();
	$vw_vars['snitch'] = $sys_manager->describe_snitch();
	$vw_vars['thrift_api_version'] = $sys_manager->describe_version();
	$vw_vars['schema_version'] = $sys_manager->describe_schema_versions();
	
	$vw_vars['cluster_helper'] = $cluster_helper;
			
	$ks_and_cf_details = ColumnFamilyHelper::getKeyspacesAndColumnFamiliesDetails();
	
	$keyspaces_name = $ks_and_cf_details['keyspaces_name'];
	$keyspaces_details = $ks_and_cf_details['keyspaces_details'];
	
	$vw_vars['keyspaces_name'] = $keyspaces_name;
	$vw_vars['keyspaces_details'] = $keyspaces_details;
	
	echo getHTML('cluster_info.php',$vw_vars);
?>