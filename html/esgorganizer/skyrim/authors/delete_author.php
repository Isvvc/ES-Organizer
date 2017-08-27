<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");

	//print_r($_POST);

	$mods_set=find_mods_for_author($_POST["id"]);
	if(mysqli_num_rows($mods_set)>0){
		//$_SESSION["message"]="Can't delete a subject with pages.";
		redirect_to(".");
		//echo "Can't delete an author with mods";
	}

	$query="DELETE FROM authors WHERE id={$_POST["id"]} LIMIT 1";
	//echo $query;
	$result=mysqli_query($db,$query);

	if($result && mysqli_affected_rows($db) == 1){
		redirect_to(".");
	}else{
		echo "deletion failed";
	}
?>