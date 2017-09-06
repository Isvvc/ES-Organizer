<?php
	function navigation(){
		global $path_short;
		$output ="<ul class=\"subjects\">";
			$output.="<li>";
				$output.="<a href=\"";
				$output.=$path_short."/skyrim";
				$output.="\" >Skyrim</a>";
				$output.="<ul class=\"pages\">";
					$output.="<li>";
						$output.="<a href=\"";
						$output.=$path_short."/skyrim/characters/";
						$output.="\" >Characters</a>";
						$output.="</li>";
					$output.="<li>";
						$output.="<a href=\"";
						$output.=$path_short."/skyrim/authors/";
						$output.="\" >Mod authors</a>";
						$output.="</li>";
					$output.="<li>";
						$output.="<a href=\"";
						$output.=$path_short."/skyrim/mods/";
						$output.="\" >Mods</a>";
						$output.="</li>";
					$output.="<li>Modules</li>";
				$output.="</ul>";
			$output.="</li>";
			$output.="<li>Daggerfall</li>";
		$output.="</ul>";
		return $output;
	}

	function redirect_to($new_location){
		header("Location: ".$new_location);
		exit;
	}

	// enumDropdown and setCheckboxes are modified versions of the functions from
	// https://jadendreamer.wordpress.com/2011/03/16/php-tutorial-put-mysql-enum-values-into-drop-down-select-box/
	function enumDropdown($tableName,$columnName,$label=false,$selected=NULL){
		// Import the global variable for the database 
		global $db;

		// Create the label and link it to the dropdown menu for that specific column. Add a space before capital letters and capitalize the first letter(example: turn "armorTypes" to "Armor Types")
		$selectDropdown =$label?'<label for="'.$columnName.'">'.ucfirst(preg_replace('/(?<!\ )[A-Z]/', ' $0', $columnName)).': </label>':'';
		$selectDropdown.='<select name="'.$columnName.'" id="'.$columnName.'">';

		// Create the MySQL query to get all of the enum values from the column
		$query="SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' AND COLUMN_NAME = '$columnName'";
		$result=mysqli_query($db,$query)
			or die (mysqli_error());

		// Extract the induvidual enum values from the query
		$row = mysqli_fetch_array($result);
		$enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));

		// Create a blank drop down menu option
		$selectDropdown.='<option value=""></option>';

		// Create a drop down menu option for each enum value
		foreach($enumList as $value){
			$selectDropdown.='<option value="'.$value.'"';
			if($selected==$value){
				$selectDropdown.=' selected="selected"';
			}
			$selectDropdown.='>'.$value.'</option>';
		}

		$selectDropdown .= "</select>";

		// Output the final drop down menu
		#echo $selectDropdown;

		//Free query result
		mysqli_free_result($result);

		return $selectDropdown;
	}

	function setCheckboxes($tableName,$columnName,$label=false,$checked=NULL){
		// Import the global variable for the database
		global $db;

		// Create the label and link it to the dropdown menu for that specific column. Add a space before capital letters and capitalize the first letter(example: turn "armorTypes" to "Armor Types")
		$selectCheckboxes =$label?"<p>".ucfirst(preg_replace('/(?<!\ )[A-Z]/', ' $0', $columnName)).": </p>":"";
		$selectCheckboxes.="<ul>";

		// Create the MySQL query to get all of the set values from the column
		$query="SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' AND COLUMN_NAME = '$columnName'";
		$result=mysqli_query($db,$query)
			or die (mysqli_error());

		// Extract the induvidual set values from the query
		$row = mysqli_fetch_array($result);
		$setList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));

		// Create a checkbox for each set value with a matching label
		foreach($setList as $value){
			$selectCheckboxes.='<li>';
			//$selectCheckboxes.='<input type="checkbox" name="'.$value.'" id="'.$value.'"';
			$selectCheckboxes.='<input type="checkbox" name="'.$value.'" id="'.str_replace(' ', '_', $value).'"';
			if(in_array($value,$checked)){
				$selectCheckboxes.=' checked';
			}
			$selectCheckboxes.='>';
			$selectCheckboxes.='<label for="'.str_replace(' ', '_', $value).'"> '.$value.'</label>';
			$selectCheckboxes.='</li>';
		}

		$selectCheckboxes.="</ul>";

		// Output all of the checkboxes
		#echo $selectCheckboxes;

		//Free query result
		mysqli_free_result($result);

		return $selectCheckboxes;
	}

	// Create an associative array with a keys and values set to each possible armor type
	function postSetCheckboxes($tableName,$columnName){
		// Import the global variable for the database
		global $db;

		// Create the MySQL query to get all of the set values from the column
		$query="SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' AND COLUMN_NAME = '$columnName'";
		$result=mysqli_query($db,$query)
			or die (mysqli_error());

		// Extract the induvidual set values from the query
		$row = mysqli_fetch_array($result);
		$setList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));

		foreach ($setList as $key => $value) {
			$setList[$key]=str_replace(' ', '_', $value);
		}

		// Create a new associative array with the set values as both the key and value
		$assoc=array_combine($setList,$setList);

		//Free query result
		mysqli_free_result($result);

		return $assoc;
	}

	function getNexusId($url){
		$url=ltrim($url,"htp:/w");
    	$url=ltrim($url,".");
    	$url=ltrim($url,"nexusmod");
    	$url=ltrim($url,".");
    	$url=ltrim($url,"com/");
    	$url=ltrim($url,"pyfgcrlaoeuidhtnsqjkxbmwvz123456789");
    	$url=ltrim($url,"/usermod");
    	$i=1;
    	$nexus=substr($url,0,1);
    	while(is_numeric($nexus)&&$i<20){
    		$nexus.=substr($url,$i,1);
    		$i++;
    	}
    	$nexus=rtrim($nexus,"/");

    	return $nexus;
	}

	function getNexusImageExtension($url){
		$regex="/mods\/(.+?)\z/";
		preg_match($regex,$url,$match);
		return $match[1];
	}

	function createNexusLink($id,$type,$linkText=NULL){
		// Type 0 = user/author
		// Type 1 = mod

		$output="<a href=\"http://www.nexusmods.com/skyrim/";
		if($type==0){
			$output.="users";
		}elseif($type==1){
			$output.="mods";
		}
		$output.="/{$id}/";
		$output.="\" target=\"blank\">";
		$output.=($linkText)?($linkText):($id);
		$output.="</a>";

		return $output;
	}

	function urlLink($url){
		$output ="<a href=\"{$url}\" target=\"blank\">{$url}</a>";
		return $output;
	}

	function find_mods_for_author($author_id){
		global $db;

		$query ="SELECT * FROM modsNexus WHERE authorId = {$author_id} ";
		$result=mysqli_query($db,$query);

		if($result){
			//successful query
		}else{
			echo "$query<br/>";
			die("Database query failed.");
			echo "<br/>".$query;
		}

		return $result;
	}

	function authorDropdown($label=false,$selected=NULL,$idType="db"){
		// Import the global variable for the database
		global $db;

		// Create the MySQL query to get all of the authors and respective ids
		$query="SELECT id,name,nexusId FROM authors";
		$result=mysqli_query($db,$query)
			or die (mysqli_error());

		$selectDropdown =$label?'<label for="authorId">Author: </label>':'';
		$selectDropdown.='<select name="authorId" id="authorId">';

		if($idType=="db"){
			$found=true;
			while($row=mysqli_fetch_assoc($result)){
				$selectDropdown.='<option value='.$row['id'];
				if($selected==$row['id']){
					$selectDropdown.=' selected="selected"';
				}
				$selectDropdown.='>'.$row['name'].'</option>';
			}
		}elseif($idType=="nexus"){
			$found=false;
			while($row=mysqli_fetch_assoc($result)){
				$selectDropdown.='<option value='.$row['id'];
				if($selected==$row['nexusId']){
					$selectDropdown.=' selected="selected"';
					$found=true;
				}
				$selectDropdown.='>'.$row['name'].'</option>';
			}
		}

		$selectDropdown .= "</select>";

		//Free query result
		mysqli_free_result($result);

		if($found==true){
			return $selectDropdown;
		}else{
			return NULL;
		}
	}

	// Web scraping scrips based from
	// http://www.oooff.com/php-scripts/basic-php-scraped-data-parsing/basic-php-data-parsing.php

	function getNexusContent($nexusId,$type){
		$url="http://www.nexusmods.com/skyrim/";
		if($type=="mod"){
			$url.="mods";
		}elseif($type=="author"){
			$url.="users";
		}
		$url.="/$nexusId/";
		$data=file_get_contents($url);
		return $data;
	}

	function getModNameFromContent($data){
		$regex='/class="header-name">(.+?)</';
		preg_match($regex,$data,$match);
		return $match[1];
	}

