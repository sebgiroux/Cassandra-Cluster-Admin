<table border="1" cellpadding="5">
	<?php if (!is_null($scf_key)): ?>
		<tr>
			<td colspan="2">
				<div class="float_left"><?php echo $scf_key; ?></div>
				<div class="float_right" style="margin-left: 20px;">
					<div class="delete_row_icon"></div>
					<div class="float_left">
						<a href="#" onclick="deleteSuperColumn('<?php echo $keyspace_name; ?>','<?php echo $columnfamily_name; ?>','<?php echo $row_key; ?>','<?php echo $scf_key;?>')">Delete</a>
					</div>
					<div class="clear_both"></div>
				</div>
			</td>
		</tr>
	<?php endif; ?>
			
	<?php foreach ($row as $column => $value): ?>
		<tr>
			<td><?php echo $column; ?></td>
			<td><pre><?php echo $value; ?></pre></td>
		</tr>
	<?php endforeach; ?>
</table>