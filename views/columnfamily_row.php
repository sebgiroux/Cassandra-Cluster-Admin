<h4><a href="describe_columnfamily.php?keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>"><?=$columnfamily_name?></a> - <a href="columnfamily_action.php?action=edit&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Edit</a></h4>
<table>
	<tr><td>Column Type</td><td><?=$column_type?></td></tr>
	<tr><td>Comparator Type</td><td><?=$comparator_type?></td></tr>
	<?php if ($subcomparator_type != ''): ?><tr><td>Subcomparator Type</td><td><?=$subcomparator_type?></td></tr><?php endif; ?>
	<?php if ($comment != ''): ?><tr><td>Comment</td><td><?=$comment?></td></tr><?php endif; ?>
	<tr><td>Row Cache Size</td><td><?=$row_cache_size?></td></tr>
	<tr><td>Key Cache Size</td><td><?=$key_cache_size?></td></tr>
	<tr><td>Read Repair Chance</td><td><?=$read_repair_chance?></td></tr>
	<tr><td>Column Metadata</td><td>
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
	</td></tr>
	<tr><td>GC Grace Seconds</td><td><?=$gc_grace_seconds?></td></tr>
	<tr><td>Default Validation Class</td><td><?=$default_validation_class?></td></tr>
	<tr><td>ID</td><td><?=$id?></td></tr>
	<tr><td>Min Compaction Threshold</td><td><?=$min_compaction_threshold?></td></tr>
	<tr><td>Max Compaction Threshold</td><td><?=$max_compaction_threshold?></td></tr>
	<tr><td>Row Cache Save Period In Seconds</td><td><?=$row_cache_save_period_in_seconds?></td></tr>
	<tr><td>Key Cache Save Period In Seconds</td><td><?=$key_cache_save_period_in_seconds?></td></tr>
	<tr><td>Memtable Flush After Mins</td><td><?=$memtable_flush_after_mins?></td></tr>
	<tr><td>Memtable Throughput In MB</td><td><?=$memtable_throughput_in_mb?></td></tr>
	<tr><td>Memtable Operations In Millions</td><td><?=$memtable_operations_in_millions?></td></tr>
</table>