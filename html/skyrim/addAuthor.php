<?php
	include 'functions.php';
?>
<!DOCTYPE html>

<html lang="en">
	<head>
		<title>Add mod author - Skyrim - ES Organizer</title>
	</head>
	<body>
		<h2>Add mod author</h2>
		<a href=".">Back</a><br/><br/>
			<form action="." method="post">
				<p>
					<label for="name">Name:</label>
		        	<input type="text" name="name" id="name" value="<?php echo htmlspecialchars($charName); ?>">
		        </p>
		        <p>
		        	<label for="nexus">Nexusmods URL:</label>
		        	<input type="text" name="nexus" id="nexus" value="<?php echo htmlspecialchars($charName); ?>">
		        </p>
		        <p>
		        	<label for="other">Other URL:</label>
		        	<input type="text" name="other" id="other" value="<?php echo htmlspecialchars($charName); ?>">
		        </p>
		        <?php
		        	setCheckboxes('authors','categories',true);
		        ?>

		        <input type="submit" name="addAuthor" value="Add author">
			</form>
	</body>
</html>