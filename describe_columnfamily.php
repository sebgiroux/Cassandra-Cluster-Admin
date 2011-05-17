<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	require('include/kernel.inc.php');
	
	echo getHTML('header.php');
	
	$keyspace_name = '';
	if (isset($_GET['keyspace_name'])) {
		$keyspace_name = $_GET['keyspace_name'];
	}
	
	$columnfamily_name = '';
	if (isset($_GET['columnfamily_name'])) {
		$columnfamily_name = $_GET['columnfamily_name'];
	}
	
	$describe_keyspace = $sys_manager->describe_keyspace($keyspace_name);
	
	$found = false;
	
	foreach ($describe_keyspace->cf_defs as $one_cf) {
		if ($one_cf->name == $columnfamily_name) {
			$found = true;
			break;
		}
	}
	
	$secondary_indexes = array();
	foreach ($one_cf->column_metadata as $metadata) {
		$index_type = $metadata->index_type;
		if ($metadata->index_type === 0) $index_type = 'Keys';
	
		$secondary_indexes[] = array('name' => $metadata->name,			
										'validation_class' => $metadata->validation_class,
										'index_type' => $index_type,
										'index_name' => $metadata->index_name);
	}
	
	$vw_vars['secondary_indexes'] = $secondary_indexes;
	
	$vw_vars['one_cf'] = $one_cf;
	
	$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
	$vw_vars['keyspace_name'] = $keyspace_name;
	$vw_vars['columnfamily_name'] = $columnfamily_name;
	
	$vw_vars['partitioner'] = $sys_manager->describe_partitioner();
	
	echo getHTML('describe_columnfamily.php',$vw_vars);
	echo getHTML('footer.php');
?>