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
	
	if (empty($keyspace_name)) {
		echo displayErrorMessage('keyspace_name_must_be_specified');
	}
	else {			
		try {
			$describe_keyspace = $sys_manager->describe_keyspace($keyspace_name);
			
			if (defined('CF_AUTOSORT') && CF_AUTOSORT) {
				usort($describe_keyspace->cf_defs, array('ColumnFamilyHelper', 'sortCfDefsCallable'));
			}
			
			// CF created successfully
			$vw_vars['added_cf'] = '';
			if (isset($_GET['create_cf'])) {
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
			
			$vw_vars['is_read_only_keyspace'] = isReadOnlyKeyspace($keyspace_name);
			
			$nb_cfs = count($describe_keyspace->cf_defs);
			if ($nb_cfs == 0) {
				$vw_vars['list_column_families'] = 'There is no column family in this keyspace.';
			}
			else {			
				for ($i = 0; $i < $nb_cfs; $i++) {
					$one_cf = $describe_keyspace->cf_defs[$i];
				
					$vw_vars['cf_def'] = $one_cf;
					$vw_vars['columnfamily_name'] = $one_cf->name;				
					$vw_vars['show_edit_link'] = true;
					$vw_vars['list_column_families'] .= '';
					$vw_vars['list_column_families'] .= getHTML('columnfamily_short_details.php',$vw_vars);
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
		catch(cassandra\NotFoundException $e) {
			echo displayErrorMessage('keyspace_doesnt_exists',array('keyspace_name' => $keyspace_name));
		}
	}
	
	echo getHTML('footer.php');
?>
