<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <a href="describe_columnfamily.php?keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>"><?=$columnfamily_name?></a> &gt; Browse Data</h3>

<p>Show <input type="text" name="show_nb_rows" id="show_nb_rows" class="tiny" onkeydown="if (event.keyCode == 13) $('#btn_change_rows').click();" value="<?php echo $nb_rows; ?>" /> rows <input type="button" value="Go" id="btn_change_rows" onclick="changeRowsPerPage('<?php echo $keyspace_name; ?>','<?php echo $columnfamily_name; ?>','<?php echo $current_offset_key; ?>');" /></p>

<table border="1" style="min-width: 500px;" cellpadding="5">
	<tr>
		<td>Key</td>
		<td>Value</td>
		<td>Actions</td>
	</tr>
	<?=$results?>
</table>

<?php
	if ($show_begin_page_link): echo '<a href="columnfamily_action.php?action=browse_data&keyspace_name='.$keyspace_name.'&columnfamily_name='.$columnfamily_name.'&pos=begin&nb_rows='.$nb_rows.'">&lt;&lt; Begin</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;'; endif;
	
	if ($show_prev_page_link): echo '<a href="columnfamily_action.php?action=browse_data&keyspace_name='.$keyspace_name.'&columnfamily_name='.$columnfamily_name.'&offset_key='.$current_offset_key.'&pos=prev&nb_rows='.$nb_rows.'">&lt; Prev Page</a>'; endif;
	
	if ($show_prev_page_link && $show_next_page_link) echo '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
	
	if ($show_next_page_link): echo '<a href="columnfamily_action.php?action=browse_data&keyspace_name='.$keyspace_name.'&columnfamily_name='.$columnfamily_name.'&offset_key='.$offset_key.'&pos=next&nb_rows='.$nb_rows.'">Next Page &gt;</a>'; endif;	
?>

<p>
	<h3>Actions</h3>
	- <a href="columnfamily_action.php?action=insert_row&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Insert a Row</a>
</p>