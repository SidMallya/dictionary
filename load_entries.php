<html>
<head>
<title>Load Entries</title>

<?php

include 'get_partitioning_id.php';

?>


</head>


<?php

if (isset($_POST['filename'])) {

   set_time_limit(0);
   $filename = $_POST['filename'];
   $file_name = "dictionary/".$filename;
   $insert_count = 0;
   
   @ $con = mysqli_connect("localhost", "root", "heyman", "test");

   if (mysqli_connect_errno()) {
      printf("Connect failed:  %s\n", mysqli_connect_error());
      exit();
   }

   $file = fopen($file_name, "r");
    
   while(!feof($file)) {
   
		$file_row = fgets($file);	  
	  
		if (!ctype_space($file_row) and ctype_alpha(substr(trim($file_row), 0, 1))) {

			//Each row in the input file is in format: word (part_of_speech) meaning.  
			//We break the row into 3 pieces using "(" and ")" as cracks for breaking.

			$file_row_array_1 = explode('(', $file_row, 2);		 
			$word = trim($file_row_array_1[0]);	  		 
			$file_row_array_2 = explode(')', $file_row_array_1[1], 2);		 
			$part_of_speech = trim($file_row_array_2[0]);
			$meaning = trim($file_row_array_2[1]);

			//The broken pieces will be saved in separate columns.  
			$insert_query = "INSERT INTO english_dictionary (partitioning_id, word, part_of_speech, meaning) VALUES(".get_partitioning_id($word).", ";
			$insert_query .= "'".mysqli_real_escape_string($con,$word)."', ";
			if($part_of_speech == '') 
				$insert_query .= "NULL, ";
			else 
				$insert_query .= "'".mysqli_real_escape_string($con,$part_of_speech)."', ";
			$insert_query .= "'".mysqli_real_escape_string($con,$meaning)."') ";

			$insert_query_result = mysqli_query($con,$insert_query);

			if ($insert_query_result)
				$insert_count++;
			else 
				echo "Insert failed: ".mysqli_error($con)." for word ".$word." <br />";

		}	  
	}
   echo "Total rows inserted = ".$insert_count."  <br />";
  
   fclose($file);
   mysqli_close($con);
   
}

else {

?>


<body style="font-family:verdana;">
<form action="load_entries.php" method="post">
<p><b>Please select dictionary file to load to table, e.g. a.txt for A, b.txt for B and so on.</b></p>
<select name="filename">
  <option value="a.txt">a.txt</option>
  <option value="b.txt">b.txt</option>
  <option value="c.txt">c.txt</option>
  <option value="d.txt">d.txt</option>
  <option value="e.txt">e.txt</option>
  <option value="f.txt">f.txt</option>
  <option value="g.txt">g.txt</option>
  <option value="h.txt">h.txt</option>
  <option value="i.txt">i.txt</option>
  <option value="j.txt">j.txt</option>
  <option value="k.txt">k.txt</option>
  <option value="l.txt">l.txt</option>
  <option value="m.txt">m.txt</option>
  <option value="n.txt">n.txt</option>
  <option value="o.txt">o.txt</option>
  <option value="p.txt">p.txt</option>
  <option value="q.txt">q.txt</option>
  <option value="r.txt">r.txt</option>
  <option value="s.txt">s.txt</option>
  <option value="t.txt">t.txt</option>
  <option value="u.txt">u.txt</option>
  <option value="v.txt">v.txt</option>
  <option value="w.txt">w.txt</option>
  <option value="x.txt">x.txt</option>
  <option value="y.txt">y.txt</option>
  <option value="z.txt">z.txt</option>
</select>
<br/>	
<input type="submit" value="Submit" />
</form>
</body>
</html>

<?php
}
?>