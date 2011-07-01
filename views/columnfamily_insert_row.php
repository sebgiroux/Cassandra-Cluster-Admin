<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <a href="describe_columnfamily.php?keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>"><?=$columnfamily_name?></a> &gt; Insert a Row</h3>

<script type="text/javascript">
	var num_columns = 1;

	function addColumn() {
		num_columns++;		
		$('#btn_add_column').remove();		
		$('#columns_list').append('<div class="clear_left"></div><div class="insert_row_column_name"><input id="column_name_' + num_columns + '" name="column_name_' + num_columns + '" type="text" class="smaller" /></div><input id="column_value_' + num_columns + '" name="column_value_' + num_columns + '" type="text" class="smaller" /> <input id="btn_add_column" type="button" value="Add..." onclick="addColumn();" />');
	}
</script>

<?=$success_message?>
<?=$info_message?>
<?=$error_message?>

<form method="post" action="">
	<div>
		<label for="key">Row Key:</label>
		<input id="key" name="key" type="text" />
	</div>
	
	<?php
		if ($is_super_cf) {
	?>
	<div>
		<label for="key">Super Column Key:</label>
		<input id="column_key" name="column_key" type="text" />
	</div>
	<?php
		}
	?>

	<div>
		<label for="column_name">Column name:</label>
		<span id="column_name">Column value:</span>
	</div>

	<div id="columns_list">
		<div class="insert_row_column_name"><input id="column_name_1" name="column_name_1" type="text" class="smaller" /></div>
		<input id="column_value_1" name="column_value_1" type="text" class="smaller" /> <input id="btn_add_column" type="button" value="Add..." onclick="addColumn();" />
	</div>
	
	<div>
		<input type="submit" name="btn_insert_row" value="Insert Row" />
	</div>
	
	<input type="hidden" name="keyspace_name" value="<?=$keyspace_name?>" />
	<input type="hidden" name="columnfamily_name" value="<?=$columnfamily_name?>" />
</form>