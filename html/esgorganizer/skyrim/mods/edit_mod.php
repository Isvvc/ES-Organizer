<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");
	
	$title="ESG Organizer - Skyrim New Author";
	include("../../includes/header.php");

	if(!isset($_POST['submit'])){
		$id=$_GET['id'];

		$query="SELECT * FROM modsNexus WHERE id={$id}";
		$result=mysqli_query($db,$query);
		if(!$result){
			die("Database query failed");
		}

		while($row=mysqli_fetch_assoc($result)){
			$name=$row["name"];
			$nexus=$row["nexusId"];
			$authorId=$row["authorId"];

			$categories=explode(",",$row["categories"]);
		}

	}else{
		// Store the data POSTed from the add character form
	    $name=$_POST['name'];
	    $nexus=$_POST['nexus'];
	    $authorId=$_POST['authorId'];

	    $id=$_POST['id'];

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

	    $query ="UPDATE modsNexus SET ";
	    $query.="name='$name',";
	    $query.="nexusId='$nexus',";
	    $query.="authorId='$authorId',";

	    $query.="categories='";
	    foreach ($categories as $key => $value) {
	    	$query.=$value=="on"?$key.",":"";
	    }
	    $query.="' ";

	    $query.="WHERE id={$id}";

	    // Create the MySQL query to insert an author based on the stored POST values
	    
	    /*$query ="INSERT INTO modsNexus(";
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
	    $query.=")";*/

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
		<p><a href=".">Cancel</a></p>
		<h2>Edit Mod</h2>
		<form action="" method="POST">
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" id="name" value="<?php echo htmlentities($name); ?>">
			</p>
			<p>
				<?php hideTextOnCheckbox("autoName","autoNameCheckbox","name"); ?>
				<input type="checkbox" onClick="autoName()" name="autoNameCheckbox" id="autoNameCheckbox">
				<label for="autoNameCheckbox">Automatically fetch full mod name</label>
			</p>
			<p>
				<label for="nexus">Nexusmods URL:</label>
				<input type="text" name="nexus" id="nexus" value="<?php echo htmlentities("http://www.nexusmods.com/skyrim/mods/".$nexus."/"); ?>">
			</p>
			<p><?php echo authorDropdown(true,$authorId); ?></p>
			<?php echo setCheckboxes('modsNexus','categories',true,$categories); ?>
			<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
			<p><input type="submit" name="submit" value="Edit mod"></p>
		</form>
	</div>
</div>

<?php include("../../includes/footer.php"); ?>