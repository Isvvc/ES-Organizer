aaayy
<br/>
<pre>
<?php
	$data = file_get_contents('http://www.nexusmods.com/skyrim/mods/84115/?');
	$regex = '/class="header-name">(.+?)</';
	preg_match($regex,$data,$match);
	var_dump($match);
	echo $match[1];

    $data = file_get_contents('http://www.nexusmods.com/skyrim/users/187943/?');
    $regex = '/(.+?) <\/h1>/';
    preg_match($regex,$data,$match);
    var_dump($match);
    echo $match[1];    
?>
</pre>

<script type="text/javascript">
    function disable_enable(){
        if(document.getElementById("checkbox").checked != 1){
            document.getElementById("input1").removeAttribute("disabled");
        }else{
            document.getElementById("input1").setAttribute("disabled","disabled");
        }
    }
</script>

<input type="text" id="input1" />
<br/><br/>
<label>Mailing address same as residental address</label>
<input id="checkbox" onClick="disable_enable()" type="checkbox" style="width:15px"/>