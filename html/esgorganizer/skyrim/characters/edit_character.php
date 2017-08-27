<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");
	
	$title="ESG Organizer - Skyrim New Character";
	include("../../includes/header.php");

	// Request the specific character's information
	if(!isset($_POST['submit'])){
		$charId=$_GET["id"];

		$query="SELECT * FROM characters WHERE id={$charId}";
		$result=mysqli_query($db,$query);
		if(!$result){
			die("Database query failed");
		}

		while($row=mysqli_fetch_assoc($result)){
			$name=$row["name"];
			$race=$row["race"];
			$gender=$row["gender"];
			$morality=$row["morality"];
			$roleplay=$row["roleplay"];

			$armorTypes=explode(",",$row["armorTypes"]);
			$combatStyle=explode(",",$row["combatStyle"]);
		}
	}else{	// Process the edit request if it was sent
		// Store the data POSTed from the add character form
	    $name=$_POST['name'];
	    $race=$_POST['race'];
	    $gender=$_POST['gender'];
	    $morality=$_POST['morality'];
	    $roleplay=$_POST['roleplay'];

	    $id=$_POST['id'];

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
	    $query ="UPDATE characters SET ";
	    $query.="name='$name',";
	    $query.="race='$race',";
	    $query.="gender='$gender',";
	    $query.="morality='$morality',";
	    $query.="roleplay=$roleplay,";

	    $query.="armorTypes='";
	    foreach ($armorTypes as $key => $value) {
	    	$query.=$value=="on"?$key.",":"";
	    }
	    $query.="', ";

	    $query.="combatStyle='";
	    foreach ($combatStyle as $key => $value) {
	    	$query.=$value=="on"?$key.",":"";
	    }
	    $query.="' ";

	    $query.="WHERE id={$id}";

	    // Perform the MySQL query
	    $result=mysqli_query($db,$query);
		if(!$result){
			die("Database query failed ".mysqli_error($db));
		}else{
			#echo "Database query successful<br/>";
			redirect_to(".");
		}
	}
	
?>

<div id="main">
	<div id="navigation">
		<?php echo navigation(); ?>
	</div>

	<div id="page">
		<p><a href=".">Cancel</a></p>
		<h2>Edit Character</h2>
		<form action="" method="POST">
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" id="name" value="<?php echo htmlentities($name); ?>">
			</p>
			<p><?php echo enumDropdown('characters','race',true,$race); ?></p>
			<p><?php echo enumDropdown('characters','gender',true,$gender); ?></p>
			<p><?php echo enumDropdown('characters','morality',true,$morality); ?></p>
			<?php echo setCheckboxes('characters','armorTypes',true,$armorTypes); ?>
			<?php echo setCheckboxes('characters','combatStyle',true,$combatStyle); ?>
			<label for="roleplay">Roleplay: </label>
			<table>
				<tr>
					<th style="border:0px;"><input type="range" name="roleplay" id="roleplay" min="0" max="4" oninput="roleplayUpdate(this.value)"></th>
					<th id="roleplayth" style="border:0px;">2</th>
					<script type="text/javascript">
						function roleplayUpdate(val){
							document.getElementById("roleplayth").innerHTML = val;
						}
					</script>
				</tr>
			</table>
			<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
			<p><input type="submit" name="submit" value="Update character"></p>
		</form>
	</div>
</div>

<?php include("../../includes/footer.php"); ?>