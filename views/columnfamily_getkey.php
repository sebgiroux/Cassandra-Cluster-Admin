<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <a href="describe_columnfamily.php?keyspace_name=<?=$keyspace_name?>&amp;columnfamily_name=<?=$columnfamily_name?>"><?=$columnfamily_name?></a> &gt; Get Key</h3>

<?=$success_message?>
<?=$error_message?>

<?=$results?>

<form method="post" action="">

	<div>
		<label for="key">Key:</label>
		<input id="key" name="key" type="text" />
	</div>

	<div>
		<input type="submit" name="btn_get_key" value="Get" />
	</div>
	
</form>