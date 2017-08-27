<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");
	
	$title="ESG Organizer - Skyrim Characters";
	include("../../includes/header.php");
?>

<div id="main">
	<div id="navigation">
		<?php echo navigation(); ?>
	</div>

	<div id="page">
		<h2>Characters</h2>
			<?php
				//Query to get all of the characters
				$query="SELECT * FROM characters";
				$result=mysqli_query($db,$query);
				if(!$result){
					die("Database query failed");
				}else{
					#echo "Database query successful<br/>";
				}
			?>

			<table>
				<tr>
					<th>Name</th>
					<th>Race</th>
					<th>Gender</th>
					<th>Morality</th>
					<th>Roleplay Level</th>
					<th>Armor Types</th>
					<th>Combat Style</th>
					<th></th>
				</tr>
				<?php
					while($row=mysqli_fetch_assoc($result)){
						$character ="<tr>";
						$character.="<td>";
							$character.=$row["name"];
						$character.="</td>";
						$character.="<td>";
							$character.=$row["race"];
						$character.="</td>";
						$character.="<td>";
							$character.=$row["gender"];
						$character.="</td>";
						$character.="<td>";
							$character.=$row["morality"];
						$character.="</td>";
						$character.="<td>";
							$character.=($row["roleplay"]?($row["roleplay"]):"");
						$character.="</td>";
						$character.="<td>";
							$character.=($row["armorTypes"]?(preg_replace('(,)','$0 ',$row["armorTypes"])):"");
						$character.="</td>";
						$character.="<td>";
							$character.=($row["combatStyle"]?(preg_replace('(,)','$0 ',$row["combatStyle"])):"");
						$character.="</td>";
						$character.="<td>";
						echo $character;
						// Add a button to edit character or delete character with a confirmation pop-up box.
						?>
							<form action= "edit_character" method="get">
								<input type="submit" value="Edit">
								<input type="hidden" name="id" value=<?php echo '"'.$row["id"].'"' ?> >
							</form>
							<form action="delete_character" method="post" onsubmit="return confirm('Are you sure you want to delete this character?');">
								<input type="submit" value="Delete">
								<input type="hidden" name="id" value=<?php echo '"'.$row["id"].'"' ?> >
							</form>
							
						<?php
						$character ="</td>";
						$character.="</tr>";
						echo $character;
					}
				?>
			</table>

			<?php
				//Free query result
				mysqli_free_result($result);
			?>

			<p><a href="new_character">New Character</a></p>
	</div>
</div>

<?php include("../../includes/footer.php"); ?>