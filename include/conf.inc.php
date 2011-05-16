<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	define('CASSANDRA_SERVER','127.0.0.1:9160');
	
	/*
		Enter your cassandra credentials here if needed
	*/
	define('CASSANDRA_USERNAME','');
	define('CASSANDRA_PASSWORD','');
	
	$CREDENTIALS = null;
	if (CASSANDRA_USERNAME != '' && CASSANDRA_PASSWORD != '') {
		$CREDENTIALS = array('username' => 'Your username', 'password' => 'Your password');
	}	
?>