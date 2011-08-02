<?php if ($show_edit_link): ?>
	<h4><a href="describe_columnfamily.php?keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>"><?php echo $columnfamily_name; ?></a> - <a href="columnfamily_action.php?action=edit&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>">Edit</a></h4>
<?php endif; ?>

<table>
	<tr><td>Column Type</td><td><?php echo $column_type; ?></td></tr>
	<tr><td>Comparator Type</td><td><?php echo $comparator_type; ?></td></tr>
	<?php if ($subcomparator_type != ''): ?><tr><td>Subcomparator Type</td><td><?php echo $subcomparator_type; ?></td></tr><?php endif; ?>
	<?php if ($comment != ''): ?><tr><td>Comment</td><td><?php echo $comment; ?></td></tr><?php endif; ?>
	<tr><td>Row Cache Size</td><td><?php echo $row_cache_size; ?></td></tr>
	<tr><td>Key Cache Size</td><td><?php echo $key_cache_size; ?></td></tr>
	<tr><td>Read Repair Chance</td><td><?php echo $read_repair_chance; ?></td></tr>
	<tr><td>Column Metadata</td><td>
		<?php
			if (is_array($column_metadata)) {
				if (count($column_metadata) > 0) {
					foreach ($column_metadata as $key => $value) {
						echo $key.': '. print_r($value,true).'<br />';
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
	<tr><td>GC Grace Seconds</td><td><?php echo $gc_grace_seconds; ?></td></tr>
	<tr><td>Default Validation Class</td><td><?php echo $default_validation_class; ?></td></tr>
	<tr><td>ID</td><td><?php echo $id; ?></td></tr>
	<tr><td>Min Compaction Threshold</td><td><?php echo $min_compaction_threshold; ?></td></tr>
	<tr><td>Max Compaction Threshold</td><td><?php echo $max_compaction_threshold; ?></td></tr>
	<tr><td>Row Cache Save Period In Seconds</td><td><?php echo $row_cache_save_period_in_seconds; ?></td></tr>
	<tr><td>Key Cache Save Period In Seconds</td><td><?php echo $key_cache_save_period_in_seconds; ?></td></tr>
	<tr><td>Memtable Flush After Mins</td><td><?php echo $memtable_flush_after_mins; ?></td></tr>
	<tr><td>Memtable Throughput In MB</td><td><?php echo $memtable_throughput_in_mb; ?></td></tr>
	<tr><td>Memtable Operations In Millions</td><td><?php echo $memtable_operations_in_millions; ?></td></tr>
</table>