/*	function getAuthorNameFromContent($data,$type){
		if($type=="author"){
			$regex='/user_id = (.+?);/';
		}elseif($type=="mod"){
			$id=getAuthorIdFromContent($data);
			$regex="/Uploaded by <a original-title=\"View user profile\" href=\"http:\/\/www.nexusmods.com\/skyrim\/users\/$id\">(.+?)<\/a>/";
		}
		preg_match($regex,$data,$match);
		return $match[1];
	}*/

	function getAuthorIdFromContent($data){
		$regex='/user_id = (.+?);/';
		preg_match($regex,$data,$match);
		return $match[1];
	}

/*	function getModName($nexusModId){
		$url="http://www.nexusmods.com/skyrim/mods/{$nexusModId}/?";
		$data=file_get_contents($url);
		// find where in the page it has the tag "header-name" class as that is where the mod name is locate
		$regex='/class="header-name">(.+?)</';
		preg_match($regex,$data,$match);
		return $match[1];
	}*/

	function getAuthorName($nexusModId){
		$url="http://www.nexusmods.com/skyrim/users/{$nexusModId}/?";
		$data=file_get_contents($url);
		//find where in the page it has a space follewed by a closing h1 tag as that is where the author's name is
		//$regex='/(.+?) <\/h1>/';
		$regex='/<h1>\n(.+?) /';
		preg_match($regex,$data,$match);
		return $match[1];
	}

/*	function getAuthorId($nexusModId){
		$url="http://www.nexusmods.com/skyrim/mods/{$nexusModId}/?";
		$data=file_get_contents($url);
		// find where in the page it has the tag "header-name" class as that is where the mod name is locate
		$regex='/user_id = (.+?);/';
		preg_match($regex,$data,$match);
		return $match[1];
	}*/

	// javascript credit to vimist on stackoverflow (I know nothing about javascript)
	// https://stackoverflow.com/questions/3048680/how-to-gray-out-html-form-inputs
	// WARNING: This function automatically outputs HTML and returns no value
	function hideTextOnCheckbox($function_name,$checkboxId,$textId){
		?>
		<script type="text/javascript">
		    function <?php echo $function_name ?>(){
		        if(document.getElementById("<?php echo $checkboxId ?>").checked != 1){
		            document.getElementById("<?php echo $textId ?>").removeAttribute("disabled");
		        }else{
		            document.getElementById("<?php echo $textId ?>").setAttribute("disabled","disabled");
		        }
		    }
		</script>
		<?php
	}

?>