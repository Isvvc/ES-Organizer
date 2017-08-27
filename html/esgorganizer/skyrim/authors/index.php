<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");
	
	$title="ESG Organizer - Skyrim Mod Authors";
	include("../../includes/header.php");
?>

<div id="main">
	<div id="navigation">
		<?php echo navigation(); ?>
	</div>

	<div id="page">
		<h2>Mod Authors</h2>
			<?php
				//Query to get all of the characters
				$query="SELECT * FROM authors";
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
					<th>Nexus ID</th>
					<th>Link</th>
					<th>Armor Types</th>
					<th>Content</th>
					<th></th>
				</tr>
				<?php
					while($row=mysqli_fetch_assoc($result)){
						$character ="<tr>";
						$character.="<td>";
							$character.=$row["name"];
						$character.="</td>";
						$character.="<td>";
							$character.=($row["nexusId"])?createNexusLink($row["nexusId"],0):"";
						$character.="</td>";
						$character.="<td>";
							$character.=($row["link"])?urlLink($row["link"]):"";
						$character.="</td>";
						$character.="<td>";
							$character.=($row["categories"]?(preg_replace('(,)','$0 ',$row["categories"])):"");
						$character.="</td>";
						$character.="<td>";
							$character.=($row["content"]?(preg_replace('(,)','$0 ',$row["content"])):"");
						$character.="</td>";
						$character.="<td>";
						echo $character;
						// Add a button to edit character or delete character with a confirmation pop-up box.
						?>
							<form action= "edit_author" method="get">
								<input type="submit" value="Edit">
								<input type="hidden" name="id" value=<?php echo '"'.$row["id"].'"' ?> >
							</form>
							<form action="delete_author" method="post" onsubmit="return confirm('Are you sure you want to delete this author?');">
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

			<p><a href="new_author">New Author</a></p>
	</div>
</div>

<?php include("../../includes/footer.php"); ?>