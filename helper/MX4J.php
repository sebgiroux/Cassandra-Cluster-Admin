<?php
	class MX4J {
		private $host = '';
		private $port = 0;
	
		public function __construct($host, $port = MX4J_HTTP_ADAPTOR_PORT) {
			$this->host = $host;
			$this->port = $port;
		}
		
		private function getUrl() {
			return 'http://'.$this->host.':'.$this->port;
		}
		
		private function doCall($url,$return_as_array = true) {
			$ch = curl_init();
			
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_NOBODY, false); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $data = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
            curl_close($ch); 
			
			// Convert XML to Array
			if ($return_as_array) {
				libxml_use_internal_errors(true);
				$array_data = simplexml_load_string($data);
				libxml_clear_errors();
				
				return json_decode(json_encode($array_data),1);
			}
			else
				return $data;
		}
		
		private function extractMemoryInfo($memory_string) {
			// Extract memory info				
			preg_match('/contents={.*}/',$memory_string,$matches);
			
			$memory_string = substr($matches[0],10,-1);			
			$memory_parts = explode(',',$memory_string);
			
			$memory_output = array();
			foreach ($memory_parts as $memory) {
				list($name,$value) = array_map('trim',explode('=',$memory));
				$memory_output[$name] = $value;
			}
			
			return $memory_output;
		}
		
		private function extractProperty($data,$index,$name) {						
			if (isset($data['Attribute'][$index]['@attributes']) && $data['Attribute'][$index]['@attributes']['name'] == $name) {
				return $data['Attribute'][$index]['@attributes']['value'];
			}
		}
		
		public function isActive() {
			return $this->doCall($this->getUrl(),false) != '';
		}
	
		public function getHeapMemoryUsage() {
			$data = $this->doCall($this->getUrl().'/mbean?objectname=java.lang%3Atype%3DMemory&template=identity');
			
			if (isset($data['Attribute'][0]['@attributes']) && $data['Attribute'][0]['@attributes']['name'] == 'HeapMemoryUsage') {
				$memory_string = $data['Attribute'][0]['@attributes']['value'];	

				return $this->extractMemoryInfo($memory_string);
			}
		}
		
		public function getNonHeapMemoryUsage() {
			$data = $this->doCall($this->getUrl().'/mbean?objectname=java.lang%3Atype%3DMemory&template=identity');
			
			if (isset($data['Attribute'][1]['@attributes']) && $data['Attribute'][1]['@attributes']['name'] == 'NonHeapMemoryUsage') {
				$memory_string = $data['Attribute'][1]['@attributes']['value'];	
			
				return $this->extractMemoryInfo($memory_string);
			}
		}
				
		public function getLoadedClassCount() {
			$data = $this->doCall($this->getUrl().'/mbean?objectname=java.lang%3Atype%3DClassLoading&template=identity');
			
			return $this->extractProperty($data,1,'TotalLoadedClassCount');
		}
		
		public function getTpStats() {
			$tp_stats = array();

			// Anti Entropy
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.internal%3Atype%3DAntiEntropyStage&template=identity');			

			$tp_stats['anti_entropy']['name'] = 'Anti Entropy';
			$tp_stats['anti_entropy']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['anti_entropy']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['anti_entropy']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');

			// Flush Sorter
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.internal%3Atype%3DFlushSorter&template=identity');
			
			$tp_stats['flush_sorter']['name'] = 'Flush Sorter';
			$tp_stats['flush_sorter']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['flush_sorter']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['flush_sorter']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');

			// Flush Writer
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.internal%3Atype%3DFlushWriter&template=identity');
			
			$tp_stats['flush_writer']['name'] = 'Flush Writer';
			$tp_stats['flush_writer']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['flush_writer']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['flush_writer']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');

			// Gossip Stage
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.internal%3Atype%3DGossipStage&template=identity');
			
			$tp_stats['gossip_stage']['name'] = 'Gossip Stage';
			$tp_stats['gossip_stage']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['gossip_stage']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['gossip_stage']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');
			
			// Hinted Handoff
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.internal%3Atype%3DHintedHandoff&template=identity');
			
			$tp_stats['hinted_handoff']['name'] = 'Hinted Handoff';
			$tp_stats['hinted_handoff']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['hinted_handoff']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['hinted_handoff']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');		
			
			// Internal Response Stage
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.internal%3Atype%3DInternalResponseStage&template=identity');
			
			$tp_stats['internal_response_stage']['name'] = 'Internal Response Stage';
			$tp_stats['internal_response_stage']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['internal_response_stage']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['internal_response_stage']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');

			// Memtable Post Flusher
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.internal%3Atype%3DMemtablePostFlusher&template=identity');
			
			$tp_stats['memory_post_flusher']['name'] = 'Memory Post Flusher';
			$tp_stats['memory_post_flusher']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['memory_post_flusher']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['memory_post_flusher']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');
			
			// Migration Stage
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.internal%3Atype%3DMigrationStage&template=identity');
			
			$tp_stats['migration_stage']['name'] = 'Migration Stage';
			$tp_stats['migration_stage']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['migration_stage']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['migration_stage']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');
	
			// Misc Stage
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.internal%3Atype%3DMiscStage&template=identity');
			
			$tp_stats['misc_stage']['name'] = 'Misc Stage';
			$tp_stats['misc_stage']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['misc_stage']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['misc_stage']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');

			// Stream Stage
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.internal%3Atype%3DStreamStage&template=identity');
			
			$tp_stats['stream_stage']['name'] = 'Stream Stage';
			$tp_stats['stream_stage']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['stream_stage']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['stream_stage']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');
			
			// Mutation Stage
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.request%3Atype%3DMutationStage&template=identity');
			
			$tp_stats['mutation_stage']['name'] = 'Mutation Stage';
			$tp_stats['mutation_stage']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['mutation_stage']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['mutation_stage']['core_pool_size'] = $this->extractProperty($data,2,'CorePoolSize');
			$tp_stats['mutation_stage']['pending_tasks'] = $this->extractProperty($data,3,'PendingTasks');

			// Read Repair Stage
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.request%3Atype%3DReadRepairStage&template=identity');
			
			$tp_stats['read_repair_stage']['name'] = 'Read Repair Stage';
			$tp_stats['read_repair_stage']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['read_repair_stage']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['read_repair_stage']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');
			
			// Read Stage
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.request%3Atype%3DReadStage&template=identity');
			
			$tp_stats['read_stage']['name'] = 'Read Stage';
			$tp_stats['read_stage']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['read_stage']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['read_stage']['core_pool_size'] = $this->extractProperty($data,2,'CorePoolSize');
			$tp_stats['read_stage']['pending_tasks'] = $this->extractProperty($data,3,'PendingTasks');

			// Replication On Write Stage
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.request%3Atype%3DReplicateOnWriteStage&template=identity');
			
			$tp_stats['replication_on_write_stage']['name'] = 'Replication On Write Stage';
			$tp_stats['replication_on_write_stage']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['replication_on_write_stage']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['replication_on_write_stage']['core_pool_size'] = $this->extractProperty($data,2,'CorePoolSize');
			$tp_stats['replication_on_write_stage']['pending_tasks'] = $this->extractProperty($data,3,'PendingTasks');

			// Request Response Stage
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.request%3Atype%3DRequestResponseStage&template=identity');
			
			$tp_stats['request_response_stage']['name'] = 'Request Response Stage';
			$tp_stats['request_response_stage']['active_count'] = $this->extractProperty($data,0,'ActiveCount');
			$tp_stats['request_response_stage']['completed_tasks'] = $this->extractProperty($data,1,'CompletedTasks');
			$tp_stats['request_response_stage']['pending_tasks'] = $this->extractProperty($data,2,'PendingTasks');
			
			return $tp_stats;
		}
		
		public function triggerGarbageCollection() {
			$data = $this->doCall($this->getUrl().'/invoke?operation=gc&objectname=java.lang%3Atype%3DMemory&template=identity');
		
			if (isset($data['Operation']['@attributes'])) {
				$result = $data['Operation']['@attributes']['result'];
				
				return $result == 'success';
			}
		}
		
		public function getColumnFamilyDetails($keyspace_name,$columnfamily_name) {
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.db:type=ColumnFamilies,keyspace='.$keyspace_name.',columnfamily='.$columnfamily_name.'&template=identity');
			
			$return = array();
			
			foreach ($data['Attribute'] as $one_attribute) {
				$name = $one_attribute['@attributes']['name'];
				$value = $one_attribute['@attributes']['value'];
				
				$return[] = array('name' => $name,'value' => $value);
			}
			
			return $return;
		}
		
		public function getColumnFamilyKeyCacheDetails($keyspace_name,$columnfamily_name) {
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.db:type=Caches,keyspace='.$keyspace_name.',cache='.$columnfamily_name.'KeyCache&template=identity');
			
			$return = array();
			
			foreach ($data['Attribute'] as $one_attribute) {
				$name = $one_attribute['@attributes']['name'];
				$value = $one_attribute['@attributes']['value'];
				
				$return[] = array('name' => $name,'value' => $value);
			}
			
			return $return;
		}
		
		public function getColumnFamilyRowCacheDetails($keyspace_name,$columnfamily_name) {
			$data = $this->doCall($this->getUrl().'/mbean?objectname=org.apache.cassandra.db:type=Caches,keyspace='.$keyspace_name.',cache='.$columnfamily_name.'RowCache&template=identity');
			
			$return = array();
			
			foreach ($data['Attribute'] as $one_attribute) {
				$name = $one_attribute['@attributes']['name'];
				$value = $one_attribute['@attributes']['value'];
				
				$return[] = array('name' => $name,'value' => $value);
			}
			
			return $return;
		}
		
		public function forceMajorCompaction($keyspace_name,$columnfamily_name) {
			$data = $this->doCall($this->getUrl().'/invoke?operation=forceMajorCompaction&objectname=org.apache.cassandra.db:type=ColumnFamilies,keyspace='.$keyspace_name.',columnfamily='.$columnfamily_name.'&template=identity');
			
			if (isset($data['Operation']['@attributes'])) {
				$result = $data['Operation']['@attributes']['result'];
				
				return $result == 'success';
			}
		}
		
		public function invalidateKeyCache($keyspace_name,$columnfamily_name) {
			$data = $this->doCall($this->getUrl().'/invoke?operation=invalidateKeyCache&objectname=org.apache.cassandra.db:type=ColumnFamilies,keyspace='.$keyspace_name.',columnfamily='.$columnfamily_name.'&template=identity');
			
			if (isset($data['Operation']['@attributes'])) {
				$result = $data['Operation']['@attributes']['result'];
				
				return $result == 'success';
			}
		}
		
		public function invalidateRowCache($keyspace_name,$columnfamily_name) {
			$data = $this->doCall($this->getUrl().'/invoke?operation=invalidateRowCache&objectname=org.apache.cassandra.db:type=ColumnFamilies,keyspace='.$keyspace_name.',columnfamily='.$columnfamily_name.'&template=identity');
			
			if (isset($data['Operation']['@attributes'])) {
				$result = $data['Operation']['@attributes']['result'];
				
				return $result == 'success';
			}
		}
		
		public function forceFlush($keyspace_name,$columnfamily_name) {
			$data = $this->doCall($this->getUrl().'/invoke?operation=forceFlush&objectname=org.apache.cassandra.db:type=ColumnFamilies,keyspace='.$keyspace_name.',columnfamily='.$columnfamily_name.'&template=identity');
			
			if (isset($data['Operation']['@attributes'])) {
				$result = $data['Operation']['@attributes']['result'];
				
				return $result == 'success';
			}
		}
		
		public function disableAutoCompaction($keyspace_name,$columnfamily_name) {
			$data = $this->doCall($this->getUrl().'/invoke?operation=disableAutoCompaction&objectname=org.apache.cassandra.db:type=ColumnFamilies,keyspace='.$keyspace_name.',columnfamily='.$columnfamily_name.'&template=identity');
			
			if (isset($data['Operation']['@attributes'])) {
				$result = $data['Operation']['@attributes']['result'];
				
				return $result == 'success';
			}
		}
		
		public function estimateKeys($keyspace_name,$columnfamily_name) {
			$data = $this->doCall($this->getUrl().'/invoke?operation=estimateKeys&objectname=org.apache.cassandra.db:type=ColumnFamilies,keyspace='.$keyspace_name.',columnfamily='.$columnfamily_name.'&template=identity');
			
			if (isset($data['Operation']['@attributes'])) {
				$result = $data['Operation']['@attributes']['result'];
				$return = $data['Operation']['@attributes']['return'];
				
				return array('return' => $return,'result' => $result == 'success');
			}
		}
	}
?>