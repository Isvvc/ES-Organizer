<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");

	#print_r($_POST);

	$modId=$_POST["id"];

	if($_POST["submit"]=="Add image"){
		$nexus=getNexusImageExtension($_POST["url"]);
		$main=($_POST["imageMain"]=="on")?'true':'false';

		#echo $main;

		$query ="INSERT INTO imagesModsNexus(";
		$query.="nexusUrlExtension,modId,main";
		$query.=") VALUES (";
		$query.="'$nexus','$modId',$main)";

		$result=mysqli_query($db,$query);

		if(!$result){
			die("Database query failed ".mysqli_error($db));
		}else{
			redirect_to("./edit_images?id=$modId");
		}
	}else if($_POST["submit"]=="Delete image"){
		$nexus=$_POST["nexus"];
		$query="DELETE FROM imagesModsNexus WHERE nexusUrlExtension='$nexus' LIMIT 1";

		$result=mysqli_query($db,$query);

		if($result && mysqli_affected_rows($db) == 1){
			redirect_to("./edit_images?id=$modId");
		}else{
			echo "deletion failed";
		}
	}
?>