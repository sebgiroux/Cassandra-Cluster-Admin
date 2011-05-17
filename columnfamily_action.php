<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	require('include/kernel.inc.php');
	
	$action = '';
	if (isset($_GET['action'])) $action = $_GET['action'];
	
	/*
		Submit form edit column family
	*/
	
	if (isset($_POST['btn_edit_columnfamily'])) {
		$columnfamily_name = $_POST['columnfamily_name'];
		$column_type = $_POST['column_type'];
		
		$comment = $_POST['comment'];
		$row_cache_size = $_POST['row_cache_size'];
		$row_cache_save_period_in_seconds = $_POST['row_cache_save_period_in_seconds'];
		$key_cache_size = $_POST['key_cache_size'];
		$key_cache_save_period_in_seconds = $_POST['key_cache_save_period_in_seconds'];
		$read_repair_chance = $_POST['read_repair_chance'];
		$gc_grace_seconds = $_POST['gc_grace_seconds'];
		$memtable_operations_in_millions = $_POST['memtable_operations_in_millions'];
		$memtable_throughput_in_mb = $_POST['memtable_throughput_in_mb'];
		$memtable_flush_after_mins = $_POST['memtable_flush_after_mins'];
		$default_validation_class = $_POST['default_validation_class'];
		$min_compaction_threshold = $_POST['min_compaction_threshold'];
		$max_compaction_threshold = $_POST['max_compaction_threshold'];		
		
		$keyspace_name = $_POST['keyspace_name'];
		$columnfamily_name = $_POST['columnfamily_name'];
			
		$attrs = array('column_type' => $column_type,
					   'comment' => $comment,
					   'row_cache_size' => $row_cache_size,
					   'row_cache_save_period_in_seconds' => $row_cache_save_period_in_seconds,
					   'key_cache_size' => $key_cache_size,
					   'key_cache_save_period_in_seconds' => $key_cache_save_period_in_seconds,
					   'read_repair_chance' => $read_repair_chance,
					   'gc_grace_seconds' => $gc_grace_seconds,
					   'memtable_operations_in_millions' => $memtable_operations_in_millions,
					   'memtable_throughput_in_mb' => $memtable_throughput_in_mb,
					   'memtable_flush_after_mins' => $memtable_flush_after_mins,
					   'default_validation_class' => $default_validation_class,
					   'min_compaction_threshold' => $min_compaction_threshold,
					   'max_compaction_threshold' => $max_compaction_threshold);
		
		if (isset($_POST['comparator_type']))
			$attrs['comparator_type'] = $_POST['comparator_type'];
		
		if ($column_type == 'Super' && isset($_POST['subcomparator_type'])) {
			$attrs['subcomparator_type'] = $subcomparator_type;
		}
		
		try {
			$sys_manager->alter_column_family($keyspace_name, $columnfamily_name, $attrs);
			
			$vw_vars['success_message'] = displaySuccessMessage('edit_columnfamily',array('columnfamily_name' => $columnfamily_name));
		}
		catch(Exception $e) {
			$vw_vars['error_message'] = displayErrorMessage('edit_columnfamily',array('columnfamily_name' => $columnfamily_name,'message' => $e->getMessage()));
		}
	}	
		
	/*
		Edit a column family
	*/
	
	if ($action == 'edit') {
		$keyspace_name = $_GET['keyspace_name'];
		$columnfamily_name = $_GET['columnfamily_name'];
	
		$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();		
		$vw_vars['keyspace_name'] = $keyspace_name;
		$vw_vars['columnfamily_name'] = $columnfamily_name;
		
		$cf = getCFInKeyspace($keyspace_name,$columnfamily_name);
		
		$vw_vars['column_type'] = $cf->column_type;
		$vw_vars['comparator_type'] = $cf->comparator_type;
		$vw_vars['subcomparator_type'] = $cf->subcomparator_type;
		$vw_vars['comment'] = $cf->comment;
		$vw_vars['row_cache_size'] = $cf->row_cache_size;
		$vw_vars['key_cache_size'] = $cf->key_cache_size;
		$vw_vars['read_repair_chance'] = $cf->read_repair_chance;
		$vw_vars['gc_grace_seconds'] = $cf->gc_grace_seconds;
		$vw_vars['default_validation_class'] = $cf->default_validation_class;
		$vw_vars['id'] = $cf->id;
		$vw_vars['min_compaction_threshold'] = $cf->min_compaction_threshold;
		$vw_vars['max_compaction_threshold'] = $cf->max_compaction_threshold;
		$vw_vars['row_cache_save_period_in_seconds'] = $cf->row_cache_save_period_in_seconds;
		$vw_vars['key_cache_save_period_in_seconds'] = $cf->key_cache_save_period_in_seconds;
		$vw_vars['memtable_flush_after_mins'] = $cf->memtable_flush_after_mins;
		$vw_vars['memtable_throughput_in_mb'] = $cf->memtable_throughput_in_mb;
		$vw_vars['memtable_operations_in_millions'] = $cf->memtable_operations_in_millions;		
		
		if (!isset($vw_vars['success_message'])) $vw_vars['success_message'] = '';
		if (!isset($vw_vars['error_message'])) $vw_vars['error_message'] = '';
		
		$vw_vars['mode'] = 'edit';		
			
		echo getHTML('header.php');
		echo getHTML('create_edit_columnfamily.php',$vw_vars);
	}
	
	/*
		Drop a column family
	*/	
	
	if ($action == 'drop') {
		$keyspace_name = $_GET['keyspace_name'];
		$columnfamily_name = $_GET['columnfamily_name'];
	
		try {
			$sys_manager->drop_column_family($keyspace_name, $columnfamily_name);
		}
		catch (Exception $e) {
			echo 'Something wrong happened '.$e->getMessage();
		}
	}	
		
	/*
		Submit form create a secondary index
	*/
	
	if (isset($_POST['btn_create_secondary_index'])) {
		$keyspace_name = $_POST['keyspace_name'];
		$columnfamily_name = $_POST['columnfamily_name'];

		$column_name = $_POST['column_name'];
		$data_type = $_POST['data_type'];
		$index_name = $_POST['index_name'];
		
		try {
			$sys_manager->create_index($keyspace_name,$columnfamily_name,$column_name,$data_type,$index_name,IndexType::KEYS);			
			$vw_vars['success_message'] = displaySuccessMessage('create_secondary_index',array('column_name' => $column_name));
		}
		catch (Exception $e) {
			$vw_vars['error_message'] = displayErrorMessage('create_secondary_index',array('column_name' => $column_name,'message' => $e->getMessage()));
		}
	}
	
	/*
		Create a secondary index
	*/
	
	if ($action == 'create_secondary_index') {
		$keyspace_name = $_GET['keyspace_name'];
		$columnfamily_name = $_GET['columnfamily_name'];
	
		$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();		
		$vw_vars['keyspace_name'] = $keyspace_name;
		$vw_vars['columnfamily_name'] = $columnfamily_name;
		
		$vw_vars['mode'] = 'create';
	
		$cf = getCFInKeyspace($keyspace_name,$columnfamily_name);
	
		if (!isset($vw_vars['success_message'])) $vw_vars['success_message'] = '';
		if (!isset($vw_vars['error_message'])) $vw_vars['error_message'] = '';
	
		echo getHTML('header.php');
		echo getHTML('create_edit_secondary_index.php',$vw_vars);
	}
	
		
	/*
		Submit form get key
	*/	
	if (isset($_POST['btn_get_key'])) {
		$key = $_POST['key'];		
		
		$keyspace_name = $_GET['keyspace_name'];
		$columnfamily_name = $_GET['columnfamily_name'];
			
		$pool = new ConnectionPool($keyspace_name, array(CASSANDRA_SERVER));
		$column_family = new ColumnFamily($pool, $columnfamily_name);
		
		try {		
			$output = $column_family->get($key);
		
			$vw_vars['result'] = '<pre>'.print_r($output,true).'</pre>';			
			$vw_vars['success_message'] = displaySuccessMessage('get_key',array('key' => $key));
		}
		catch (cassandra_NotFoundException $e) {
			$vw_vars['success_message'] = displayInfoMessage('get_key_doesnt_exists',array('key' => $key));
		}
		catch (Exception $e) {
			$vw_vars['error_message'] = displayErrorMessage('get_key',array('message' => $e->getMessage()));
		}
	}
	
	/*
		Get a key
	*/
	
	if ($action == 'get_key') {
		$keyspace_name = $_GET['keyspace_name'];
		$columnfamily_name = $_GET['columnfamily_name'];
	
		$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();		
		$vw_vars['keyspace_name'] = $keyspace_name;
		$vw_vars['columnfamily_name'] = $columnfamily_name;
		
		$vw_vars['mode'] = 'create';
	
		$cf = getCFInKeyspace($keyspace_name,$columnfamily_name);
		
		if (!isset($vw_vars['result'])) $vw_vars['result'] = '';
		if (!isset($vw_vars['success_message'])) $vw_vars['success_message'] = '';
		if (!isset($vw_vars['error_message'])) $vw_vars['error_message'] = '';
		
		echo getHTML('header.php');
		echo getHTML('columnfamily_getkey.php',$vw_vars);
	}
	
		
	/*
		Submit form insert a row
	*/
	
	if (isset($_POST['btn_insert_row'])) {
		$keyspace_name = $_POST['keyspace_name'];
		$columnfamily_name = $_POST['columnfamily_name'];
		
		$key = $_POST['key'];
		
		$pool = new ConnectionPool($keyspace_name, array(CASSANDRA_SERVER));
		$column_family = new ColumnFamily($pool, $columnfamily_name);
		
		$no_column = 1;
		
		$data = array();
		
		while (isset($_POST['column_name_'.$no_column]) && isset($_POST['column_value_'.$no_column])) {
			$column_name = $_POST['column_name_'.$no_column];
			$column_value = $_POST['column_value_'.$no_column];
		
			if (!empty($_POST['column_name_'.$no_column]) && !empty($_POST['column_value_'.$no_column])) {
				$data[$column_name] = $column_value;
			}
			else
				break;
				
			$no_column++;
		}
		
		try {
			if (!empty($key)) {
				$column_family->insert($key,$data);
				$vw_vars['success_message'] = displaySuccessMessage('insert_row',array());
			}
			else {
				$vw_vars['info_message'] = displayInfoMessage('insert_row_not_empty',array());
			}
		}
		catch (Exception $e) {
			$vw_vars['error_message'] = displayErrormessage('insert_row',array('message' => $e->getMessage()));
		}
	}
	
	/*
		Insert a row
	*/
	
	if ($action == 'insert_row') {
		$keyspace_name = $_GET['keyspace_name'];
		$columnfamily_name = $_GET['columnfamily_name'];
		
		$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
		$vw_vars['keyspace_name'] = $keyspace_name;
		$vw_vars['columnfamily_name'] = $columnfamily_name;
		
		if (!isset($vw_vars['success_message'])) $vw_vars['success_message'] = '';
		if (!isset($vw_vars['info_message'])) $vw_vars['info_message'] = '';
		if (!isset($vw_vars['error_message'])) $vw_vars['error_message'] = '';
		
		echo getHTML('header.php');
		echo getHTML('columnfamily_insert_row.php',$vw_vars);
	}
	
	/*
		Truncate column family
	*/
	
	if ($action == 'truncate') {
		$keyspace_name = $_GET['keyspace_name'];
		$columnfamily_name = $_GET['columnfamily_name'];
		
		try {
			$sys_manager->truncate_column_family($keyspace_name, $columnfamily_name);
			
			redirect('describe_columnfamily.php?keyspace_name='.$keyspace_name.'&columnfamily_name='.$columnfamily_name);
		}
		catch(Exception $e) {
			echo 'Something wrong happened '.$e->getMessage();
		}
	}
	
	/*
		Browse data - Using get_range()
	*/
	
	if ($action == 'browse_data') {
		$keyspace_name = $_GET['keyspace_name'];
		$columnfamily_name = $_GET['columnfamily_name'];
		
		$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
		$vw_vars['keyspace_name'] = $keyspace_name;
		$vw_vars['columnfamily_name'] = $columnfamily_name;
		
		$pool = new ConnectionPool($keyspace_name, array(CASSANDRA_SERVER));
		$column_family = new ColumnFamily($pool, $columnfamily_name);
		
		try {					
			$offset_key = '';
			if (isset($_GET['offset_key'])) $offset_key = $_GET['offset_key'];
		
			$nb_rows = 5;
		
			$result = $column_family->get_range($offset_key,'',$nb_rows,null);
			
			$vw_vars['results'] = '';	
			$nb_results = 0;
			foreach ($result as $key => $value) {
				$vw_row_vars['key'] = $key;
				$vw_row_vars['value'] = $value;
				
				$vw_vars['results'] .= getHTML('columnfamily_browse_data_row.php',$vw_row_vars);
				
				$nb_results++;
			}		
			
			// We got the number of rows we asked for, display "Next Page" link
			if ($nb_results == $nb_rows) {
				$vw_vars['old_offset_key'] = $offset_key;
				
				$offset_key = ++$key;				
				
				$vw_vars['offset_key'] = $offset_key;
				$vw_vars['show_next_page_link'] = true;
			}
			else {
				$vw_vars['old_offset_key'] = '';
				$vw_vars['offset_key'] = '';
				$vw_vars['show_next_page_link'] = '';
			}
			
			echo getHTML('header.php');
			echo getHTML('columnfamily_browse_data.php',$vw_vars);
		}
		catch (Exception $e) {
			echo 'Something went wrong '.$e->getMessage();
		}
	}
	
	echo getHTML('footer.php');
?>