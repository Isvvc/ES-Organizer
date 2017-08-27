<?php
	require_once("../../includes/global.php");
	require_once("../../includes/db_connection.php");
	require_once("../../includes/functions.php");
	
	$title="ESG Organizer - Skyrim Mod Images";
	include("../../includes/header.php");

	$modId=$_GET['id'];
 ?>

 <div id="main">
 	<div id="navigation">
		<?php echo navigation(); ?>
	</div>

	<div id="page">
		<p><a href=".">Back</a></p>
		<h2>Images</h2>
		<p>
			<?php
				$query="SELECT nexusId FROM modsNexus WHERE id=$modId";
				$result=mysqli_query($db,$query);
				if(!$result){
					die("Database query failed");
				}else{
					#echo "Database query successful<br/>";
				}

				$row=mysqli_fetch_assoc($result);

				echo createNexusLink($row["nexusId"],1,"Link to mod page");

				mysqli_free_result($result);
			?>
		</p>
		<form action="modify_images" method="POST">
			<label for="url">Nexusmods URL:</label>
			<input type="text" name="url" id="url">
			<label for="imageMain">Main?:</label>
			<input type="checkbox" name="imageMain" id="imageMain">
			<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
			<input type="submit" name="submit" value="Add image">
		</form>
		<br/>
		<?php
			//Query to get all of the characters
			$query="SELECT * FROM imagesModsNexus WHERE modId=$modId";
			$result=mysqli_query($db,$query);
			if(!$result){
				die("Database query failed");
			}else{
				#echo "Database query successful<br/>";
			}
		?>
		<table>
			<tr>
				<th>Image</th>
				<th>Direct Link</th>
				<th></th>
			</tr>
			<?php
				while($row=mysqli_fetch_assoc($result)){
					$url='https://staticdelivery.nexusmods.com/mods/'.$row["nexusUrlExtension"];
					$output ="<tr>";
						$output.="<td>";
							$output.="<img src=\"$url\" style=\"width:300px;\">";
						$output.="</td>";
						$output.="<td>";
							$output.="<a href=\"$url\" target=\"_blank\">$url</a>";
						$output.="</td>";
						$output.="<td>";
					echo $output;
			?>
						<form action="modify_images" method="post" onsubmit="return confirm('Are you sure you want to delete this image?');">
							<input type="submit" name="submit" value="Delete image">
							<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
							<input type="hidden" name="nexus" value="<?php echo $row["nexusUrlExtension"] ?>" >
						</form>
			<?php
					$output ="</td>";
					$output.="</tr>";

					echo $output;
				}
				
			?>
		</table>
		<?php
			//Free query result
			mysqli_free_result($result);
		?>

	</div>
 </div>

 <?php include("../../includes/footer.php"); ?>