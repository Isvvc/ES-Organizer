<?php
	include 'functions.php';
?><?php
	//If the add character form was filled out
	if(isset($_POST['addCharacter'])){
		// Store the data POSTed from the add character form
	    $name=$_POST['name'];
	    $race=$_POST['race'];
	    $gender=$_POST['gender'];
	    $morality=$_POST['morality'];
	    $roleplay=$_POST['roleplay'];

	    // Create an associative array with a keys and values set to each possible armor type
	    $armorTypes=postSetCheckboxes('characters','armorTypes');

	    // Change the values of the associative array created to the POST values for each key
	    foreach($armorTypes as $key => $value) {
	    	$armorTypes[$key]=$_POST[$value];
	    }

	    // Do the same thing as Armor Types for Combat Style
	    $combatStyle=postSetCheckboxes('characters','combatStyle');

	    #print_r($combatStyle);

	    foreach($combatStyle as $key => $value) {
	    	$combatStyle[$key]=$_POST[$value];
	    }

	    #print_r($combatStyle);

	    // Create the MySQL query to insert a character based on the stored POST values
	    $query ="INSERT INTO characters(";
	    $query.="name,race,gender,morality,roleplay";
	    $query.=",armorTypes";
	    $query.=",combatStyle";
	    $query.=") VALUES (";
	    $query.=" '$name','$race','$gender','$morality','$roleplay'";
	    $query.=",'";
	    // Add a the armor type and a comma if that armor type was selected. Leave blank if not
	    foreach ($armorTypes as $key => $value) {
	    	$query.=$value=="on"?$key.",":"";
	    	#$query.=$value.",";
	    }
	    $query.="','";
	    foreach ($combatStyle as $key => $value) {
	    	$query.=$value=="on"?$key.",":"";
	    }
	    // Remove the extra comma
	    $query =rtrim($query,', ');
	    $query.="'";
	    $query.=")";

	    #echo $query;

	    // Perform the MySQL query
	    $result=mysqli_query($db,$query);
		if(!$result){
			die("Database query failed ".mysqli_error($db));
		}else{
			//echo "Database query successful<br/>";
		}
	}else
	// If a character was chosen to be deleted
	if(isset($_POST['delCharacter'])){
		// Get the POST values to see the table id for the character to be deleted
		$id=$_POST['id'];

		// Create the query to delete the character with the matching id
		$query ="DELETE FROM characters ";
		$query.="WHERE id = '$id' ";
		// Set the delet limit to 1 as a safeguard to prevent accidental mass deletion in case somethin' goes screwy
		$query.="LIMIT 1";

		// Perform the query
		$result=mysqli_query($db,$query);
		if(!$result){
			die("Database query failed ".mysqli_error($db));
		}else{
			#echo "Database query successful<br/>";
		}
	}else

	if(isset($_POST['addAuthor'])){
		// Store the POST data from the form
	    $name=$_POST['name'];
	    $url=$_POST['nexus'];
	    $other=$_POST['other'];

	    #$url="http://www.nexusmods.com/fallout4/users/16578339/?tb=mods&pUp=1";
    	#echo $url."<br/><br/>";
    	#$url=ltrim($url,"pyfgcrlaoeuidhtnsqjkxbmwvz:/.");
    	$url=ltrim($url,"htp:/w");
    	$url=ltrim($url,".");
    	$url=ltrim($url,"nexusmod");
    	$url=ltrim($url,".");
    	$url=ltrim($url,"com/");
    	$url=ltrim($url,"pyfgcrlaoeuidhtnsqjkxbmwvz123456789");
    	$url=ltrim($url,"/user");
    	$url=rtrim($url,"pyfgcrlaoeuidhtnsqjkxbmwvz123456789");
    	#echo $url."<br/>";
    	#echo (is_numeric("16578339"))?"true":"false";
    	#echo "<br/>";
    	$i=1;
    	$nexus=substr($url,0,1);
    	#echo $nexus."<br/>";
    	while(is_numeric($nexus)&&$i<20){
    		$nexus.=substr($url,$i,1);
    		#echo $nexus."<br/>";
    		$i++;
    	}
    	$nexus=rtrim($nexus,"/");
    	#echo $nexus;

	    // Create an associative array with a keys and values set to each possible category
	    $categories=postSetCheckboxes('authors','categories');

	    // Change the values of the associative array created to the POST values for each key
	    foreach($categories as $key => $value) {
	    	$categories[$key]=$_POST[str_replace(" ","_",$value)];
	    }

	    // Create the MySQL query to insert an author based on the stored POST values
	    $query ="INSERT INTO authors(";
	    $query.="name,nexusId,link";
	    $query.=",categories";
	    $query.=") VALUES (";
	    $query.=" '$name','$nexus','$other'";
	    $query.=",'";
	    // Add a the category and a comma if that category was selected. Leave blank if not
	    foreach ($categories as $key => $value) {
	    	$query.=$value=="on"?$key.",":"";
	    }
	    // Remove the extra comma
	    $query =rtrim($query,', ');
	    $query.="'";
	    $query.=")";

	    // Perform the MySQL query
	    $result=mysqli_query($db,$query);
		if(!$result){
			die("Database query failed ".mysqli_error($db));
		}else{
			echo "Database query successful<br/>";
		}
	}else

	if(isset($_POST['delAuthor'])){
		// Get the POST values to see the table id for the character to be deleted
		$id=$_POST['id'];

		// Create the query to delete the character with the matching id
		$query ="DELETE FROM authors ";
		$query.="WHERE id = '$id' ";
		// Set the delet limit to 1 as a safeguard to prevent accidental mass deletion in case somethin' goes screwy
		$query.="LIMIT 1";

		// Perform the query
		$result=mysqli_query($db,$query);
		if(!$result){
			die("Database query failed ".mysqli_error($db));
		}else{
			#echo "Database query successful<br/>";
		}
	}
