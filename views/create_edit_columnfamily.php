<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> <?if (!empty($columnfamily_name)): echo "&gt; $columnfamily_name"; endif; ?> <?if($mode=='create'): echo '&gt; Create Column Family'; endif;?></h3>

<?=$success_message?>
<?=$error_message?>

<form method="post" action="" id="columnfamily_form">

	<div>
		<label for="columnfamily_name">Column Family Name:</label>
		<input type="text" id="columnfamily_name" name="columnfamily_name" value="<?=$columnfamily_name?>" />
	</div>

	<div>
		<label for="column_type">Column Type:</label>
		<select id="column_type" name="column_type">
			<option value="Standard">Standard</option>
			<option value="Super">Super</option>
		</select>
	</div>
	
	<div>
		<label for="comparator_type">Comparator Type:</label>
		<select id="comparator_type" name="comparator_type">
			<option value="org.apache.cassandra.db.marshal.AsciiType">AsciiType (ASCII String)</option>
			<option value="org.apache.cassandra.db.marshal.BytesType">BytesType (No Type)</option>
			<option value="org.apache.cassandra.db.marshal.LexicalUUIDType">LexicalUUIDType (Non-version 1 UUID)</option>
			<option value="org.apache.cassandra.db.marshal.LongType">LongType (64 Bit Integer)</option>
			<option value="org.apache.cassandra.db.marshal.TimeUUIDType">TimeUUIDType (Version 1 UUID (timestamp based))</option>
			<option value="org.apache.cassandra.db.marshal.UTF8Type">UTF8Type (UTF8 Encoded String)</option>
		</select>
	</div>
	
	<div>
		<label for="subcomparator_type">Subcomparator Type:</label>
		<select id="subcomparator_type" name="subcomparator_type">
			<option value="org.apache.cassandra.db.marshal.AsciiType">AsciiType (ASCII String)</option>
			<option value="org.apache.cassandra.db.marshal.BytesType">BytesType (No Type)</option>
			<option value="org.apache.cassandra.db.marshal.LexicalUUIDType">LexicalUUIDType (Non-version 1 UUID)</option>
			<option value="org.apache.cassandra.db.marshal.LongType">LongType (64 Bit Integer)</option>
			<option value="org.apache.cassandra.db.marshal.TimeUUIDType">TimeUUIDType (Version 1 UUID (timestamp based))</option>
			<option value="org.apache.cassandra.db.marshal.UTF8Type">UTF8Type (UTF8 Encoded String)</option>
		</select>
	</div>
	
	<div>
		<label for="comment">Comment:</label>
		<input type="text" id="comment" name="comment" value="<?=$comment?>" />
	</div>
	
	<div>
		<label for="row_cache_size">Row Cache Size:</label>
		<input type="text" id="row_cache_size" name="row_cache_size" value="<?=$row_cache_size?>" />
	</div>
	
	<div>
		<label for="row_cache_save_period_in_seconds">Row Cached Save Period in Seconds:</label>
		<input type="text" id="row_cache_save_period_in_seconds" name="row_cache_save_period_in_seconds" value="<?=$row_cache_save_period_in_seconds?>" />
	</div>
	
	<div>
		<label for="key_cache_size">Key Cache Size:</label>
		<input type="text" id="key_cache_size" name="key_cache_size" value="<?=$key_cache_size?>" />
	</div>
	
	<div>
		<label for="key_cache_save_period_in_seconds">Key Cached Save Period in Seconds:</label>
		<input type="text" id="key_cache_save_period_in_seconds" name="key_cache_save_period_in_seconds" value="<?=$key_cache_save_period_in_seconds?>" />
	</div>
	
	<div>
		<label for="read_repair_chance">Read Repair Chance:</label>
		<input type="text" id="read_repair_chance" name="read_repair_chance" value="<?=$read_repair_chance?>" />
	</div>
	
	<div>
		<label for="gc_grace_seconds">GC Grace Seconds:</label>
		<input type="text" id="gc_grace_seconds" name="gc_grace_seconds" value="<?=$gc_grace_seconds?>" />
	</div>
	
	<div>
		<label for="memtable_operations_in_millions">Memtable Operations in Millions:</label>
		<input type="text" id="memtable_operations_in_millions" name="memtable_operations_in_millions" value="<?=$memtable_operations_in_millions?>" />
	</div>
	
	<div>
		<label for="memtable_throughput_in_mb">Memtable Throughput in MB</label>
		<input type="text" id="memtable_throughput_in_mb" name="memtable_throughput_in_mb" value="<?=$memtable_throughput_in_mb?>" />
	</div>
	
	<div>
		<label for="memtable_flush_after_mins">Memtable Flush After Mins:</label>
		<input type="text" id="memtable_flush_after_mins" name="memtable_flush_after_mins" value="<?=$memtable_flush_after_mins?>" />
	</div>
	
	<div>
		<label for="default_validation_class">Default Validation Class</label>
		<input type="text" id="default_validation_class" name="default_validation_class" value="<?=$default_validation_class?>" />
	</div>
	
	<div>
		<label for="min_compaction_threshold">Min Compaction Threshold</label>
		<input type="text" id="min_compaction_threshold" name="min_compaction_threshold" value="<?=$min_compaction_threshold?>" />
	</div>
	
	<div>
		<label for="max_compaction_threshold">Max Compaction Threshold</label>
		<input type="text" id="max_compaction_threshold" name="max_compaction_threshold" value="<?=$max_compaction_threshold?>" />
	</div>
	
	<div>
		<input type="submit" name="<? if ($mode == 'edit'): echo 'btn_edit_columnfamily'; else: echo 'btn_create_columnfamily'; endif; ?>" value="<? if ($mode == 'edit'): echo 'Edit Column Family'; else: echo 'Create Column Family'; endif; ?>" />
	</div>
	
	<input type="hidden" name="keyspace_name" value="<?=$keyspace_name?>" />
