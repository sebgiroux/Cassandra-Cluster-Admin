<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	$CASSANDRA_SERVERS = array('127.0.0.1:9160'); // Add as many Cassandra nodes as you want in this array
	
	/*
		Enter your Cassandra credentials here if needed
	*/
	define('CASSANDRA_USERNAME','');
	define('CASSANDRA_PASSWORD','');
	
	$CREDENTIALS = null;
	if (CASSANDRA_USERNAME != '' && CASSANDRA_PASSWORD != '') {
		$CREDENTIALS = array('username' => CASSANDRA_USERNAME, 'password' => CASSANDRA_PASSWORD);
	}	
	
	/*
		Enter your Cassandra Cluster Admin credentials here
	*/
	define('CCA_LOGIN_REQUIRED',true);
	define('CCA_USERNAME','root'); 	
	define('CCA_PASSWORD','');
?>