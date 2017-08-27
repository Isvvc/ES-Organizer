<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");
	
	$title="ESG Organizer - Skyrim New Author";
	include("../../includes/header.php");

	if(!isset($_POST['submit'])){
		$authorId=$_GET['id'];

		$query="SELECT * FROM authors WHERE id={$authorId}";
		$result=mysqli_query($db,$query);
		if(!$result){
			die("Database query failed");
		}

		while($row=mysqli_fetch_assoc($result)){
			$name=$row["name"];
			$nexus=$row["nexusId"];
			$other=$row["link"];

			$categories=explode(",",$row["categories"]);
			$content=explode(",",$row["content"]);
		}

	}else{
		// Store the data POSTed from the add character form
	    $name=$_POST['name'];
	    $nexus=$_POST['nexus'];
	    $other=$_POST['other'];

	    $id=$_POST['id'];

	    $nexus=getNexusId($nexus);

	    if($_POST['autoNameCheckbox']){
	    	$name=getAuthorName($nexus);
	    }

	    // Create an associative array with a keys and values set to each possible category
	    $categories=postSetCheckboxes('authors','categories');

	    // Change the values of the associative array created to the POST values for each key
	    foreach($categories as $key => $value) {
	    	$categories[$key]=$_POST[$value];
	    }

	    // Do the same thing as Armor Types for Combat Style
	    $content=postSetCheckboxes('authors','content');

	    foreach($content as $key => $value) {
	    	$content[$key]=$_POST[$value];
	    }

	    $query ="UPDATE authors SET ";
	    $query.="name='$name',";
	    $query.="nexusId='$nexus',";
	    $query.="link='$other',";

	    $query.="categories='";
	    foreach ($categories as $key => $value) {
	    	$query.=$value=="on"?$key.",":"";
	    }
	    $query.="', ";

	    $query.="content='";
	    foreach ($content as $key => $value) {
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
		<h2>Edit Mod Author</h2>
		<form action="" method="POST">
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" id="name" value="<?php echo htmlentities($name); ?>">
			</p>
			<p>
				<?php hideTextOnCheckbox("autoName","autoNameCheckbox","name"); ?>
				<input type="checkbox" onClick="autoName()" name="autoNameCheckbox" id="autoNameCheckbox">
				<label for="autoNameCheckbox">Automatically fetch author name from the Nexus</label>
			</p>
			<p>
				<label for="nexus">Nexusmods URL:</label>
				<input type="text" name="nexus" id="nexus" value="<?php echo htmlentities("http://www.nexusmods.com/skyrim/users/".$nexus."/"); ?>">
			</p>
			<p>
				<label for="other">Other URL:</label>
				<input type="text" name="other" id="other" value="<?php echo htmlentities($other); ?>">
			</p>
			<?php echo setCheckboxes('authors','categories',true,$categories); ?>
			<?php echo setCheckboxes('authors','content',true,$content); ?>
			<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
			<p><input type="submit" name="submit" value="Edit author"></p>
		</form>
	</div>
</div>

<?php include("../../includes/footer.php"); ?>