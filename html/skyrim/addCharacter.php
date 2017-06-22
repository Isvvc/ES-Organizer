<?php
	include 'functions.php';
?>
<!DOCTYPE html>

<html lang="en">
	<head>
		<title>Add Character - Skyrim - ES Organizer</title>
	</head>
	<body>
		<h2>Add character</h2>
			<a href=".">Back</a><br/><br/>
			<form action="." method="post">
		        <div id="data">
		            <p>
		            	<label for="name">Name:</label>
		            	<input type="text" name="name" id="name" value="<?php echo htmlspecialchars($charName); ?>">
		            </p>
			    	<?php
			    		echo "<p>";
			    		enumDropdown('characters','race',true);
			    		echo "</p>";
			    		echo "<p>";
			    		enumDropdown('characters','gender',true);
			    		echo "</p>";
			    		echo "<p>";
			    		enumDropdown('characters','morality',true);
			    		echo "</p>";
			    		setCheckboxes('characters','armorTypes',true);
			    		setCheckboxes('characters','combatStyle',true);
			    	?>

			    	<label for="roleplay">Roleplay: </label>
			    	<table>
			    		<tr>
				    		<th><input type="range" name="roleplay" id="roleplay" min="0" max="4" oninput="roleplayUpdate(this.value)"></th>
					    	<th id="roleplayth">2</th>
					    	<script type="text/javascript">
					    		function roleplayUpdate(val){
					    			document.getElementById("roleplayth").innerHTML = val;
					    		}
					    	</script>
				    	</tr>
			    	</table>
		        </div>
		        <br/>
		        <div id="buttons">
		            <label>&nbsp;</label>
		            <input type="submit" name="addCharacter" value="Add character"><br>
		        </div>
		        <br/>
			    
		    </form>
	</body>
