<h3><a href="index.php"><?=$cluster_name?></a> &gt; <?=$keyspace_name?></h3>

<p>
	Strategy Class: <?=$strategy_class?><br />
	Strategy Options: <?if (empty($strategy_options)): echo 'None'; else: echo $strategy_options; endif;?><br />
	Replication Factor: <?=$replication_factor?>
</p>

List of Column Families in Keyspace: 
<?
	for ($i = 0; $i < count($cf_defs); $i++):
		$one_cf = $cf_defs[$i];
	
		echo '<h4><a href="describe_columnfamily.php?keyspace_name='.$keyspace_name.'&columnfamily_name='.$one_cf->name.'">'.$one_cf->name.'</a> - <a href="columnfamily_action.php?action=edit&keyspace_name='.$keyspace_name.'&columnfamily_name='.$one_cf->name.'">Edit</a></h4>';
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
	endfor;
?>

<h3>Ring</h3>
	
<?
	if (is_array($ring)) {
		foreach ($ring as $node) {
			echo '<ul>';
				echo '<li>Start Token: '.$node->start_token.'</li>';
				echo '<li>End Token: '.$node->end_token.'</li>';
				foreach ($node->endpoints as $endpoint) {
					echo '<li>Endpoints: '.$endpoint.'</li>';
				}
			echo '</ul>';
		}
	}
	else {
		echo $ring;
	}

?>

<h3>Actions</h3>

- <a href="keyspace_action.php?action=create_cf&keyspace_name=<?=$keyspace_name?>">Create a New Column Family</a><br />
- <a href="keyspace_action.php?action=edit&keyspace_name=<?=$keyspace_name?>">Edit Keyspace</a><br />
- <a href="#" onclick="return dropKeyspace('<?=$keyspace_name?>');">Drop keyspace</a>
