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
	
	$columnfamily_name = '';
	if (isset($_GET['columnfamily_name'])) {
		$columnfamily_name = $_GET['columnfamily_name'];
	}
	
	$current_page_title = 'Cassandra Cluster Admin > Column Family Details > '.$keyspace_name.' > '.$columnfamily_name;
	
	echo getHTML('header.php');
		
	// Make sure a keyspace name has been specified
	if ($keyspace_name == '') {
		echo displayErrorMessage('keyspace_name_must_be_specified');
	}
	else {
		if ($columnfamily_name == '') {
			echo displayErrorMessage('columnfamily_name_must_be_specified');
		}
		else {			
			$one_cf = getCFInKeyspace($keyspace_name,$columnfamily_name);
			
			// Make sure the column family exists in this keyspace
			if ($one_cf) {			
				$deleted_from_column = '';
				if (isset($_GET['action']) && $_GET['action'] == 'drop_index' && isset($_GET['column'])) {
					try {
						$sys_manager->drop_index($keyspace_name,$columnfamily_name,$_GET['column']);
						$deleted_from_column = $_GET['column'];
					}
					catch (Exception $e) {
						echo displayErrorMessage('something_wrong_happened',array('message' => $e->getMessage()));
					}
				}
			
				$secondary_indexes = array();
				foreach ($one_cf->column_metadata as $metadata) {				
					$index_type = $metadata->index_type;
					
					// Deleted index
					if (is_null($index_type) || $deleted_from_column == $metadata->name) {	
						continue;
					}
					
					if ($index_type === 0) $index_type = 'Keys';
				
					$secondary_indexes[] = array('name' => $metadata->name,			
													'validation_class' => $metadata->validation_class,
													'index_type' => $index_type,
													'index_name' => $metadata->index_name);
				}
				
				$vw_vars['secondary_indexes'] = $secondary_indexes;
			
				// Column family definition
				$vw_cf_vars['columnfamily_name'] = $one_cf->name;
				$vw_cf_vars['keyspace_name'] = $keyspace_name;
				
				$vw_cf_vars['column_type'] = $one_cf->column_type;
				$vw_cf_vars['comparator_type'] = $one_cf->comparator_type;
				$vw_cf_vars['subcomparator_type'] = $one_cf->subcomparator_type;
				$vw_cf_vars['comment'] = $one_cf->comment;
				$vw_cf_vars['row_cache_size'] = $one_cf->row_cache_size;
				$vw_cf_vars['key_cache_size'] = $one_cf->key_cache_size;
				$vw_cf_vars['read_repair_chance'] = $one_cf->read_repair_chance;
				$vw_cf_vars['column_metadata'] = $one_cf->column_metadata;
				$vw_cf_vars['gc_grace_seconds'] = $one_cf->gc_grace_seconds;
				$vw_cf_vars['default_validation_class'] = $one_cf->default_validation_class;
				$vw_cf_vars['id'] = $one_cf->id;
				$vw_cf_vars['min_compaction_threshold'] = $one_cf->min_compaction_threshold;
				$vw_cf_vars['max_compaction_threshold'] = $one_cf->max_compaction_threshold;
				$vw_cf_vars['row_cache_save_period_in_seconds'] = $one_cf->row_cache_save_period_in_seconds;
				$vw_cf_vars['key_cache_save_period_in_seconds'] = $one_cf->key_cache_save_period_in_seconds;
				$vw_cf_vars['memtable_flush_after_mins'] = $one_cf->memtable_flush_after_mins;
				$vw_cf_vars['memtable_throughput_in_mb'] = $one_cf->memtable_throughput_in_mb;
				$vw_cf_vars['memtable_operations_in_millions'] = $one_cf->memtable_operations_in_millions;
				
				$vw_cf_vars['show_edit_link'] = false;
				
				$vw_vars['columnfamily_def'] = getHTML('columnfamily_row.php',$vw_cf_vars);				
				
				$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
				$vw_vars['keyspace_name'] = $keyspace_name;
				$vw_vars['columnfamily_name'] = $columnfamily_name;
				
				$vw_vars['partitioner'] = $sys_manager->describe_partitioner();
				
				$vw_vars['thrift_api_version'] = $sys_manager->describe_version();
				
				$vw_vars['is_counter_column'] = $vw_cf_vars['default_validation_class'] == 'org.apache.cassandra.db.marshal.CounterColumnType';
				
				echo getHTML('describe_columnfamily.php',$vw_vars);
			}
			else {
				echo displayErrorMessage('columnfamily_doesnt_exists',array('column_name' => $columnfamily_name));
			}
		}
	}
	
	echo getHTML('footer.php');
?>