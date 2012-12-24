<div id="cluster_selection">
	<?php if (count($cluster_details) > 1): ?>
		Select a cluster:
		<select name="cluster" id="cluster" onchange="applyClusterChange();">
			<?php foreach ($cluster_details as $index => $one_cluster): ?>
				<?php $current_cluster_name = $cluster_helper->getClusterNameForIndex($index); ?>
				<?php if (!is_null($current_cluster_name)): ?><option value="<?php echo $index?>" <?php if ($cluster_helper->getClusterIndex() == $index): echo 'selected="selected"'; endif; ?>><?php echo $current_cluster_name?></option><?php endif; ?>
			<?php endforeach; ?>
		</select>
	<?php endif; ?>
</div>

<div id="cluster_info" class="well">
	<h3>Cluster Name: <?php echo $cluster_name; ?></h3>

	Cluster Partitioner: <?php echo $partitioner; ?><br />
	Cluster Snitch: <?php echo $snitch; ?><br />
	Thrift API Version: <?php echo $thrift_api_version; ?><br />
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

<?php echo $success_message; ?>
<?php echo $error_message; ?>

<a href="keyspace_action.php?action=create" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Create New Keyspace</a>

<h3>Keyspaces and Column Families</h3>	
<ul id="keyspaces" class="well">
	<?php
		$nb_ks = count($keyspaces_name);
		for ($i = 0; $i < $nb_ks; $i++):
			$keyspace_name = $keyspaces_name[$i];
			
			echo '<li><a href="describe_keyspace.php?keyspace_name='.$keyspace_name.'">'.$keyspace_name.'</a>';
				echo '<ul>';
					$nb_cf = count($keyspaces_details[$i]['columnfamilies_name']);
					for ($j = 0; $j < $nb_cf; $j++):
						$columnfamily_name = $keyspaces_details[$i]['columnfamilies_name'][$j];
						echo '<li><a href="describe_columnfamily.php?keyspace_name='.$keyspace_name.'&amp;columnfamily_name='.$columnfamily_name.'">'.$columnfamily_name.'</a></li>';
					endfor;						
				echo '</ul>';				
			echo '</li>';
		endfor;
	?>
</ul>		

<h3>JMX</h3>

<ul>
	<li><a href="jmx.php">See Stats</a></li>
</ul>


