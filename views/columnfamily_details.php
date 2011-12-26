<?php if ($show_edit_link): ?>
	<h4>
		<a href="describe_columnfamily.php?keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>"><?php echo $columnfamily_name; ?></a> 
		<?php if (!$is_read_only_keyspace): ?>- <a href="columnfamily_action.php?action=edit&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>">Edit</a><?php endif; ?>
	</h4>
<?php endif; ?>

<table>
	<tr><td>Column Type</td><td><?php echo $column_type; ?></td></tr>
	<tr><td>Comparator Type</td><td><?php echo $comparator_type; ?></td></tr>
	<?php if ($subcomparator_type != ''): ?><tr><td>Subcomparator Type</td><td><?php echo $subcomparator_type; ?></td></tr><?php endif; ?>
	<?php if ($comment != ''): ?><tr><td>Comment</td><td><?php echo $comment; ?></td></tr><?php endif; ?>
	<tr><td>Row Cache Size</td><td><?php echo $row_cache_size; ?></td></tr>
	<tr><td>Key Cache Size</td><td><?php echo $key_cache_size; ?></td></tr>
	<tr><td>Read Repair Chance</td><td><?php echo $read_repair_chance; ?></td></tr>
	<tr><td valign="top">Column Metadata</td><td>
		<?php
			if (is_array($column_metadata)):
				if (count($column_metadata) > 0):
					for ($i = 0; $i < count($column_metadata); $i++):
						$value = $column_metadata[$i];
						if (is_array($value)):
							foreach ($value as $key => $one_value):
								echo $key.': '.$value.'<br />';
							endforeach;
						elseif (is_object($value)):
							$class_vars = get_object_vars($value);
							foreach ($class_vars as $key => $value):
								echo $key.': '.$value.'<br />';
							endforeach;
						else:
							echo $value.'<br />';
						endif;
						
						if ($i < count($column_metadata) - 1):
							echo '<hr size="1" />';
						endif;
					endfor;
				else:
					echo 'None';
				endif;
			else:
				echo $column_metadata;
			endif;
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