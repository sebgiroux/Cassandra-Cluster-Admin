<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <?=$columnfamily_name?></h3>

<?php
	echo '<ul>';
		echo '<li>Column Type: '.$one_cf->column_type.'</li>';
		echo '<li>Comparator Type: '.$one_cf->comparator_type.'</li>';
		echo '<li>Subcomparator Type: '.$one_cf->subcomparator_type.'</li>';
		echo '<li>Comment: '.$one_cf->comment.'</li>';
		echo '<li>Row Cache Size: '.$one_cf->row_cache_size.'</li>';
		echo '<li>Key Cache Size: '.$one_cf->key_cache_size.'</li>';
		echo '<li>Read Repair Chance: '.$one_cf->read_repair_chance.'</li>';
		echo '<li>Column Metadata: '.$one_cf->column_metadata.'</li>';
		echo '<li>GC Grace Seconds: '.$one_cf->gc_grace_seconds.'</li>';
		echo '<li>Default Validation Class: '.$one_cf->default_validation_class.'</li>';
		echo '<li>ID: '.$one_cf->id.'</li>';
		echo '<li>Min Compaction Threshold: '.$one_cf->min_compaction_threshold.'</li>';
		echo '<li>Max Compaction Threshold: '.$one_cf->max_compaction_threshold.'</li>';
		echo '<li>Row Cache Save Period In Seconds: '.$one_cf->row_cache_save_period_in_seconds.'</li>';
		echo '<li>Key Cache Save Period In Seconds: '.$one_cf->key_cache_save_period_in_seconds.'</li>';
		echo '<li>Memtable Flush After Mins: '.$one_cf->memtable_flush_after_mins.'</li>';
		echo '<li>Memtable Throughput In MB: '.$one_cf->memtable_throughput_in_mb.'</li>';
		echo '<li>Memtable Operations In Millions: '.$one_cf->memtable_operations_in_millions.'</li>';
	echo '</ul>';
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
	
	- <a href="columnfamily_action.php?action=create_secondary_index&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Create a Secondary Index</a><br />
	- <a href="columnfamily_action.php?action=get_key&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Get Key</a><br />
	- <a href="columnfamily_action.php?action=insert_row&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Insert a Row</a><br />
	- <a href="columnfamily_action.php?action=edit&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Edit Column Family</a><br />
	- <a href="#" onclick="return truncateColumnFamily('<?=$keyspace_name?>','<?=$columnfamily_name?>');">Truncate Column Family</a><br />
	- <a href="#" onclick="return dropColumnFamily('<?=$keyspace_name?>','<?=$columnfamily_name?>');">Drop Column Family</a>
</p>