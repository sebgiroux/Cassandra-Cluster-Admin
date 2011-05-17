<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <a href="describe_columnfamily.php?keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>"><?=$columnfamily_name?></a> &gt; Browse Data</h3>

<table border="1">
	<tr>
		<td>Key</td>
		<td>Value</td>
	</tr>
	<?=$results?>
</table>

<?if($show_next_page_link): echo '<a href="columnfamily_action.php?action=browse_data&keyspace_name='.$keyspace_name.'&columnfamily_name='.$columnfamily_name.'&old_offset_key='.$old_offset_key.'&offset_key='.$offset_key.'">Next Page &gt;</a>'; endif;?>