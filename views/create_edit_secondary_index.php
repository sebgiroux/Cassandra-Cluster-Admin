<ul class="breadcrumb">
	<li>
		<a href="index.php"><?php echo $cluster_name; ?></a> <span class="divider">/</span>
	</li>
	<li>
		<a href="describe_keyspace.php?keyspace_name=<?php echo $keyspace_name; ?>"><?php echo $keyspace_name; ?></a> <span class="divider">/</span>
	</li>
	<li>
		<a href="describe_columnfamily.php?keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>"><?php echo $columnfamily_name; ?></a> <span class="divider">/</span>
	</li>
	<li class="active">
		Create Secondary Index
	</li>
</ul>


<?php echo $success_message; ?>
<?php echo $error_message; ?>

<form method="post" action="" class="well">

	<div>
		<label for="column_name">Column Name:</label>
		<input id="column_name" name="column_name" type="text" />
	</div>
	
	<div>
		<label for="data_type">Data Type:</label>
		<select id="data_type" name="data_type">
			<option value="BytesType">BytesType</option>
			<option value="LongType">LongType</option>
			<option value="IntegerType">IntegerType</option>
			<option value="AsciiType">AsciiType</option>
			<option value="UTF8Type">UTF8Type</option>
			<option value="TimeUUIDType">TimeUUIDType</option>
			<option value="LexicalUUIDType">LexicalUUIDType</option>
		</select>
	</div>
	
	<div>
		<label for="index_name">Index Name:</label>
		<input id="index_name" name="index_name" type="text" />
	</div>
	
	<div>
		<label for="index_type">Index Type:</label>
		<select id="index_type" name="index_type" disabled="disabled">
			<option value="keys">Keys</option>
		</select>
	</div>	
	
	<div>
		<input type="submit" class="btn btn-primary" name="<?php if ($mode == 'edit'): echo 'btn_edit_secondary_index'; else: echo 'btn_create_secondary_index'; endif; ?>" value="<?php if ($mode == 'edit'): echo 'Edit Secondary Index'; else: echo 'Add Secondary Index'; endif; ?>" />
		<input type="hidden" name="keyspace_name" value="<?php echo $keyspace_name; ?>" />
		<input type="hidden" name="columnfamily_name" value="<?php echo $columnfamily_name; ?>" />
	</div>	
</form>