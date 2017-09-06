<?php
	require_once("../includes/global.php");
	require_once("../includes/db_connection.php");
	require_once("../includes/functions.php");

	$title="ESG Organizer Import";
	include("../includes/header.php");

	if($_POST['submit']=="Add mod"){
		// Store the data POSTed from the add character form
	    $name=$_POST['name'];
	    $nexus=$_POST['nexus'];
	    $authorId=$_POST['authorId'];

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
	    	$query.=$value=="on"?(str_replace('_', ' ', $key).","):"";
	    	#$query.=$value.",";
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
			echo "<script>window.close();</script>";
		}
	}elseif($_POST['submit']=="Add author"){
		// Store the data POSTed from the add character form
	    $name=$_POST['name'];
	    $nexus=$_POST['nexus'];
	    $other=$_POST['other'];

	    echo $nexus;

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

	    // Create the MySQL query to insert an author based on the stored POST values
	    $query ="INSERT INTO authors(";
	    $query.="name,nexusId,link";
	    $query.=",categories,content";
	    $query.=") VALUES (";
	    $query.=" '$name','$nexus','$other'";
	    $query.=",'";
	    foreach ($categories as $key => $value) {
	    	$query.=$value=="on"?(str_replace('_', ' ', $key).","):"";
	    	#$query.=$value.",";
	    }
	    $query.="','";
	    foreach ($content as $key => $value) {
	    	$query.=$value=="on"?(str_replace('_', ' ', $key).","):"";
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
			redirect_to("");
		}
	}

	$url=$_GET["url"];
	$nexus=getNexusId($url);
	$data=getNexusContent($nexus,"mod");
	$name=getModNameFromContent($data);
	$authorId=getAuthorIdFromContent($data);

	$dropdown=authorDropdown(true,$authorId,"nexus");
?>

<?php
	if($dropdown){
?>
<div id="main">
	<div id="page">
		<p><a href="">Cancel</a></p>
		<h2>Import mod</h2>
		<form method="POST">
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" id="name" value="<?php echo htmlentities($name); ?>" disabled="disabled">
			</p>
			<p>
				<?php hideTextOnCheckbox("autoName","autoNameCheckbox","name"); ?>
				<input type="checkbox" onClick="autoName()" name="autoNameCheckbox" id="autoNameCheckbox" checked="checked">
				<label for="autoNameCheckbox">Automatically fetch full mod name</label>
			</p>
			<p>
				<label for="nexus">Nexusmods ID:</label>
				<input type="text" name="nexus" id="nexus" value="<?php echo htmlentities($nexus); ?>">
			</p>
			<p><?php echo $dropdown; ?></p>
			<?php echo setCheckboxes('modsNexus','categories',true,$categories); ?>
			<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
			<p><input type="submit" name="submit" value="Add mod"></p>
		</form>
	</div>
</div>

<?php
	}else{
		$name=getAuthorName($authorId);
?>

<div id="main">
	<div id="page">
		<p><a href=".">Cancel</a></p>
		<p>Must add mod author first.</p>
		<h2>Import Mod Author</h2>
		<form method="POST">
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" id="name" value="<?php echo htmlentities($name); ?>" disabled="disabled">
			</p>
			<p>
				<?php hideTextOnCheckbox("autoName","autoNameCheckbox","name"); ?>
				<input type="checkbox" onClick="autoName()" name="autoNameCheckbox" id="autoNameCheckbox" checked="checked">
				<label for="autoNameCheckbox">Automatically fetch author name from the Nexus</label>
			</p>
			<p>
				<label for="nexus">Nexusmods ID:</label>
				<input type="text" name="nexus" id="nexus" value="<?php echo htmlentities($authorId); ?>">
			</p>
			<p>
				<label for="other">Other URL:</label>
				<input type="text" name="other" id="other" value="<?php echo htmlentities($other); ?>">
			</p>
			<?php echo setCheckboxes('authors','categories',true,$categories); ?>
			<?php echo setCheckboxes('authors','content',true,$content); ?>
			<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
			<p><input type="submit" name="submit" value="Add author"></p>
		</form>
	</div>
</div>
<?php
	}
?>

<?php include("../includes/footer.php"); ?>