<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");
	
	$title="ESG Organizer - Skyrim Mods";
	include("../../includes/header.php");
?>

<div id="main">
	<div id="navigation">
		<?php echo navigation(); ?>
	</div>

	<div id="page">
		<h2>Mods</h2>
			<?php
				//Query to get all of the characters
				$query="SELECT * FROM modsNexus";
				$result=mysqli_query($db,$query);
				if(!$result){
					die("Database query failed");
				}else{
					#echo "Database query successful<br/>";
				}

				$authors_query="SELECT id,nexusId,name FROM authors";
				$authors_result=mysqli_query($db,$authors_query);
				if(!$authors_result){
					die("Database query failed");
				}

				$authors_list=array();
				while($row=mysqli_fetch_assoc($authors_result)){
					$authors_list_nexus[$row["id"]]=$row["nexusId"];
					$authors_list_name[$row["id"]]=$row["name"];
				}
			?>

			<table>
				<tr>
					<th>Name</th>
					<th>Nexus ID</th>
					<th>Author</th>
					<th>Categories</th>
					<th>Images</th>
					<th></th>
				</tr>
				<?php
					while($row=mysqli_fetch_assoc($result)){
						$character ="<tr>";
						$character.="<td>";
							$character.=$row["name"];
						$character.="</td>";
						$character.="<td>";
							$character.=($row["nexusId"])?createNexusLink($row["nexusId"],1):"";
						$character.="</td>";
						$character.="<td>";
							$character.=createNexusLink($authors_list_nexus[$row["authorId"]],0,$authors_list_name[$row["authorId"]]);
						$character.="</td>";
						$character.="<td>";
							$character.=($row["categories"]?(preg_replace('(,)','$0 ',$row["categories"])):"");
						$character.="</td>";
						$character.="<td>";
							$images_query="SELECT * FROM imagesModsNexus WHERE modId={$row["id"]} AND main=1";
							$images_result=mysqli_query($db,$images_query);
							if(!$images_result){
								die("Database query failed");
							}else{
								//$character.='<img src="https://staticdelivery.nexusmods.com/mods/"'.$images_result["nexusUrlExtension"].'" />';
								#$character.="ayy";
								while($imageRow=mysqli_fetch_assoc($images_result)){
									$character.='<p><img src="https://staticdelivery.nexusmods.com/mods/'.$imageRow["nexusUrlExtension"].'" style="width:300px;"></p>';
								}
							}
						$character.="</td>";
						$character.="<td>";
						echo $character;
						// Add a button to edit character or delete character with a confirmation pop-up box.
						?>
							
							<form action= "edit_images" method="get">
								<p>
									<input type="submit" value="Images">
									<input type="hidden" name="id" value=<?php echo '"'.$row["id"].'"' ?> >
								</p>
							</form>
							
							<form action= "edit_mod" method="get">
								<input type="submit" value="Edit">
								<input type="hidden" name="id" value=<?php echo '"'.$row["id"].'"' ?> >
							</form>
							<form action="delete_mod" method="post" onsubmit="return confirm('Are you sure you want to delete this mod?');">
								<input type="submit" value="Delete">
								<input type="hidden" name="id" value=<?php echo '"'.$row["id"].'"' ?> >
							</form>
							<br/>
							
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

			<p><a href="new_mod">New Mod</a></p>
	</div>
</div>

<?php include("../../includes/footer.php"); ?>