<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <a href="describe_columnfamily.php?keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>"><?=$columnfamily_name?></a> &gt; <?php if ($mode == 'insert'): ?>Insert a Row<?php elseif ($mode == 'edit'): ?>Edit Row "<?php echo $key; ?>"<?php endif; ?></h3>

<script type="text/javascript">
	var num_columns = 0;

	function addColumn(name,value) {
		num_columns++;		
		$('#btn_add_column').remove();		
		$('#columns_list').append('<div class="clear_left"></div><div class="insert_row_column_name"><input id="column_name_' + num_columns + '" name="column_name_' + num_columns + '" type="text" class="smaller" value="' + name + '" /></div><input id="column_value_' + num_columns + '" name="column_value_' + num_columns + '" type="text" class="smaller" value="' + value + '" /> <input id="btn_add_column" type="button" value="Add..." onclick="addColumn(\'\',\'\');" />');
	}

	$(document).ready(function() {		
		<?php if ($mode == 'insert'): ?>addColumn('','');<?php endif; ?>
		<?php 
			if ($mode == 'edit'):
				if ($is_super_cf):
					
				else:
					foreach ($output as $name => $value):
						echo 'addColumn(\''.$name.'\',\''.$value.'\');';
					endforeach;
				endif;
			endif;
		?>
	});
</script>

<?=$success_message?>
<?=$info_message?>
<?=$error_message?>

<form method="post" action="">
	<div>
		<label for="key">Row Key:</label>
		<input id="key" name="key" type="text" value="<?php echo $key; ?>" <?php if ($mode == 'edit'): ?>disabled="disabled"<?php endif;?> />
	</div>
	
	<?php if ($is_super_cf): ?>
	<div>
		<label for="key">Super Column Key:</label>
		<input id="column_key" name="column_key" type="text" value="<?php echo $super_key; ?>" />
	</div>
	<?php endif; ?>

	<div>
		<label for="column_name">Column name:</label>
		<span id="column_name">Column value:</span>
	</div>

	<div id="columns_list"></div>
	
	<div>
		<input type="submit" name="btn_insert_row" value="<?php if ($mode == 'insert'): ?>Insert Row<?php elseif ($mode == 'edit'): ?>Edit Row<?php endif; ?>" />
	</div>
	
	<?php if ($mode == 'edit'): ?><input type="hidden" name="key" value="<?php echo $key; ?>" /><?php endif;?>
	<input type="hidden" name="keyspace_name" value="<?=$keyspace_name?>" />
	<input type="hidden" name="columnfamily_name" value="<?=$columnfamily_name?>" />
	<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
</form>