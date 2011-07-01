<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/	

	require('include/phpcassa/connection.php');
    require('include/phpcassa/columnfamily.php');
	require('include/phpcassa/sysmanager.php');
	
	require('conf.inc.php');
	
	try {
		$sys_manager = new SystemManager($CASSANDRA_SERVERS[array_rand($CASSANDRA_SERVERS)],$CREDENTIALS,1500,1500);
	}
	catch (TException $e) {
		die('An error happened while connecting to your Cassandra cluster: '.$e->getMessage());
	}
	
	function getHTML($filename, $php_params = array()) {
		if (!file_exists('views/'.$filename))
			die ('The view ' . $filename . ' doesn\'t exist');

		// If we got some params to be treated in php
		extract($php_params);
		
		ob_start();
		include('views/'.$filename);
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	} 
	
	function getCFInKeyspace($keyspace_name,$columnfamily_name) {
		global $sys_manager;
		
		$describe_keyspace = $sys_manager->describe_keyspace($keyspace_name);
		
		$found = false;
		
		foreach ($describe_keyspace->cf_defs as $one_cf) {
			if ($one_cf->name == $columnfamily_name) {
				$found = true;
				break;
			}
		}
		
		if ($found) return $one_cf;
	}
	
	function redirect($url) {
		header('Location: '.$url);
		exit();
	}
	
	function displaySuccessMessage($index,$params = array()) {
		if ($index == 'create_keyspace') {
			return '<div class="success_message">Keyspace '.$params['keyspace_name'].' has been created successfully!</div>';
		}
		elseif ($index == 'edit_keyspace') {
			return '<div class="success_message">Keyspace '.$params['keyspace_name'].' has been edited successfully!</div>';
		}
		elseif ($index == 'drop_keyspace') {
			return '<div class="success_message">Keyspace '.$params['keyspace_name'].' has been dropped successfully!</div>';
		}
		elseif ($index == 'create_columnfamily') {
			return '<div class="success_message">Column family '.$params['columnfamily_name'].' has been created successfully!</div>';
		}
		elseif ($index == 'edit_columnfamily') {
			return '<div class="success_message">Column family '.$params['columnfamily_name'].' has been edited successfully!</div>';
		}
		elseif ($index == 'get_key') {
			return '<div class="success_message">Successfully got key "'.$params['key'].'"</div>';
		}
		elseif ($index == 'create_secondary_index') {
			return '<div class="success_message">Secondary index on column '.$params['column_name'].' has been created succesfully!</div>';
		}
		elseif ($index == 'insert_row') {
			return '<div class="success_message">Row inserted successfully!</div>';
		}
	}
	
	function displayInfoMessage($index,$params = array()) {
		if ($index == 'edit_keyspace_increased_replication_factor') {
			return '<div class="info_message">Tips: Looks like you increased the replication factor.<br />You might want to run "nodetool -h localhost repair" on all your Cassandra nodes.</div>';
		}
		elseif ($index == 'edit_keyspace_decreased_replication_factor') {
			return '<div class="info_message">Tips: Looks like you decreased the replication factor.<br />You might want to run "nodetool -h localhost cleanup" on all your Cassandra nodes.</div>';
		}
		elseif ($index == 'get_key_doesnt_exists') {
			return '<div class="info_message">Key "'.$params['key'].'" doesn\'t exists</div>';
		}
		elseif ($index == 'insert_row_not_empty') {
			return '<div class="info_message">Key must not be empty</div>';
		}
	}

	function displayErrorMessage($index,$params = array()) {
		if ($index == 'create_keyspace') {
			return '<div class="error_message">Keyspace '.$params['keyspace_name'].' couldn\'t be created.<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'edit_keyspace') {
			return '<div class="error_message">Keyspace '.$params['keyspace_name'].' couldn\'t be edited.<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'drop_keyspace') {
			return '<div class="error_message">Keyspace '.$params['keyspace_name'].' couldn\'t be dropped.<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'create_columnfamily') {
			return '<div class="error_message">Column family '.$params['columnfamily_name'].' couldn\'t be created.<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'edit_columnfamily') {
			return '<div class="error_message">Column family '.$params['columnfamily_name'].' couldn\'t be edited.<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'get_key') {
			return '<div class="error_message">Error during getting key: '.$params['message'].'</div>';
		}
		elseif ($index == 'insert_row') {
			return '<div class="error_message">Error while inserting row: '.$params['message'].'</div>';
		}
		elseif ($index == 'create_secondary_index') {
			return '<div class="error_message">Couldn\'t create secondary index on column '.$params['column_name'].'<br /> Reason: '.$params['message'].'</div>';
		}
		elseif ($index == 'keyspace_doesnt_exists') {
			return '<div class="error_message">Keyspace "'.$params['keyspace_name'].'" doesn\'t exists</div>';
		}
		elseif ($index == 'columnfamily_doesnt_exists') {
			return '<div class="error_message">Column family "'.$params['column_name'].'" doesn\'t exists</div>';
		}
		elseif ($index == 'keyspace_name_must_be_specified') {
			return '<div class="error_message">You must specify a keyspace name</div>';
		}
		elseif ($index == 'columnfamily_name_must_be_specified') {
			return '<div class="error_message">You must specify a column family name</div>';
		}
		elseif ($index == 'login_wrong_username_password') {
			return '<div class="error_message">Wrong username and/or password!</div>';
		}
		elseif ($index == 'you_must_be_logged') {
			return '<div class="error_message">You must be logged to access Cassandra Cluster Admin!</div>';
		}
	}
	
	function displayCFRow($row,$scf_key = null) {
		$output = '<table border="1">';
		
		if (!is_null($scf_key)) $output .= '<tr><td colspan="2">'.$scf_key.'</td></tr>';
		
		foreach ($row as $key => $value) {
			$output .= '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
		}
		
		$output .= '</table><br />';
		
		return $output;
	}
	
	function displaySCFRow($row) {
		$output = '';
		foreach ($row as $key => $value) {
			$output .= displayCFRow($value,$key);
		}	
		
		return $output;
	}
	
	session_start();
?>