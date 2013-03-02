<table id="navigation" class="table table-bordered table-striped">
	<tr>
		<td onclick="document.location.href='./'"><a href="./">Schema</a></td>
		<td onclick="document.location.href='?nav=cluster'"><a href="?nav=cluster">Cluster</a></td>
	</tr>
</table>

<?php echo $success_message; ?>
<?php echo $error_message; ?>

<?php
if (isset($_GET['nav']) && $_GET['nav'] == 'cluster'):
?>

<div id="cluster_info" class="well">

	<div id="cluster_selection">
		<font class="cluster_name">Cluster Name:</font>
		<select name="cluster" id="cluster" onchange="applyClusterChange();">
			<?php foreach ($cluster_details as $index => $one_cluster): ?>
				<?php $current_cluster_name = $cluster_helper->getClusterNameForIndex($index); ?>
				<?php if (!is_null($current_cluster_name)): ?><option value="<?php echo $index?>" <?php if ($cluster_helper->getClusterIndex() == $index): echo 'selected="selected"'; endif; ?>><?php echo $current_cluster_name?></option><?php endif; ?>
			<?php endforeach; ?>
		</select>
	</div>

	Cluster Partitioner: <?php echo $partitioner; ?><br />
	Cluster Snitch: <?php echo $snitch; ?><br />
	Thrift API Version: <?php echo $thrift_api_version; ?><br /><br />
	Schema Versions: <br />
	<table width="100%" class="cluster_status table table-bordered table-striped">
		<?php
			foreach ($schema_version as $version => $servers):
				foreach ($servers as $server):
						echo '<tr>';
							if ($version == 'UNREACHABLE'):
								echo '<td><span class="badge badge-important">&#215;</span></td><td>'.$server.'</td><td>'.$version.'</td>';
							else:
								echo '<td><span class="badge badge-success">&#10003;</span></td><td>'.$server.'</td><td>'.$version.'</td>';
							endif;
						echo '</tr>';
				endforeach;
			endforeach;
		?>
	</table>
</div>

<?php
else:
?>

<ul id="keyspaces" class="well"><li>
	<h3>Keyspaces and Column Families</h3>

	<a href="keyspace_action.php?action=create" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Create New Keyspace</a>

	<br /><br />

	<table width="100%" class="table table-bordered table-striped kcfs">
	<?php
		$nb_ks = count($keyspaces_name);
		for ($i = 0; $i < $nb_ks; $i++):
			$beg = $end = '';
			if (!($i&1) and $i == $nb_ks-1):
				$beg .= '</tr><tr><td colspan="2"><ul>';

				$end .= '</ul></td></tr>';
			elseif (!($i&1)):
				if ($i) $beg .= '</tr>';
				$beg .= '<tr><td><ul>';

				$end .= '</ul></td>';
			else:
				$beg .= '<td><ul>';

				$end .= '</ul></td>';
			endif;

			echo $beg;

			$keyspace_name = $keyspaces_name[$i];
			
			echo '<li><a href="describe_keyspace.php?keyspace_name='.$keyspace_name.'">'.$keyspace_name.'</a>';
				$nb_cf = count($keyspaces_details[$i]['columnfamilies_name']);
				if ($nb_cf):
					echo '<ul>';
					for ($j = 0; $j < $nb_cf; $j++):
						$columnfamily_name = $keyspaces_details[$i]['columnfamilies_name'][$j];
						echo '<li><a href="describe_columnfamily.php?keyspace_name='.$keyspace_name.'&amp;columnfamily_name='.$columnfamily_name.'">'.$columnfamily_name.'</a></li>';
					endfor;
					echo '</ul>';
				endif;
			echo '</li>';

			echo $end;
		endfor;
	?>
	</table>
</li></ul>

<?php
endif;
?>

<h3>JMX</h3>

<ul>
	<li><a href="jmx.php">See Stats</a></li>
</ul>