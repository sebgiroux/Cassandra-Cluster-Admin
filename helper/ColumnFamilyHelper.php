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
		*/	
		public static function getCFInKeyspace($keyspace_name,$columnfamily_name) {
			global $sys_manager;
			
			try {
				$describe_keyspace = $sys_manager->describe_keyspace($keyspace_name);
			}
			catch(cassandra_NotFoundException $e) {
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
			Display a row out of a column family
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