<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <?=$columnfamily_name?></h3>

<?php
	echo '<table>';
		echo '<tr><td>Column Type</td><td>'.$one_cf->column_type.'</td></tr>';
		echo '<tr><td>Comparator Type</td><td>'.$one_cf->comparator_type.'</td></tr>';
		echo '<tr><td>Subcomparator Type</td><td>'.$one_cf->subcomparator_type.'</td></tr>';
		echo '<tr><td>Comment</td><td>'.$one_cf->comment.'</td></tr>';
		echo '<tr><td>Row Cache Size</td><td>'.$one_cf->row_cache_size.'</td></tr>';
		echo '<tr><td>Key Cache Size</td><td>'.$one_cf->key_cache_size.'</td></tr>';
		echo '<tr><td>Read Repair Chance</td><td>'.$one_cf->read_repair_chance.'</td></tr>';
		echo '<tr><td>Column Metadata</td><td>'.$one_cf->column_metadata.'</td></tr>';
		echo '<tr><td>GC Grace Seconds</td><td>'.$one_cf->gc_grace_seconds.'</td></tr>';
		echo '<tr><td>Default Validation Class</td><td>'.$one_cf->default_validation_class.'</td></tr>';
		echo '<tr><td>ID</td><td>'.$one_cf->id.'</td></tr>';
		echo '<tr><td>Min Compaction Threshold</td><td>'.$one_cf->min_compaction_threshold.'</td></tr>';
		echo '<tr><td>Max Compaction Threshold</td><td>'.$one_cf->max_compaction_threshold.'</td></tr>';
		echo '<tr><td>Row Cache Save Period In Seconds</td><td>'.$one_cf->row_cache_save_period_in_seconds.'</td></tr>';
		echo '<tr><td>Key Cache Save Period In Seconds</td><td>'.$one_cf->key_cache_save_period_in_seconds.'</td></tr>';
		echo '<tr><td>Memtable Flush After Mins</td><td>'.$one_cf->memtable_flush_after_mins.'</td></tr>';
		echo '<tr><td>Memtable Throughput In MB</td><td>'.$one_cf->memtable_throughput_in_mb.'</td></tr>';
		echo '<tr><td>Memtable Operations In Millions</td><td>'.$one_cf->memtable_operations_in_millions.'</td></tr>';
	echo '</table>';
?>

<?php
	if (count($secondary_indexes) > 0) {
		echo '<h3>Secondary Indexes</h3>';
	
		foreach ($secondary_indexes as $one_si) {
			echo 'Column Name: '.$one_si['name'].'<br />';
			echo 'Validation Class: '.$one_si['validation_class'].'<br />';
			echo 'Index Type: '.$one_si['index_type'].'<br />';
			echo 'Index Name: '. $one_si['index_name'].'<br />';
		}
	}
?>

<p>
	<h3>Actions</h3>
	
	<?if ($partitioner == 'org.apache.cassandra.dht.OrderPreservingPartitioner'): echo '- <a href="columnfamily_action.php?action=browse_data&keyspace_name='.$keyspace_name.'&columnfamily_name='.$columnfamily_name.'">Browse Data</a><br/>'; endif;?>
	- <a href="columnfamily_action.php?action=create_secondary_index&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Create a Secondary Index</a><br />
	- <a href="columnfamily_action.php?action=get_key&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Get Key</a><br />
	- <a href="columnfamily_action.php?action=insert_row&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Insert a Row</a><br />
	- <a href="columnfamily_action.php?action=edit&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Edit Column Family</a><br />
	- <a href="#" onclick="return truncateColumnFamily('<?=$keyspace_name?>','<?=$columnfamily_name?>');">Truncate Column Family</a><br />
	- <a href="#" onclick="return dropColumnFamily('<?=$keyspace_name?>','<?=$columnfamily_name?>');">Drop Column Family</a>
</p>