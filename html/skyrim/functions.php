<?php
	#echo "Functions.php loaded<br/>";
?><?php
	//Establish connection to SQL database
	$db = mysqli_connect(localhost,esmanagerNginx,[password],esmanager);

	//Test the connection
	if(mysqli_connect_errno()){
		die("Database connection falied: ". mysqli_connect_error()." (".mysqli_connect_errno().")");
	}else{
		#echo "Database connection successful<br/>";
	}
?><?php
	// enumDropdown and setCheckboxes are modified versions of the functions from
	// https://jadendreamer.wordpress.com/2011/03/16/php-tutorial-put-mysql-enum-values-into-drop-down-select-box/
	function enumDropdown($tableName,$columnName,$label=false){
		// Import the global variable for the database 
		global $db;

		// Create the label and link it to the dropdown menu for that specific column. Add a space before capital letters and capitalize the first letter(example: turn "armorTypes" to "Armor Types")
		$selectDropdown=$label?'<label for='.$columnName.'>'.ucfirst(preg_replace('/(?<!\ )[A-Z]/', ' $0', $columnName)).': </label>':'';
		$selectDropdown .= '<select name='.$columnName.' id='.$columnName.'>';

		// Create the MySQL query to get all of the enum values from the column
		$query="SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' AND COLUMN_NAME = '$columnName'";
		$result=mysqli_query($db,$query)
		or die (mysqli_error());

		// Extract the induvidual enum values from the query
		$row = mysqli_fetch_array($result);
		$enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));

		// Create a drop down menu option for each enum value
		foreach($enumList as $value){
			$selectDropdown .= '<option value='.$value.'>'.$value.'</option>';
		}

		$selectDropdown .= "</select>";

		// Output the final drop down menu
		echo $selectDropdown;

		//Free query result
		mysqli_free_result($result);

		return $selectDropdown;
	}

	function setCheckboxes($tableName,$columnName,$label=true){
		// Import the global variable for the database
		global $db;

		// Create the label and link it to the dropdown menu for that specific column. Add a space before capital letters and capitalize the first letter(example: turn "armorTypes" to "Armor Types")
		$selectCheckboxes =$label?"<p>".ucfirst(preg_replace('/(?<!\ )[A-Z]/', ' $0', $columnName)).": </p>":"";
		$selectCheckboxes.="<ul>";

		// Create the MySQL query to get all of the set values from the column
		$query="SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' AND COLUMN_NAME = '$columnName'";
		$result=mysqli_query($db,$query)
		or die (mysqli_error());

		// Extract the induvidual set values from the query
		$row = mysqli_fetch_array($result);
		$setList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));

		// Create a checkbox for each set value with a matching label
		foreach($setList as $value){
			$selectCheckboxes.='<li>';
			$selectCheckboxes.='<input type="checkbox" name="'.$value.'" id="'.$value.'">';
			$selectCheckboxes.='<label for="'.$value.'"> '.$value.'</label>';
			$selectCheckboxes.='</li>';
		}

		$selectCheckboxes.="</ul>";

		// Output all of the checkboxes
		echo $selectCheckboxes;

		//Free query result
		mysqli_free_result($result);

		return $selectCheckboxes;
	}

	// Create an associative array with a keys and values set to each possible armor type
	function postSetCheckboxes($tableName,$columnName){
		// Import the global variable for the database
		global $db;

		// Create the MySQL query to get all of the set values from the column
		$query="SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' AND COLUMN_NAME = '$columnName'";
		$result=mysqli_query($db,$query)
		or die (mysqli_error());

		// Extract the induvidual set values from the query
		$row = mysqli_fetch_array($result);
		$setList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));

		// Create a new associative array with the set values as both the key and value
		$assoc=array_combine($setList,$setList);

		//Free query result
		mysqli_free_result($result);

		return $assoc;
	}
?>