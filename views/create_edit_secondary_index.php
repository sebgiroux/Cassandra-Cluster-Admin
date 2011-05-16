<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <a href="describe_columnfamily.php?keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>"><?=$columnfamily_name?></a> &gt; Create Secondary Index</h3>

<?=$success_message?>
<?=$error_message?>

<form method="post" action="">

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
		<label for="index_name">Index Name (Optional):</label>
		<input id="index_name" name="index_name" type="text" />
	</div>
	
	<div>
		<label for="index_type">Index Type:</label>
		<select id="index_type" name="index_type" disabled="disabled">
			<option value="keys">Keys</option>
		</select>
	</div>	
	
	<div>
		<input type="submit" name="<? if ($mode == 'edit'): echo 'btn_edit_secondary_index'; else: echo 'btn_create_secondary_index'; endif; ?>" value="<? if ($mode == 'edit'): echo 'Edit Secondary Index'; else: echo 'Add Secondary Index'; endif; ?>" />
	</div>
	
	<input type="hidden" name="keyspace_name" value="<?=$keyspace_name?>" />
	<input type="hidden" name="columnfamily_name" value="<?=$columnfamily_name?>" />
</form>