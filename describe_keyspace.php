<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	require('include/kernel.inc.php');
	require('include/verify_login.inc.php');
	
	$keyspace_name = '';
	if (isset($_GET['keyspace_name'])) {
		$keyspace_name = $_GET['keyspace_name'];
	}
	
	$current_page_title = 'Cassandra Cluster Admin > Keyspace Details > '.$keyspace_name;
	echo getHTML('header.php');
	
	$vw_vars = array();
	
	if ($keyspace_name == '') {
		echo displayErrorMessage('keyspace_name_must_be_specified');
	}
	else {			
		try {
			$describe_keyspace = $sys_manager->describe_keyspace($keyspace_name);			
			
			// CF created successfully
			$vw_vars['added_cf'] = '';
			if (isset($_GET['create_cf']) == 1) {
				$columnfamily_name = $_SESSION['message'];
				$query_time = $_SESSION['query_time'];
				$vw_vars['added_cf'] = displaySuccessMessage('create_columnfamily',array('columnfamily_name' => $columnfamily_name,'query_time' => $query_time));
			}
		
			// CF deleted successfully
			$vw_vars['deleted_cf'] = '';
			if (isset($_GET['deleted_cf']) && $_GET['deleted_cf'] == 1) {
				$vw_vars['deleted_cf'] = displaySuccessMessage('drop_columnfamily');
			}
		
			$vw_vars['strategy_class'] = $describe_keyspace->strategy_class;
			
			$strategy_options = $describe_keyspace->strategy_options;
			
			$replication_factor = $describe_keyspace->replication_factor;
			if ($replication_factor == '' && isset($strategy_options['replication_factor'])) $replication_factor = $strategy_options['replication_factor'];		
			if ($replication_factor == '') $replication_factor = 1;
			$vw_vars['replication_factor'] = $replication_factor;			
			
			if (is_array($strategy_options) && isset($strategy_options['replication_factor'])) {
				unset($strategy_options['replication_factor']);
			}
			$vw_vars['strategy_options'] = $strategy_options;
			
			$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
			$vw_vars['keyspace_name'] = $keyspace_name;
			
			$vw_vars['ring'] = array();
			$vw_vars['list_column_families'] = '';
			
			$vw_vars['is_readonly_keyspace'] = isReadyOnlyKeyspace($keyspace_name);
			
			$nb_cfs = count($describe_keyspace->cf_defs);
			if ($nb_cfs == 0) {
				$vw_vars['list_column_families'] = 'There is no column family in this keyspace.';
			}
			else {			
				for ($i = 0; $i < $nb_cfs; $i++) {
					$one_cf = $describe_keyspace->cf_defs[$i];
				
					$vw_vars['columnfamily_name'] = $one_cf->name;
					$vw_vars['keyspace_name'] = $keyspace_name;
					
					$vw_vars['column_type'] = $one_cf->column_type;
					$vw_vars['comparator_type'] = $one_cf->comparator_type;
					$vw_vars['subcomparator_type'] = $one_cf->subcomparator_type;
					$vw_vars['comment'] = $one_cf->comment;
					$vw_vars['row_cache_size'] = $one_cf->row_cache_size;
					$vw_vars['key_cache_size'] = $one_cf->key_cache_size;
					$vw_vars['read_repair_chance'] = $one_cf->read_repair_chance;
					$vw_vars['column_metadata'] = $one_cf->column_metadata;
					$vw_vars['gc_grace_seconds'] = $one_cf->gc_grace_seconds;
					$vw_vars['default_validation_class'] = $one_cf->default_validation_class;
					$vw_vars['id'] = $one_cf->id;
					$vw_vars['min_compaction_threshold'] = $one_cf->min_compaction_threshold;
					$vw_vars['max_compaction_threshold'] = $one_cf->max_compaction_threshold;
					$vw_vars['row_cache_save_period_in_seconds'] = $one_cf->row_cache_save_period_in_seconds;
					$vw_vars['key_cache_save_period_in_seconds'] = $one_cf->key_cache_save_period_in_seconds;
					$vw_vars['memtable_flush_after_mins'] = $one_cf->memtable_flush_after_mins;
					$vw_vars['memtable_throughput_in_mb'] = $one_cf->memtable_throughput_in_mb;
					$vw_vars['memtable_operations_in_millions'] = $one_cf->memtable_operations_in_millions;
					
					$vw_vars['show_edit_link'] = true;
					
					$vw_vars['list_column_families'] .= getHTML('columnfamily_details.php',$vw_vars);
				}
			}
			
			try {
				$vw_vars['ring'] = $sys_manager->describe_ring($keyspace_name);
			}
			catch(Exception $e) {
				$vw_vars['ring'] = $e->getMessage();
			}
				
			echo getHTML('describe_keyspace.php',$vw_vars);			
		}
		catch(cassandra_NotFoundException $e) {
			echo displayErrorMessage('keyspace_doesnt_exists',array('keyspace_name' => $keyspace_name));
		}
	}
	
	echo getHTML('footer.php');
?>