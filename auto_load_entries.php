<html>
<head>
<title>Load Entries</title>

<?php

	//Contains the function to get the partitioning_id of a word.
	include 'get_partitioning_id.php';

?>


</head>

<body>

<?php
	
	@ $con = mysqli_connect("localhost", "root", "changeme", "test");
	
	if (mysqli_connect_errno()) {
		printf("Connect failed:  %s\n", mysqli_connect_error());
		exit();
	}

	//Read each alphabet file and load to table.
	for($a = 97; $a <= 122; $a++) {

		set_time_limit(0);
		$filename = chr($a).".txt";
		$file_loc = "dictionary/".$filename;
		$insert_count = 0;

		$file = fopen($file_loc, "r");
		
		while(!feof($file)) {
	   
			$file_row = fgets($file);	  
		  
			
			if (!ctype_space($file_row) and ctype_alpha(substr(trim($file_row), 0, 1))) {

				//Each row in the input file is in format: word (part_of_speech) meaning.  
				//We break the row into 3 pieces using "(" and ")" as cracks for breaking.

				$file_row_array_1 = explode('(', $file_row, 2);		 
				$word = htmlentities(trim($file_row_array_1[0]));	  		 
				$file_row_array_2 = explode(')', $file_row_array_1[1], 2);		 
				$part_of_speech = htmlentities(trim($file_row_array_2[0]));
				$meaning = htmlentities(trim($file_row_array_2[1]));

				//The broken pieces will be saved in separate columns.  
				$insert_query = "INSERT INTO english_dictionary (partitioning_id, word, part_of_speech, meaning) VALUES ";
				$insert_query .= "(".get_partitioning_id($word).", ";
				$insert_query .= "'".mysqli_real_escape_string($con,$word)."', ";
				if($part_of_speech == '') 
					$insert_query .= "NULL, ";
				else 
					$insert_query .= "'".mysqli_real_escape_string($con,$part_of_speech)."', ";
				$insert_query .= "'".mysqli_real_escape_string($con,$meaning)."');";
				
				$insert_query_result = mysqli_query($con,$insert_query);

				if ($insert_query_result)
					$insert_count++;
				else 
					echo "Insert failed: ".mysqli_error($con)." for word ".$word." <br />";
				
			}	  
		}
		fclose($file);
		
		echo "Total rows inserted from file ".chr($a).".txt = ".$insert_count."  <br />";
   
	}
	mysqli_close($con);

?>

</body>

</html>


