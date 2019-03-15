 <?php
 
	include 'get_partitioning_id.php';

	//Prevent wildcard processing from user input
	$wildcard_characters = array("%", "_");
	$input_text = str_replace($wildcard_characters, mt_rand(), trim($_GET['input_text']));
	
	$mode = $_GET['mode'];

	@ $con = mysqli_connect("localhost", "root", "changeme", "test");

	if (mysqli_connect_errno()) {
		printf("Connect failed:  %s\n", mysqli_connect_error());
		exit();
	}

	switch ($mode) {
	
		case "normal":
		
			$normal_query = "SELECT * FROM english_dictionary WHERE word like '".mysqli_real_escape_string($con,$input_text)."%' AND ";
			$normal_query .= "PARTITIONING_ID = ".get_partitioning_id(mysqli_real_escape_string($con,$input_text))." ";
			$normal_query .= "LIMIT 10";
			$normal_query_result = mysqli_query($con,$normal_query);

			echo mysqli_error($con);

			if (!mysqli_num_rows($normal_query_result)) {
				echo "<p>No matches found</p>";
			}
			else {
				echo '<table border="0">
				<tr>
				<th>Word</th>
				<th>Meaning</th>
				</tr>';
				while($row = mysqli_fetch_array($normal_query_result)) {
					echo "<tr>";
					echo '<td width="100">' . $row['word'] . ' (' . $row['part_of_speech'] . ') ' . '</td>';
					echo "<td>" . $row['meaning'] . "</td>";
					echo "</tr>";
				}
				echo "</table>";   
			}
			break;
			
		case "reverse":
		
			$reverse_query = "SELECT * FROM english_dictionary WHERE ";
			
			//Append wildcard % at the beginning and at the end of each input word and write the LIKE conditions to an array.
			$input_text_word = explode(" ", $input_text);
			$array_row = 0;
			foreach ($input_text_word as $input_text_word_value) {
				if ($input_text_word_value != "") {
					$reverse_query_cond_array[$array_row] = " meaning LIKE '% ".mysqli_real_escape_string($con, $input_text_word_value)."%' ";
					$array_row++;
				}
			}
			
			//Concatenate each element of the array with the AND operator if applicable.
 			if ($input_text == "")  
 				$reverse_query_cond = "FALSE";
 			else
				$reverse_query_cond = implode(" AND ", $reverse_query_cond_array);
				
			$reverse_query .= $reverse_query_cond;
			$reverse_query .= " LIMIT 10";
						
			$reverse_query_result = mysqli_query($con,$reverse_query);

			if (!mysqli_num_rows($reverse_query_result)) {
				echo "<p>No matches found</p>";
			}
			else {
				echo '<table border="0">
				<tr>
				<th>Word</th>
				<th>Meaning</th>
				</tr>';
				while($row = mysqli_fetch_array($reverse_query_result)) {
					echo "<tr>";
					echo '<td width="100">' . $row['word'] . ' (' . $row['part_of_speech'] . ') ' . '</td>';
					echo "<td>" . $row['meaning'] . "</td>";
					echo "</tr>";
				}
				echo "</table>";   
			}
			break;
			
		default:
			echo "Unknown mode selected. Please let me know how you did it.";	  
	}

	mysqli_close($con);


?>