<html>
<head>
<title>Create Load file</title>

<?php

include 'get_partitioning_id.php';

?>


</head>


<?php

if (isset($_POST['alphabet'])) {

   set_time_limit(0);
   $alphabet = $_POST['alphabet'];
   $read_file_name = "dictionary/".$alphabet.".txt";
   $write_file_name = "dictionary/".$alphabet."_load.txt";
   $write_count = 0;
   
   $file = fopen($read_file_name, "r");
   
   $write_file = fopen($write_file_name, "w");
    
   while(!feof($file)) {
   
      $file_row = fgets($file);	  
	  
	  if (!ctype_space($file_row) and ctype_alpha(substr(trim($file_row), 0, 1))) {
	  
	     $file_row_array_1 = explode('(', $file_row, 2);		 
  	     $word = trim($file_row_array_1[0]);	  		 
		 $file_row_array_2 = explode(')', $file_row_array_1[1], 2);		 
		 $part_of_speech = "(".$file_row_array_2[0].")";
		 if (strlen($part_of_speech) > 30)
		    echo "String length greater than 30 for word ".$word." <br />";
		 $meaning = trim($file_row_array_2[1]);
		 
		 $write_row = get_partitioning_id($word)."<".$word."<".$part_of_speech."<".$meaning."\n";
		 fwrite($write_file, $write_row);
		 $write_count++;
	  }	 
   }

  
   fclose($write_file);
   fclose($file);

   echo $write_count." row(s) written";
   
}

else {

?>


<body>
<form action="create_load_file.php" method="post">
<input name="alphabet" type="text" size="10" maxlength="1" />
<input type="submit" value="Submit" />
</form>
</body>
</html>

<?php
}
?>