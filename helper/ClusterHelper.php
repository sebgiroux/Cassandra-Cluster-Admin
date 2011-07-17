<?php
	/*
		Cassandra Cluster Admin
		
		Helper class to get data from the config array which can contains multiple Cassandra cluster
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	class ClusterHelper {		
		private $cassandra_clusters = array();
		
		public function __construct($cassandra_clusters) {
			$this->cassandra_clusters = $cassandra_clusters;
		}
		
		/*
			Get the current index in the array of cluster
		*/
		public function getClusterIndex() {
			if (isset($_SESSION['cluster_index'])) {
				return $_SESSION['cluster_index'];
			}
			
			return 0;
		}
			
		/*
			Get the name of the cluster at $index
		*/
		public function getClusterNameForIndex($index) {
			try {
				$random_server = $this->getRandomNodeForIndex($index);
				$credentials = $this->getCredentialsForIndex($index);
		
				$sys_manager = new SystemManager($random_server,$credentials,1500,1500);
				
				return $sys_manager->describe_cluster_name();
			}
			catch (TException $e) {
				return null;
			}		
		}
		
		/*
			Get an array of Cassandra nodes (IP:port) for the current cluster
		*/
		public function getArrayOfNodesForCurrentCluster() {			
			$all_nodes = $this->cassandra_clusters[$this->getClusterIndex()]['nodes'];
			
			return $all_nodes;
		}
		
		/*
			Get a random Cassandra node at $index
		*/
		public function getRandomNodeForIndex($index) {			
			$all_nodes = $this->cassandra_clusters[$index]['nodes'];
			$random_server = $all_nodes[array_rand($all_nodes)];
			
			return $random_server;
		}
		
		/*
			Get a random Cassandra node for the current cluster
		*/
		public function getRandomNodeForCurrentCluster() {
			return $this->getRandomNodeForIndex($this->getClusterIndex());
		}
		
		/*
			Get the Cassandra cluster credentials at $index
		*/
		public function getCredentialsForIndex($index) {			
			$cluster = $this->cassandra_clusters[$index];
			
			$username = $cluster['username'];
			$password = $cluster['password'];
			
			if ($username == '' && $password == '') {
				return null;
			}
			
			return array('username' => $username, 'password' => $password);
		}
		
		/*
			Get the Cassandra cluster credentials for the current cluster
		*/
		public function getCredentialsForCurrentCluster() {
			return $this->getCredentialsForIndex($this->getClusterIndex());
		}
		
		/*
			Get the number of clusters in the array
		*/
		public function getClustersCount() {
			return count($this->cassandra_clusters);
		}
	}
?>