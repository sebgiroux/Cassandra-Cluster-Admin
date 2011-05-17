Cassandra Cluster Admin
by Sébastien Giroux
------------------------------------------------
Cassandra Cluster Admin is a GUI tool to help people administrate their Apache Cassandra cluster.

If you're like me and used MySQL for a while (and still using it!), you get used to phpMyAdmin and its simple and easy to use user interface. I thought it would be nice to have a similar tool for Cassandra and I couldn't find any, so I build my own!

With Cassandra Cluster Admin, you can create/edit/drop keyspace and column family, truncate a column family, create secondary indexes, display a row, browse data (get range slice), insert a row and much more!

Bug report and/or pull request are always welcome!

Configuration
------------------------------------------------
To configure Cassandra Cluster Admin, you must edit include/conf.inc.php with your cassandra server IP, and your Cassandra username/password if needed.

Disclaimer
------------------------------------------------
This software is still in beta so always be careful when using it on a production cluster. I won't take any responsability if for some reason this tool drop all your keyspaces and wipe all your data, althought I really doubt it will happen =)

Credits
------------------------------------------------
The Apache Cassandra project
Tyler Hobbs (thobbs) - phpcassa