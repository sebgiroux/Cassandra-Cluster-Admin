<h4><a href="describe_columnfamily.php?keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>"><?=$columnfamily_name?></a> - <a href="columnfamily_action.php?action=edit&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Edit</a></h4>
<ul>
	<li>Column Type: <?=$column_type?></li>
	<li>Comparator Type: <?=$comparator_type?></li>
	<li>Subcomparator Type: <?=$subcomparator_type?></li>
	<li>Comment: <?=$comment?></li>
	<li>Row Cache Size: <?=$row_cache_size?></li>
	<li>Key Cache Size: <?=$key_cache_size?></li>
	<li>Read Repair Chance: <?=$read_repair_chance?></li>
	<li>
		Column Metadata: 
		<?
			if (is_array($column_metadata)) {
				if (count($column_metadata) > 0) {
					foreach ($column_metadata as $key => $value) {
						echo $key.' '.$value;
					}
				}
				else {
					echo 'None';
				}
			}
			else {
				echo $column_metadata;
			}
		?>
	</li>
	<li>GC Grace Seconds: <?=$gc_grace_seconds?></li>
	<li>Default Validation Class: <?=$default_validation_class?></li>
	<li>ID: <?=$id?></li>
	<li>Min Compaction Threshold: <?=$min_compaction_threshold?></li>
	<li>Max Compaction Threshold: <?=$max_compaction_threshold?></li>
	<li>Row Cache Save Period In Seconds: <?=$row_cache_save_period_in_seconds?></li>
	<li>Key Cache Save Period In Seconds: <?=$key_cache_save_period_in_seconds?></li>
	<li>Memtable Flush After Mins: <?=$memtable_flush_after_mins?></li>
	<li>Memtable Throughput In MB: <?=$memtable_throughput_in_mb?></li>
	<li>Memtable Operations In Millions: <?=$memtable_operations_in_millions?></li>
</ul>