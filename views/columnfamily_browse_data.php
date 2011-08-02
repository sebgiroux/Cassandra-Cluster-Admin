<h3><a href="index.php"><?php echo $cluster_name; ?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?php echo $keyspace_name; ?>"><?php echo $keyspace_name; ?></a> &gt; <a href="describe_columnfamily.php?keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>"><?php echo $columnfamily_name; ?></a> &gt; Browse Data</h3>

<?php if (!$is_counter_column): ?>
<div id="menu">
	<div class="menu_item" onclick="location.href='columnfamily_action.php?action=insert_row&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>'">
		<div class="icon insert_row"></div> Insert Row
	</div>
	<div class="clear_left"></div>
</div>
<?php endif; ?>

<div style="width: 500px;">
	<p class="float_left">Show <input type="text" name="show_nb_rows" id="show_nb_rows" class="tiny" onkeydown="if (event.keyCode == 13) $('#btn_change_rows').click();" value="<?php echo $nb_rows; ?>" /> rows <input type="button" value="Go" id="btn_change_rows" onclick="changeRowsPerPage('<?php echo $keyspace_name; ?>','<?php echo $columnfamily_name; ?>','<?php echo $current_offset_key; ?>');" /></p>
	<p class="float_right">Go to Key <input type="text" id="go_to_key" style="width: 120px;" /> <input type="button" value="Go" onclick="changeRowsPerPage('<?php echo $keyspace_name; ?>','<?php echo $columnfamily_name; ?>',$('#go_to_key').val());" /></p>
	<div class="clear_both"></div>
</div>

<table border="1" style="min-width: 500px;" cellpadding="5">
	<tr>
		<td>Key</td>
		<td>Value</td>
		<td>Actions</td>
	</tr>
	<?php echo $results; ?>
</table>

<?php if ($show_begin_page_link): ?><a href="columnfamily_action.php?action=browse_data&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>&amp;pos=begin&nb_rows=<?php echo $nb_rows; ?>">&lt;&lt; Begin</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?php endif; ?>
<?php if ($show_prev_page_link): ?><a href="columnfamily_action.php?action=browse_data&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>&amp;offset_key=<?php echo $current_offset_key; ?>&amp;pos=prev&nb_rows=<?php echo $nb_rows; ?>">&lt; Prev Page</a><?php endif; ?>
<?php if ($show_prev_page_link && $show_next_page_link): ?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?php endif; ?>
<?php if ($show_next_page_link): ?><a href="columnfamily_action.php?action=browse_data&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>&offset_key=<?php echo $offset_key; ?>&pos=next&nb_rows=<?php echo $nb_rows; ?>">Next Page &gt;</a><?php endif; ?>	