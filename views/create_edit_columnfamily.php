<h3><a href="index.php"><?php echo $cluster_name; ?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?php echo $keyspace_name; ?>"><?php echo $keyspace_name; ?></a> <?php if (!empty($columnfamily_name)): echo "&gt; $columnfamily_name"; endif; ?> <?php if($mode=='create'): echo '&gt; Create Column Family'; endif;?></h3>

<?php echo $success_message; ?>
<?php echo $error_message; ?>

<form method="post" action="" id="columnfamily_form">

	<div>
		<label for="columnfamily_name">Column Family Name:</label>
		<input type="text" id="columnfamily_name" name="columnfamily_name" value="<?php echo $columnfamily_name; ?>" />
	</div>

	<div>
		<label for="column_type">Column Type:</label>
		<select id="column_type" name="column_type">
			<option value="Standard">Standard</option>
			<option value="Super">Super</option>
		</select>
	</div>
	
	<div>
		<label for="comparator_type">
			<div class="form_label">Comparator Type:</div>
			<div class="form_label_help" id="comparator_type_tooltip">?</div>			
		</label>
		<select id="comparator_type" name="comparator_type">
			<option value="org.apache.cassandra.db.marshal.AsciiType">AsciiType (ASCII String)</option>
			<option value="org.apache.cassandra.db.marshal.BytesType">BytesType (No Type)</option>
			<option value="org.apache.cassandra.db.marshal.LexicalUUIDType">LexicalUUIDType (Non-version 1 UUID)</option>
			<option value="org.apache.cassandra.db.marshal.LongType">LongType (64 Bit Integer)</option>
			<option value="org.apache.cassandra.db.marshal.TimeUUIDType">TimeUUIDType (Version 1 UUID (timestamp based))</option>
			<option value="org.apache.cassandra.db.marshal.UTF8Type">UTF8Type (UTF8 Encoded String)</option>
		</select>
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="subcomparator_type">
			<div class="form_label">Subcomparator Type:</div>
			<div class="form_label_help" id="subcomparator_type_tooltip">?</div>	
		</label>
		<select id="subcomparator_type" name="subcomparator_type">
			<option value="org.apache.cassandra.db.marshal.AsciiType">AsciiType (ASCII String)</option>
			<option value="org.apache.cassandra.db.marshal.BytesType">BytesType (No Type)</option>
			<option value="org.apache.cassandra.db.marshal.LexicalUUIDType">LexicalUUIDType (Non-version 1 UUID)</option>
			<option value="org.apache.cassandra.db.marshal.LongType">LongType (64 Bit Integer)</option>
			<option value="org.apache.cassandra.db.marshal.TimeUUIDType">TimeUUIDType (Version 1 UUID (timestamp based))</option>
			<option value="org.apache.cassandra.db.marshal.UTF8Type">UTF8Type (UTF8 Encoded String)</option>
		</select>
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="comment">Comment:</label>
		<input type="text" id="comment" name="comment" value="<?php echo $comment; ?>" />
	</div>
	
	<div>
		<label for="row_cache_size">
			<div class="form_label">Row Cache Size:</div>
			<div class="form_label_help" id="row_cache_size_tooltip">?</div>
		</label>
		<input type="text" id="row_cache_size" name="row_cache_size" value="<?php echo $row_cache_size; ?>" />
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="row_cache_save_period_in_seconds">
			<div class="form_label">Row Cached Save Period in Seconds:</div>
			<div class="form_label_help" id="row_cached_save_period_in_seconds_tooltip">?</div>
		</label>
		<input type="text" id="row_cache_save_period_in_seconds" name="row_cache_save_period_in_seconds" value="<?php echo $row_cache_save_period_in_seconds; ?>" />
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="key_cache_size">
			<div class="form_label">Key Cache Size:</div>
			<div class="form_label_help" id="key_cache_size_tooltip">?</div>
		</label>
		<input type="text" id="key_cache_size" name="key_cache_size" value="<?php echo $key_cache_size; ?>" />
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="key_cache_save_period_in_seconds">
			<div class="form_label">Key Cached Save Period in Seconds:</div>
			<div class="form_label_help" id="key_cached_save_period_in_seconds_tooltip">?</div>
		</label>
		<input type="text" id="key_cache_save_period_in_seconds" name="key_cache_save_period_in_seconds" value="<?php echo $key_cache_save_period_in_seconds; ?>" />
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="read_repair_chance">
			<div class="form_label">Read Repair Chance:</div>
			<div class="form_label_help" id="read_repair_chance_tooltip">?</div>
		</label>
		<input type="text" id="read_repair_chance" name="read_repair_chance" value="<?php echo $read_repair_chance; ?>" />
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="gc_grace_seconds">
			<div class="form_label">GC Grace Seconds:</div>
			<div class="form_label_help" id="gc_grace_seconds_tooltip">?</div>
		</label>
		<input type="text" id="gc_grace_seconds" name="gc_grace_seconds" value="<?php echo $gc_grace_seconds; ?>" />
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="memtable_operations_in_millions">
			<div class="form_label">Memtable Operations in Millions:</div>
			<div class="form_label_help" id="memtable_operations_in_millions_tooltip">?</div>
		</label>
		<input type="text" id="memtable_operations_in_millions" name="memtable_operations_in_millions" value="<?php echo $memtable_operations_in_millions; ?>" />
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="memtable_throughput_in_mb">
			<div class="form_label">Memtable Throughput in MB:</div>
			<div class="form_label_help" id="memtable_throughput_in_mb_tooltip">?</div>
		</label>
		<input type="text" id="memtable_throughput_in_mb" name="memtable_throughput_in_mb" value="<?php echo $memtable_throughput_in_mb; ?>" />
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="memtable_flush_after_mins">
			<div class="form_label">Memtable Flush After Mins:</div>
			<div class="form_label_help" id="memtable_flush_after_mins_tooltip">?</div>
		</label>
		<input type="text" id="memtable_flush_after_mins" name="memtable_flush_after_mins" value="<?php echo $memtable_flush_after_mins; ?>" />
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="default_validation_class">
			<div class="form_label">Default Validation Class:</div>
			<div class="form_label_help" id="default_validation_class_tooltip">?</div>
		</label>
		<input type="text" id="default_validation_class" name="default_validation_class" value="<?php echo $default_validation_class; ?>" /> <?php if ( version_compare($thrift_api_version,MINIMUM_THRIFT_API_VERSION_FOR_COUNTERS,'>=')): ?>* Use "CounterColumnType" for Counter Column<?php endif;?>
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="min_compaction_threshold">
			<div class="form_label">Min Compaction Threshold:</div>
			<div class="form_label_help" id="min_compaction_threshold_tooltip">?</div>
		</label>
		<input type="text" id="min_compaction_threshold" name="min_compaction_threshold" value="<?php echo $min_compaction_threshold; ?>" />
		<div class="clear_label"></div>
	</div>
	
	<div>
		<label for="max_compaction_threshold">
			<div class="form_label">Max Compaction Threshold:</div>
			<div class="form_label_help" id="max_compaction_threshold_tooltip">?</div>
		</label>
		<input type="text" id="max_compaction_threshold" name="max_compaction_threshold" value="<?php echo $max_compaction_threshold; ?>" />
		<div class="clear_label"></div>
	</div>
	
	<p class="form_tips">* Any field left blank will use the server default value.</p>
	
	<div>
		<input type="submit" name="<?php if ($mode == 'edit'): echo 'btn_edit_columnfamily'; else: echo 'btn_create_columnfamily'; endif; ?>" value="<?php if ($mode == 'edit'): echo 'Edit Column Family'; else: echo 'Create Column Family'; endif; ?>" />
		<input type="hidden" name="keyspace_name" value="<?php echo $keyspace_name; ?>" />
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		/*
			Set dropdown with right value on page load
		*/				
		if ('<?php echo $column_type; ?>' != '') $('#column_type').val('<?php echo $column_type; ?>');
		if ('<?php echo $comparator_type; ?>' != '') $('#comparator_type').val('<?php echo $comparator_type; ?>');
		if ('<?php echo $subcomparator_type; ?>' != '') $('#subcomparator_type').val('<?php echo $subcomparator_type; ?>');
		
		/*
			Disable comparator type if in edit mode
		*/
		if ('<?php echo $mode; ?>' == 'edit') {
			$('#columnfamily_name').attr('disabled','disabled');
			$('#comparator_type').attr('disabled','disabled');
			
			$('#columnfamily_form').append($('<input type="hidden" id="hidden_columnfamily_name" name="columnfamily_name" value="' + $('#columnfamily_name').val() + '" />'));
			$('#comparator_type').append($('<input type="hidden" id="hidden_comparator_type" name="comparator_type" value="' + $('#comparator_type').val() + '" />'));
		}
		
		/*
			Disable sub comparator type if column family is standard or in edit mode
		*/		
		if ($('#column_type').val() == 'Standard' || '<?php echo $mode; ?>' == 'edit') {
			$('#subcomparator_type').attr('disabled','disabled');
			
			$('#subcomparator_type').append($('<input type="hidden" id="hidden_subcomparator_type" name="subcomparator_type" value="' + $('#subcomparator_type').val() + '" />'));
		}
		
		/*
			Set right sub comparator type depending on column type and edit mode
		*/
		$('#column_type').change(function() {
			if ($('#column_type').val() == 'Standard' || '<?php echo $mode; ?>' == 'edit') {
				$('#subcomparator_type').attr('disabled','disabled');
				
				$('#subcomparator_type').append($('<input type="hidden" id="hidden_subcomparator_type" name="subcomparator_type" value="' + $('#subcomparator_type').val() + '" />'));
			}
			else {
				$('#subcomparator_type').attr('disabled','');
				
				$('#hidden_subcomparator_type').remove();
			}
		});
		
		/*
			Tooltips
		*/
		registerCFFormTooltips();
	});
</script>