?>
<!DOCTYPE html>

<html lang="en">
	<head>
		<title>Skyrim - ES Organizer</title>
	</head>
	<body>
		<h1>ES Organizer - Skyrim</h1>
		<a href="..">Back</a>
		<h2>Characters</h2>
			<?php
				//Query to get all of the characters
				$query="SELECT * FROM characters";
				$result=mysqli_query($db,$query);
				if(!$result){
					die("Database query failed");
				}else{
					#echo "Database query successful<br/>";
				}
			?>

			<ul>
				<?php while($row=mysqli_fetch_assoc($result)){ ?>
				<!-- This is a temporary solution to just spit out the character's values. -->
				<li>
					<?php
						$character =($row["name"]?($row["name"].", "):"");
						$character.=($row["race"]?($row["race"].", "):"");
						$character.=($row["gender"]?($row["gender"].", "):"");
						$character.=($row["morality"]?($row["morality"].", "):"");
						$character.=($row["roleplay"]?($row["roleplay"].", "):"");
						$character.=($row["armorTypes"]?(preg_replace('(,)','$0 ',$row["armorTypes"]).", "):"");
						$character.=($row["combatStyle"]?(preg_replace('(,)','$0 ',$row["combatStyle"])):"");
						$character =rtrim($character,', ');
						$character.="<br/>";
						echo $character;
					?>
				</li>
				<!-- Add a button to delete a character with a confirmation pop-up box. -->
				<form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this character?');">
					<input type="submit" name="delCharacter" value="Delete Character">
					<!-- Create a hidden input with the id for the character so that when the delete request is sent, the character id is also sent so the server knows which character to delete -->
					<input type="hidden" name="id" value=<?php echo '"'.$row["id"].'"' ?> > <!-- This is the closing angle bracket for the input next to the closing angle bracket for the php. Not a typo -->
				</form>
				<?php } ?>
			</ul>

			<?php
				//Free query result
				mysqli_free_result($result);
			?>

			<a href="addCharacter">Add character</a>
		<h2>Mods</h2>

		<h2>Mod Authors</h2>
			<?php
				//Query to get all of the mod authors
				$query="SELECT * FROM authors";
				$result=mysqli_query($db,$query);
				if(!$result){
					die("Database query failed");
				}else{
					#echo "Database query successful<br/>";
				}
			?>

			<ul>
				<?php while($row=mysqli_fetch_assoc($result)){ ?>
				<!-- This is a temporary solution to just spit out the author's values. -->
				<li>
					<?php
						$author =($row["name"]?($row["name"].", "):"");
						$author.=($row["nexusId"]?($row["nexusId"].", "):"");
						$author.=($row["link"]?($row["link"].", "):"");
						$author.=($row["categories"]?(preg_replace('(,)','$0 ',$row["categories"]).", "):"");
						$author =rtrim($author,', ');
						$author.="<br/>";
						echo $author;
					?>
				</li>
				<!-- Add a button to delete a author with a confirmation pop-up box. -->
				<form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this author?');">
					<input type="submit" name="delAuthor" value="Delete Author">
					<!-- Create a hidden input with the id for the author so that when the delete request is sent, the author id is also sent so the server knows which author to delete -->
					<input type="hidden" name="id" value=<?php echo '"'.$row["id"].'"' ?> > <!-- This is the closing angle bracket for the input next to the closing angle bracket for the php. Not a typo -->
				</form>
				<?php } ?>
			</ul>

			<?php
				//Free query result
				mysqli_free_result($result);
			?>

			<a href="addAuthor">Add mod author</a>
	</body>
</html>