<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");

	//print_r($_POST);

	$query="DELETE FROM characters WHERE id={$_POST["id"]} LIMIT 1";
	//echo $query;
	$result=mysqli_query($db,$query);

	if($result && mysqli_affected_rows($db) == 1){
		redirect_to(".");
	}else{
		echo "deletion failed";
	}
?>