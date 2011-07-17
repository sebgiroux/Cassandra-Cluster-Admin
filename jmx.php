<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	require('include/kernel.inc.php');
	require('include/verify_login.inc.php');

	$all_nodes = $cluster_helper->getArrayOfNodesForCurrentCluster();
	$first_host = explode(':',$all_nodes[0]);
	$first_host = $first_host[0];
	
	if (isset($_GET['change_mx4j_node'])) $_SESSION['mx4j_node'] = $_GET['mx4j_node'];
	elseif (!isset($_SESSION['mx4j_node'])) $_SESSION['mx4j_node'] = $first_host;
	
	// If user come from another cluster, we need to refresh the node in the session
	$found = false;
	foreach ($all_nodes as $one_node) {
		list($host,$port) = explode(':',$one_node);
		
		if ($host == $_SESSION['mx4j_node']) {
			$found = true;
			break;
		}
	}
	
	if (!$found) $_SESSION['mx4j_node'] = $first_host;
	
	$mx4j = new MX4J($_SESSION['mx4j_node']);
	
	// Make sure MX4J is active
	if ($mx4j->isActive()) {	
		if (isset($_GET['get_json_heap_memory_usage']) && $_GET['get_json_heap_memory_usage'] == 1) {
			die(json_encode($mx4j->getHeapMemoryUsage()));
		}
		
		if (isset($_GET['get_json_non_heap_memory_usage']) && $_GET['get_json_non_heap_memory_usage'] == 1) {
			die(json_encode($mx4j->getNonHeapMemoryUsage()));
		}
	
		$vw_vars['heap_memory_usage'] = $mx4j->getHeapMemoryUsage();
		$vw_vars['non_heap_memory_usage'] = $mx4j->getNonHeapMemoryUsage();
		
		$vw_vars['nb_loaded_class'] = $mx4j->getLoadedClassCount();
		
		$vw_vars['tp_stats'] = $mx4j->getTpStats();

		$vw_vars['trigger_gc'] = null;
		
		// Trigger the garbage collector
		if (isset($_GET['trigger_gc']) && $_GET['trigger_gc'] == 1) {
			$trigger_gc = $mx4j->triggerGarbageCollection();
			
			$vw_vars['trigger_gc'] = $trigger_gc;
		}
		
		$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
		
		$vw_vars['all_nodes'] = $all_nodes;
		
		echo getHTML('header.php');
		echo getHTML('jmx.php',$vw_vars);
	}
	else {
		$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
		$vw_vars['jmx_host'] = $first_host;
		
		echo getHTML('header.php');
		echo getHTML('mx4j_not_active.php',$vw_vars);
	}
	
	echo getHTML('footer.php');
?>