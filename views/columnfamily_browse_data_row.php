<tr>
	<td><?=$key?></td>
	<td><pre><?if ($is_super_cf): echo displaySCFRow($value); else: echo displayCFRow($value); endif;?></pre></td>
</tr>