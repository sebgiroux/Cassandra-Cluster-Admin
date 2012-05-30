<ul class="breadcrumb">
	<li>
		<a href="index.php"><?php echo $cluster_name; ?></a> <span class="divider">/</span>
	</li>
	<li class="active">
		JMX Stats
	</li>
</ul>

<p>It looks like MX4J is not actived on the node (<?php echo $jmx_host; ?>) you're trying to see JMX stats, or node is not alive.</p>

<div style="margin-bottom: 20px;">
        Select another node:
        <select id="node" onchange="changeMX4JNode();">
                <?php
                        foreach ($all_nodes as $one_node):
                                list($host,$port) = explode(':',$one_node);
                                if ($host == $jmx_host)
                                   echo '<option value="'.$host.'" selected>'.$host.'</option>';
                               else
                                   echo '<option value="'.$host.'">'.$host.'</option>';
                        endforeach;
                ?>
        </select>
</div>

<p>To install MX4J:</p>

<ol>
	<li>Download mx4j-tools.jar from <a href="http://mx4j.sourceforge.net/" target="blank">http://mx4j.sourceforge.net/</a></li>
	<li>Add mx4j-tools.jar to the classpath (e.g. under lib/)</li>
	<li>Start cassandra</li>
	<li>In the log you should see a message such as HttpAtapter started on port 8081</li>
	<li>To choose a different port (8081 is the default) or a different listen address (0.0.0.0 is not the default) edit conf/cassandra-env.sh and uncomment #MX4J_ADDRESS="-Dmx4jaddress=0.0.0.0" and #MX4J_PORT="-Dmx4jport=8081"</li>
	<li>If you decided to edit the port, you will also need to tell Cassandra Cluster Admin which port to look for. You can change the port in include/conf.inc.php by altering MX4J_HTTP_ADAPTOR_PORT.</li>
</ol>