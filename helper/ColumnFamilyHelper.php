<?php
	/*
		Cassandra Cluster Admin
		
		Helper class to display column family data easily
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	class ColumnFamilyHelper {
		/*
			Return the column family definition for the specified keyspace name and column family name
			
			@param $keyspace_name 		The keyspace name that the column family is in
			@param $columnfamily_name 	The column family name to get
		*/	
		public static function getCFInKeyspace($keyspace_name,$columnfamily_name) {
			global $sys_manager;
			
			try {
				$describe_keyspace = $sys_manager->describe_keyspace($keyspace_name);
			}
			catch(cassandra\NotFoundException $e) {
				return null;
			}
			
			$found = false;
			
			foreach ($describe_keyspace->cf_defs as $one_cf) {
				if ($one_cf->name == $columnfamily_name) {
					$found = true;
					break;
				}
			}
			
			if ($found) return $one_cf;
		}
		
		/*
			Return an array with the name of all keyspaces and column family in current cluster.
		*/
		public static function getKeyspacesAndColumnFamiliesDetails() {
			global $sys_manager;
		
			try {
				$keyspaces = $sys_manager->describe_keyspaces();
				$keyspaces_name = array();
				$keyspaces_details = array();
				
				foreach ($keyspaces as $keyspace) {
					$keyspaces_name[] = $keyspace->name;
					
					$columnfamilies_name = array();
					
					foreach ($keyspace->cf_defs as $columnfamily) {
						$columnfamilies_name[] = $columnfamily->name;
					}
					
					$keyspaces_details[] = array('columnfamilies_name' => $columnfamilies_name);
				}
				
				return array('keyspaces_name' => $keyspaces_name, 'keyspaces_details' => $keyspaces_details);
			}
			catch(cassandra\InvalidRequestException $e) {
				die(getHTML('server_error.php',array('error_message' => displayErrorMessage('cassandra_server_error',array('error_message' => $e->getMessage())))).getHTML('footer.php'));
			}
		}
	
		/*
			Display a row out of a column family
			
			@param $row_key				The key of the row to display
			@param $keyspace_name		The keyspace name that row was in
			@param $columnfamily_name	The column family name that row was in
			@param $row					The complete row
			@param $scf_key				The key of the super column family, if it apply
			@param $is_counter_column 	True if the column family is a counter column, false otherwise
		*/
		public static function displayCFRow($row_key,$keyspace_name,$columnfamily_name,$row,$scf_key = null,$is_counter_column = false) {		
			$vw_vars['scf_key'] = $scf_key;
			$vw_vars['row'] = $row;
			$vw_vars['keyspace_name'] = $keyspace_name;
			$vw_vars['columnfamily_name'] = $columnfamily_name;
			$vw_vars['row_key'] = $row_key;
			
			// If it's a column family of counter
			if ($is_counter_column) {			
				return getHTML('columnfamily_row_counter.php',$vw_vars);
			}
			else {		
				return getHTML('columnfamily_row.php',$vw_vars);
			}
		}
		
		/*
			Display a row out of a super column family
			
			@param $row_key				The key of the row to display
			@param $keyspace_name		The keyspace name that row was in
			@param $columnfamily_name	The column family name that row was in
			@param $row					The complete row
			@param $is_counter_column 	True if the column family is a counter column, false otherwise
		*/
		public static function displaySCFRow($row_key,$keyspace_name,$columnfamily_name,$row,$is_counter_column = false) {
			$output = '';
			
			foreach ($row as $key => $value) {
				$output .= ColumnFamilyHelper::displayCFRow($row_key,$keyspace_name,$columnfamily_name,$value,$key,$is_counter_column);
			}	
			
			return $output;
		}
	}
?>