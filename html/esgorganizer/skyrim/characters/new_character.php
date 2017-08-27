<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");
	
	$title="ESG Organizer - Skyrim New Character";
	include("../../includes/header.php");

	if(isset($_POST['submit'])){
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
		<p><a href=".">Back</a></p>
		<h2>New Character</h2>
		<form action="" method="POST">
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" id="name">
			</p>
			<p><?php echo enumDropdown('characters','race',true); ?></p>
			<p><?php echo enumDropdown('characters','gender',true); ?></p>
			<p><?php echo enumDropdown('characters','morality',true); ?></p>
			<?php echo setCheckboxes('characters','armorTypes',true); ?>
			<?php echo setCheckboxes('characters','combatStyle',true); ?>
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
			<p><input type="submit" name="submit" value="Add character"></p>
		</form>
	</div>
</div>

<?php include("../../includes/footer.php"); ?>