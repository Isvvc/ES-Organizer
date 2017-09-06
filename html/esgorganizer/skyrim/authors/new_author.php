<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");
	
	$title="ESG Organizer - Skyrim New Author";
	include("../../includes/header.php");

	if(isset($_POST['submit'])){
		#print_r($_POST);

		// Store the data POSTed from the add character form
	    $name=$_POST['name'];
	    $nexus=$_POST['nexus'];
	    $other=$_POST['other'];

	    $nexus=getNexusId($nexus);

	    if($_POST['autoNameCheckbox']){
	    	$name=getAuthorName($nexus);
	    }

	    // Create an associative array with a keys and values set to each possible category
	    $categories=postSetCheckboxes('authors','categories');

	    // Change the values of the associative array created to the POST values for each key
	    foreach($categories as $key => $value){
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
	    	$query.=($value=="on")?(str_replace('_', ' ', $key).","):"";
	    	#$query.=$value.",";
	    }
	    $query.="','";
	    foreach ($content as $key => $value) {
	    	$query.=($value=="on")?(str_replace('_', ' ', $key).","):"";
	    }
	    // Remove the extra comma
	    $query =rtrim($query,', ');
	    $query.="'";
	    $query.=")"; 

	    #echo $query;

	    //print_r($categories);



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
		<h2>New Mod Author</h2>
		<form method="POST">
			<p>
				<label for="name">Name:</label>
				<input type="text" name="name" id="name">
			</p>
			<p>
				<?php hideTextOnCheckbox("autoName","autoNameCheckbox","name"); ?>
				<input type="checkbox" onClick="autoName()" name="autoNameCheckbox" id="autoNameCheckbox">
				<label for="autoNameCheckbox">Automatically fetch author name from the Nexus</label>
			</p>
			<p>
				<label for="nexus">Nexusmods URL:</label>
				<input type="text" name="nexus" id="nexus">
			</p>
			<p>
				<label for="other">Other URL:</label>
				<input type="text" name="other" id="other">
			</p>
			<?php echo setCheckboxes('authors','categories',true); ?>
			<?php echo setCheckboxes('authors','content',true); ?>
			<p><input type="submit" name="submit" value="Add author"></p>
		</form>
	</div>
</div>

<?php include("../../includes/footer.php"); ?>