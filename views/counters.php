<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <a href="describe_columnfamily.php?keyspace_name=<?=$keyspace_name?>&amp;columnfamily_name=<?=$columnfamily_name?>"><?=$columnfamily_name?></a> &gt; Counters</h3>

<?=$success_message?>
<?=$error_message?>

<form method="post" action="columnfamily_action.php">
	<div>
		<label for="key">Row Key:</label>
		<input id="key" name="key" type="text" />
	</div>

	<? if ($is_super_cf): ?>
	<div>
		<label for="super_column">Super Column Name:</label>
		<input id="super_column" name="super_column" type="text" />
	</div>
	<? endif;?>
	
	<div>
		<label for="column">Column name:</label>
		<input id="column" name="column" type="text" />
	</div>
	
	<div>
		<label for="action">Action:</label>
		<select id="action" name="action">
			<option value="inc">Increment</option>
			<option value="dec">Decrement</option>
		</select>
	</div>
	
	<div>
		<label for="key">Value:</label>
		<input id="value" name="value" type="text" value="1" />
	</div>	
	
	<div>
		<input type="submit" name="btn_modify_counter" value="Modify Counter" />
		
		<input type="hidden" name="keyspace_name" value="<?=$keyspace_name?>" />
		<input type="hidden" name="columnfamily_name" value="<?=$columnfamily_name?>" />
	</div>
</form>