</form>

<script type="text/javascript">
	$(document).ready(function() {
		/*
			Set dropdown with right value on page load
		*/		
		$('#column_type').val('<?=$column_type?>');
		$('#comparator_type').val('<?=$comparator_type?>');
		$('#subcomparator_type').val('<?=$subcomparator_type?>');
		
		/*
			Disable comparator type if in edit mode
		*/
		if ('<?=$mode?>' == 'edit') {
			$('#columnfamily_name').attr('disabled','disabled');
			$('#comparator_type').attr('disabled','disabled');
			
			$('#columnfamily_form').append($('<input type="hidden" id="hidden_columnfamily_name" name="columnfamily_name" value="' + $('#columnfamily_name').val() + '" />'));
			$('#comparator_type').append($('<input type="hidden" id="hidden_comparator_type" name="comparator_type" value="' + $('#comparator_type').val() + '" />'));
		}
		
		/*
			Disable sub comparator type if column family is standard or in edit mode
		*/		
		if ($('#column_type').val() == 'Standard' || '<?=$mode?>' == 'edit') {
			$('#subcomparator_type').attr('disabled','disabled');
			
			$('#subcomparator_type').append($('<input type="hidden" id="hidden_subcomparator_type" name="subcomparator_type" value="' + $('#subcomparator_type').val() + '" />'));
		}
		
		/*
			Set right sub comparator type depending on column type and edit mode
		*/
		$('#column_type').change(function() {
			if ($('#column_type').val() == 'Standard' || '<?=$mode?>' == 'edit') {
				$('#subcomparator_type').attr('disabled','disabled');
				
				$('#subcomparator_type').append($('<input type="hidden" id="hidden_subcomparator_type" name="subcomparator_type" value="' + $('#subcomparator_type').val() + '" />'));
			}
			else {
				$('#subcomparator_type').attr('disabled','');
				
				$('#hidden_subcomparator_type').remove();
			}
		});
	});
</script>