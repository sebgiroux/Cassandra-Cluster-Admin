<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	/*
		Add as many Cassandra nodes as you want in the nodes array like so: array('10.70.80.90:9160','10.70.80.100:9160','10.70.80.110:9160')
		
		Multi cluster is also supported:
		
		$CASSANDRA_CLUSTERS = array(array('nodes' => array('127.0.0.1:9160'),
										 'username' => '',
										 'password' => ''),
								   array('nodes' => array('10.12.13.14:9160','10.13.12.14:9160'),
										 'username' => '',
										 'password' => ''));
	*/
	
	$CASSANDRA_CLUSTERS = array(array('nodes' => array('127.0.0.1:9160'),
								     'username' => '',
								     'password' => ''));
	
	/*
		Read-only keyspace
		
		Some keyspace can be set to read-only so nothing can be added/edited/deleted from them.
	*/
	
	define('READ_ONLY_KEYSPACES','system'); // Seperate by comma (,)
	
	/*
		Enter your Cassandra Cluster Admin credentials here
	*/
	define('CCA_LOGIN_REQUIRED',false);
	define('CCA_USERNAME','root'); 	
	define('CCA_PASSWORD','');
	
	define('MX4J_HTTP_ADAPTOR_PORT',8081);
	
	define('MAXIMUM_COLUMNS_TO_FETCH',10000);
	define('COLUMNS_TO_FETCH_PER_ITERATION',1000);
?>