<?php
	require_once("./includes/global.php");
	require_once("./includes/db_connection.php");
	require_once("./includes/functions.php");
	
	$title="ESG Organizer";
	include("./includes/header.php");
?>

<div id="main">
	<div id="navigation">
		<?php echo navigation(); ?>
	</div>

	<div id="page">
		<h2>Select a game</h2>
		<h3>Skyrim</h3>
		<h3>Daggerfall</h3>
	</div>
</div>

<?php include("./includes/footer.php"); ?>