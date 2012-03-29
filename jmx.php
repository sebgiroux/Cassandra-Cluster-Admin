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
	
	if (isset($_GET['change_mx4j_node'])) $_SESSION['mx4j_node'] = $_GET['change_mx4j_node'];
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
	
	if (function_exists('curl_init')) {		
		$mx4j = new MX4J($_SESSION['mx4j_node']);
		
		// Make sure MX4J is active
		if ($mx4j->isActive()) {
			if (isset($_GET['get_json_heap_memory_usage']) && $_GET['get_json_heap_memory_usage'] == 1) {
				die(json_encode($mx4j->getHeapMemoryUsage()));
			}
			
			if (isset($_GET['get_json_non_heap_memory_usage']) && $_GET['get_json_non_heap_memory_usage'] == 1) {
				die(json_encode($mx4j->getNonHeapMemoryUsage()));
			}
			
			if (isset($_GET['get_tp_stats']) && $_GET['get_tp_stats'] == 1) {
				die(json_encode($mx4j->getTpStats()));
			}
			
			if (isset($_GET['columnfamily_details']) && isset($_GET['keyspace_name']) && isset($_GET['columnfamily_name'])) {
				$keyspace_name = $_GET['keyspace_name'];
				$columnfamily_name = $_GET['columnfamily_name'];
			
				$details = array('details' => $mx4j->getColumnFamilyDetails($keyspace_name,$columnfamily_name),		
								 'key_cache' => $mx4j->getColumnFamilyKeyCacheDetails($keyspace_name,$columnfamily_name),	
								 'row_cache' => $mx4j->getColumnFamilyRowCacheDetails($keyspace_name,$columnfamily_name));	
				
				die(json_encode($details));
			}
		
			$vw_vars['heap_memory_usage'] = $mx4j->getHeapMemoryUsage();
			$vw_vars['non_heap_memory_usage'] = $mx4j->getNonHeapMemoryUsage();
			
			$vw_vars['nb_loaded_class'] = $mx4j->getLoadedClassCount();
			
			$vw_vars['tp_stats'] = $mx4j->getTpStats();

			$vw_vars['trigger_gc'] = null;
			$vw_vars['trigger_force_major_compaction'] = null;
			$vw_vars['trigger_invalidate_key_cache'] = null;
			$vw_vars['trigger_invalidate_row_cache'] = null;
			$vw_vars['trigger_force_flush'] = null;
			$vw_vars['trigger_disable_auto_compaction'] = null;
			$vw_vars['trigger_estimate_keys'] = null;
			
			// Trigger the garbage collector
			if (isset($_GET['trigger_gc']) && $_GET['trigger_gc'] == 1) {
				$trigger_gc = $mx4j->triggerGarbageCollection();
				
				$vw_vars['trigger_gc'] = $trigger_gc;
			}	
			
			// Trigger a major compaction
			if (isset($_GET['trigger_force_major_compaction']) && $_GET['trigger_force_major_compaction'] == 1 && isset($_GET['keyspace_name']) && isset($_GET['columnfamily_name'])) {
				$keyspace_name = $_GET['keyspace_name'];
				$columnfamily_name = $_GET['columnfamily_name'];
				
				$trigger_force_major_compaction = $mx4j->forceMajorCompaction($keyspace_name,$columnfamily_name);
				
				$vw_vars['trigger_force_major_compaction'] = $trigger_force_major_compaction;
			}
			
			// Trigger invalidate key cache
			if (isset($_GET['trigger_invalidate_key_cache']) && $_GET['trigger_invalidate_key_cache'] == 1 && isset($_GET['keyspace_name']) && isset($_GET['columnfamily_name'])) {
				$keyspace_name = $_GET['keyspace_name'];
				$columnfamily_name = $_GET['columnfamily_name'];
				
				$trigger_invalidate_key_cache = $mx4j->invalidateKeyCache($keyspace_name,$columnfamily_name);
				
				$vw_vars['trigger_invalidate_key_cache'] = $trigger_invalidate_key_cache;
			}
			
			// Trigger invalidate row cache
			if (isset($_GET['trigger_invalidate_row_cache']) && $_GET['trigger_invalidate_row_cache'] == 1 && isset($_GET['keyspace_name']) && isset($_GET['columnfamily_name'])) {
				$keyspace_name = $_GET['keyspace_name'];
				$columnfamily_name = $_GET['columnfamily_name'];
				
				$trigger_invalidate_row_cache = $mx4j->invalidateRowCache($keyspace_name,$columnfamily_name);
				
				$vw_vars['trigger_invalidate_row_cache'] = $trigger_invalidate_row_cache;
			}
			
			// Trigger force flush
			if (isset($_GET['trigger_force_flush']) && $_GET['trigger_force_flush'] == 1 && isset($_GET['keyspace_name']) && isset($_GET['columnfamily_name'])) {
				$keyspace_name = $_GET['keyspace_name'];
				$columnfamily_name = $_GET['columnfamily_name'];
				
				$trigger_force_flush = $mx4j->forceFlush($keyspace_name,$columnfamily_name);
				
				$vw_vars['trigger_force_flush'] = $trigger_force_flush;
			}
			
			// Trigger disable auto compaction
			if (isset($_GET['trigger_disable_auto_compaction']) && $_GET['trigger_disable_auto_compaction'] == 1 && isset($_GET['keyspace_name']) && isset($_GET['columnfamily_name'])) {
				$keyspace_name = $_GET['keyspace_name'];
				$columnfamily_name = $_GET['columnfamily_name'];
				
				$trigger_disable_auto_compaction = $mx4j->disableAutoCompaction($keyspace_name,$columnfamily_name);
				
				$vw_vars['trigger_disable_auto_compaction'] = $trigger_disable_auto_compaction;
			}
			
			// Trigger estimate keys
			if (isset($_GET['trigger_estimate_keys']) && $_GET['trigger_estimate_keys'] == 1 && isset($_GET['keyspace_name']) && isset($_GET['columnfamily_name'])) {
				$keyspace_name = $_GET['keyspace_name'];
				$columnfamily_name = $_GET['columnfamily_name'];
				
				$trigger_estimate_keys = $mx4j->estimateKeys($keyspace_name,$columnfamily_name);
				
				$vw_vars['trigger_estimate_keys'] = $trigger_estimate_keys;
			}	
			
			$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
			
			$vw_vars['all_nodes'] = $all_nodes;
			
			/*
				List of keyspaces and column families
			*/
			$ks_and_cf_details = ColumnFamilyHelper::getKeyspacesAndColumnFamiliesDetails();
			
			$vw_vars['ks_and_cf_details'] = $ks_and_cf_details;
			$vw_vars['jmx_host'] = $host;
			
			echo getHTML('header.php');
			echo getHTML('jmx.php',$vw_vars);
		}
		else {
			$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
			$vw_vars['all_nodes'] = $all_nodes;
			$vw_vars['jmx_host'] = $host;
			
			echo getHTML('header.php');
			echo getHTML('mx4j_not_active.php',$vw_vars);
		}
	}
	// CURL is not installed
	else {
		echo getHTML('header.php');
		echo getHTML('jmx_missing_dependencies.php');
	}
		
	echo getHTML('footer.php');
?>