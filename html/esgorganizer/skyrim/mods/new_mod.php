<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");
	
	$title="ESG Organizer - Skyrim New Mod";
	include("../../includes/header.php");

	if(isset($_POST['submit'])){
		// Store the data POSTed from the add character form
	    $name=$_POST['name'];
	    $nexus=$_POST['nexus'];
	    $authorId=$_POST['authorId'];

	    $nexus=getNexusId($nexus);

	    if($_POST['autoNameCheckbox']){
	    	$data=getNexusContent($nexus,"mod");
	    	$name=getModNameFromContent($data);
	    }

	    // Create an associative array with a keys and values set to each possible category
	    $categories=postSetCheckboxes('modsNexus','categories');

	    // Change the values of the associative array created to the POST values for each key
	    foreach($categories as $key => $value) {
	    	$categories[$key]=$_POST[$value];
	    }

	    // Create the MySQL query to insert an author based on the stored POST values
	    $query ="INSERT INTO modsNexus(";
	    $query.="name,nexusId,authorId";
	    $query.=",categories";
	    $query.=") VALUES (";
	    $query.=" '$name','$nexus','$authorId'";
	    $query.=",'";
	    foreach ($categories as $key => $value) {
	    	$query.=$value=="on"?$key.",":"";
	    	#$query.=$value.",";
	    }
	    // Remove the extra comma
	    $query =rtrim($query,', ');
	    $query.="'";
	    $query.=")";

	    echo $query;

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
		<h2>New Mod</h2>
		<form action="" method="POST">
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" id="name">
			</p>
			<p>
				<?php hideTextOnCheckbox("autoName","autoNameCheckbox","name"); ?>
				<input type="checkbox" onClick="autoName()" name="autoNameCheckbox" id="autoNameCheckbox">
				<label for="autoNameCheckbox">Automatically fetch full mod name</label>
			</p>
			<p>
				<label for="nexus">Nexusmods URL:</label>
				<input type="text" name="nexus" id="nexus">
			</p>
			<p><?php echo authorDropdown(true); ?></p>
			<p><?php echo setCheckboxes('modsNexus','categories',true); ?></p>
			<p><input type="submit" name="submit" value="Add mod"></p>
		</form>
	</div>
</div>

<?php include("../../includes/footer.php"); ?>