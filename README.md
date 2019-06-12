# dictionary
A simple dynamic online dictionary created using PHP, AJAX and MySQL.

Prerequisites:

1) The code used here requires below set up in MySQL:  

   User: root
   
   Password: changeme
   
   DB: test
   
   Update the search_dictionary.php and auto_load_entries.php if you wish to use a different user, password or db.
   
2) Run the below DDL in MySQL command prompt:

   ```
   use test; 
   CREATE TABLE `english_dictionary` (
   `word_id` int(11) NOT NULL AUTO_INCREMENT,
   `partitioning_id` tinyint(4) NOT NULL,
   `word` varchar(35) NOT NULL,
   `part_of_speech` varchar(100) DEFAULT NULL,
   `meaning` text NOT NULL,
   PRIMARY KEY (`word_id`,`partitioning_id`),
   KEY `word` (`word`)
   ) ENGINE=MyISAM DEFAULT CHARSET=utf8
   PARTITION BY HASH (partitioning_id)
   PARTITIONS 26;
   ```
   
3) Run auto_load_entries.php to load the dictionary table.
