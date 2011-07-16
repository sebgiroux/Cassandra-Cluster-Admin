<table border="1" cellpadding="5">
	<?php if (!is_null($scf_key)): ?><tr><td colspan="2"><?=$scf_key?></td></tr><?php endif; ?>
			
	<?php foreach ($row as $column => $value): ?>
		<tr><td><?=$column?></td><td><pre><?=$value?></pre></td></tr>
	<?php endforeach; ?>
</table>