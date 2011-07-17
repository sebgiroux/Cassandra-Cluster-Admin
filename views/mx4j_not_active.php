<h3><a href="index.php"><?=$cluster_name?></a> &gt; JMX Stats</h3>

<p>It looks like MX4J is not actived on the node (<?=$jmx_host?>) you're trying to see JMX stats.</p>

<p>To install MX4J:</p>

<ol>
	<li>Download mx4j-tools.jar from <a href="http://mx4j.sourceforge.net/" target="blank">http://mx4j.sourceforge.net/</a></li>
	<li>Add mx4j-tools.jar to the classpath (e.g. under lib/)</li>
	<li>Start cassandra</li>
	<li>In the log you should see a message such as HttpAtapter started on port 8081</li>
	<li>To choose a different port (8081 is the default) or a different listen address (0.0.0.0 is not the default) edit conf/cassandra-env.sh and uncomment #MX4J_ADDRESS="-Dmx4jaddress=0.0.0.0" and #MX4J_PORT="-Dmx4jport=8081"</li>
	<li>If you decided to edit the port, you will also need to tell Cassandra Cluster Admin which port to look for. You can change the port in include/conf.inc.php by altering MX4J_HTTP_ADAPTOR_PORT.</li>
</ol>