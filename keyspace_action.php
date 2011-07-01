<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	require('include/kernel.inc.php');
	require('include/verify_login.inc.php');
	
	$action = '';
	if (isset($_GET['action'])) $action = $_GET['action'];
	
	$vw_vars = array();	
		
	/*
		Create a column family
	*/	
	if (isset($_POST['btn_create_columnfamily'])) {
		$keyspace_name = '';
		if (isset($_GET['keyspace_name'])) {
			$keyspace_name = $_GET['keyspace_name'];
		}
		
		$columnfamily_name = '';
		if (isset($_GET['columnfamily_name'])) {
			$columnfamily_name = $_GET['columnfamily_name'];
		}
		
		$attrs = array();
		
		$column_type = $_POST['column_type'];
		if (!empty($column_type)) $attrs['column_type'] = $column_type;
		
		$comparator_type = $_POST['comparator_type'];
		if (!empty($comparator_type)) $attrs['comparator_type'] = $comparator_type;
		
		if (isset($_POST['subcomparator_type'])) {
			$subcomparator_type = $_POST['subcomparator_type'];
			if (!empty($subcomparator_type) && $column_type == 'Super') $attrs['subcomparator_type'] = $subcomparator_type;
		}
		
		$comment = $_POST['comment'];
		if (!empty($comment)) $attrs['comment'] = $comment;
		
		$row_cache_size = $_POST['row_cache_size'];
		if (!empty($row_cache_size)) $attrs['row_cache_size'] = $row_cache_size;		
		
		$row_cache_save_period_in_seconds = $_POST['row_cache_save_period_in_seconds'];
		if (!empty($row_cache_save_period_in_seconds)) $attrs['row_cache_save_period_in_seconds'] = $row_cache_save_period_in_seconds;		
		
		$key_cache_size = $_POST['key_cache_size'];
		if (!empty($key_cache_size)) $attrs['key_cache_size'] = $key_cache_size;		
		
		$key_cache_save_period_in_seconds = $_POST['key_cache_save_period_in_seconds'];
		if (!empty($key_cache_save_period_in_seconds)) $attrs['key_cache_save_period_in_seconds'] = $key_cache_save_period_in_seconds;		
		
		$read_repair_chance = $_POST['read_repair_chance'];
		if (!empty($read_repair_chance)) $attrs['read_repair_chance'] = $read_repair_chance;	
		
		$gc_grace_seconds = $_POST['gc_grace_seconds'];
		if (!empty($gc_grace_seconds)) $attrs['gc_grace_seconds'] = $gc_grace_seconds;	
		
		$memtable_operations_in_millions = $_POST['memtable_operations_in_millions'];
		if (!empty($memtable_operations_in_millions)) $attrs['memtable_operations_in_millions'] = $memtable_operations_in_millions;	
		
		$memtable_throughput_in_mb = $_POST['memtable_throughput_in_mb'];
		if (!empty($memtable_throughput_in_mb)) $attrs['memtable_throughput_in_mb'] = $memtable_throughput_in_mb;	
		
		$memtable_flush_after_mins = $_POST['memtable_flush_after_mins'];
		if (!empty($memtable_flush_after_mins)) $attrs['memtable_flush_after_mins'] = $memtable_flush_after_mins;	
		
		$default_validation_class = $_POST['default_validation_class'];
		if (!empty($default_validation_class)) $attrs['default_validation_class'] = $default_validation_class;	
		
		$min_compaction_threshold = $_POST['min_compaction_threshold'];
		if (!empty($min_compaction_threshold)) $attrs['min_compaction_threshold'] = $min_compaction_threshold;
		
		$max_compaction_threshold = $_POST['max_compaction_threshold'];		
		if (!empty($max_compaction_threshold)) $attrs['max_compaction_threshold'] = $max_compaction_threshold;
		
		try {
			$sys_manager->create_column_family($keyspace_name, $columnfamily_name, $attrs);
			
			$vw_vars['success_message'] = displaySuccessMessage('create_columnfamily',array('columnfamily_name' => $columnfamily_name));
		}
		catch (Exception $e) {
			$vw_vars['error_message'] = displayErrorMessage('create_columnfamily',array('columnfamily_name' => $columnfamily_name, 'message' => $e->getMessage()));
		}
	}	
	
	/*
		Create a column family
	*/
	
	if ($action == 'create_cf') {
		$keyspace_name = '';
		if (isset($_GET['keyspace_name'])) {
			$keyspace_name = $_GET['keyspace_name'];
		}
		
		$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();		
		$vw_vars['keyspace_name'] = $keyspace_name;
		
		$vw_vars['columnfamily_name'] = '';
		$vw_vars['column_type'] = '';
		$vw_vars['comparator_type'] = '';
		$vw_vars['subcomparator_type'] = '';
		$vw_vars['comment'] = '';
		$vw_vars['row_cache_size'] = '';
		$vw_vars['key_cache_size'] = '';
		$vw_vars['read_repair_chance'] = '';
		$vw_vars['gc_grace_seconds'] = '';
		$vw_vars['default_validation_class'] = '';
		$vw_vars['id'] = '';
		$vw_vars['min_compaction_threshold'] = '';
		$vw_vars['max_compaction_threshold'] = '';
		$vw_vars['row_cache_save_period_in_seconds'] = '';
		$vw_vars['key_cache_save_period_in_seconds'] = '';
		$vw_vars['memtable_flush_after_mins'] = '';
		$vw_vars['memtable_throughput_in_mb'] = '';
		$vw_vars['memtable_operations_in_millions'] = '';				
		
		$vw_vars['mode'] = 'create';		
				
		if (!isset($vw_vars['success_message'])) $vw_vars['success_message'] = '';
		if (!isset($vw_vars['error_message'])) $vw_vars['error_message'] = '';
		
		echo getHTML('header.php');
		echo getHTML('create_edit_columnfamily.php',$vw_vars);
	}
		
	/*
		Submit form create a keyspace
	*/
	
	if (isset($_POST['btn_create_keyspace'])) {
		$keyspace_name = $_POST['keyspace_name'];
		$replication_factor = $_POST['replication_factor'];
		$strategy = $_POST['strategy'];
		
		$attrs = array('replication_factor' => $replication_factor,'strategy_class' => $strategy);
		try {
			$sys_manager->create_keyspace($keyspace_name, $attrs);
			
			$vw_vars['success_message'] = displaySuccessMessage('create_keyspace',array('keyspace_name' => $keyspace_name));
		}
		catch (Exception $e) {
			$vw_vars['error_message'] = displayErrorMessage('create_keyspace',array('keyspace_name' => $keyspace_name,'message' => $e->getMessage()));
		}
	}
	
	/*
		Create a keyspace
	*/
	
	if ($action == 'create') {
		$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
		$vw_vars['keyspace_name'] = '';
		$vw_vars['replication_factor'] = '';
		
		$vw_vars['mode'] = 'create';
		
		if (!isset($vw_vars['success_message'])) $vw_vars['success_message'] = '';
		if (!isset($vw_vars['error_message'])) $vw_vars['error_message'] = '';
		
		echo getHTML('header.php');
		echo getHTML('create_edit_keyspace.php',$vw_vars);
	}
	
	/*
		Submit form edit a keyspace
	*/
	
	if (isset($_POST['btn_edit_keyspace'])) {
		$keyspace_name = $_POST['keyspace_name'];
		$replication_factor = $_POST['replication_factor'];
		$strategy = $_POST['strategy'];
		
		$attrs = array('replication_factor' => $replication_factor,'strategy_class' => $strategy);
		
		$describe_keyspace = $sys_manager->describe_keyspace($keyspace_name);
		
		try {	
			$sys_manager->alter_keyspace($keyspace_name, $attrs);
			
			$vw_vars['success_message'] = displaySuccessMessage('edit_keyspace',array('keyspace_name' => $keyspace_name));
			
			$old_replication_factor = $describe_keyspace->replication_factor;
			$new_replication_factor = $replication_factor;
			
			// Display tips about the replication factor that has been increased
			if ($old_replication_factor < $new_replication_factor) {
				$vw_vars['success_message'] .= displayInfoMessage('edit_keyspace_increased_replication_factor',array());
			}
			// Display tips about the replication factor that has been decreased
			elseif ($old_replication_factor > $new_replication_factor) {
				$vw_vars['success_message'] .= displayInfoMessage('edit_keyspace_decreased_replication_factor',array());			
			}
		}
		catch (Exception $e) {
			$vw_vars['error_message'] = displayErrorMessage('edit_keyspace',array('keyspace_name' => $keyspace_name,'message' => $e->getMessage()));
		}
	}
	
	/*
		Edit a keyspace
	*/
	
	if ($action == 'edit') {
		$keyspace_name = '';
		if (isset($_GET['keyspace_name'])) {
			$keyspace_name = $_GET['keyspace_name'];
		}
	
		$describe_keyspace = $sys_manager->describe_keyspace($keyspace_name);
	
		$vw_vars['cluster_name'] = $sys_manager->describe_cluster_name();
		$vw_vars['keyspace_name'] = $keyspace_name;
		$vw_vars['replication_factor'] = $describe_keyspace->replication_factor;
		
		$vw_vars['strategy_class'] = $describe_keyspace->strategy_class;
		
		$vw_vars['mode'] = 'edit';
		
		if (!isset($vw_vars['success_message'])) $vw_vars['success_message'] = '';
		if (!isset($vw_vars['error_message'])) $vw_vars['error_message'] = '';
		
		echo getHTML('header.php');
		echo getHTML('create_edit_keyspace.php',$vw_vars);
	}
	
	/*
		Drop a keyspace
	*/
	
	if ($action == 'drop') {
		$keyspace_name = '';
		if (isset($_GET['keyspace_name'])) {
			$keyspace_name = $_GET['keyspace_name'];
		}
		
		try {
			$sys_manager->drop_keyspace($keyspace_name);
			$_SESSION['success_message'] = 'drop_keyspace';
			$_SESSION['keyspace_name'] = $keyspace_name;
			
			redirect('index.php?success_message=drop_keyspace');
		}
		catch (Exception $e) {
			$_SESSION['error_message'] = 'drop_keyspace';
			$_SESSION['keyspace_name'] = $keyspace_name;
			$_SESSION['message'] = $e->getMessage();
			redirect('index.php?error_message=drop_keyspace');
		}
	}
	
	echo getHTML('footer.php');
?>