<?php 

function genPHPFromArray ($arr) {
	global $indent; 
	// var_dump($arr); 
	$indent ++; 
	$tab = str_repeat ("\t", $indent); 
    $a = "array(\n\t".$tab; 
    $nArr = array(); 
	foreach ($arr as $key => $value) {
		$kt = gettype($key); 
		if ($kt == "string") {
			$sKey = "'$key'"; 
		} else {
			$sKey = "$key";
		}
		$vt = gettype ( $value ); 
		// echo "<br>Key type $kt Key $key sKey $sKey Value $value ValueType $vt <br>";  
		switch ($vt) {
			case 'boolean':
				$nArr[] =  $sKey . " => " . ($value?"true":"false"); break; 
			case 'integer':
				$nArr[] =  $sKey . " => " . $value;break; 
			case 'double':
				$nArr[] =  $sKey . " => " . $value;break; 
			case 'string':
				$nArr[] =  $sKey . " => " . "'$value'";break; 
			case 'array':
				$nArr[] =  $sKey . " => " . genPHPFromArray($value);break; 
			case 'object':
			case 'resource':
			case 'NULL':
			case 'unknown type':
				$nArr[] =  $sKey . " => "."'unknown'"; 
				break; 
		}		
	}
	// var_dump($nArr); 
	$a .= implode(",\n\t$tab ", $nArr); 
	$a .= ")\n$tab"; 
	$indent --; 
	return $a; 
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$genOutput = "No Result Yet"; 
	$serialized = "Type code here..."; 
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$indent = 0; 
	$serialized = str_replace('\"', '"', $_POST['serinput']); 
	
	$arr = unserialize($serialized); 
	$genOutput = "serialize(\n\t". genPHPFromArray($arr).")"; 
} else {
	
}




?>
<html>

<head>

<link rel="stylesheet" href="devtool.css" type="text/css" />
</head>
<body>
<h1>Devtool: Unserialize and reverse-engineer</h1>
<p>Cut and paste a serialized object, and get the PHP code to create it</p>
<h3>Input serialized object here</h3>
<form method="post" action="">
<textarea name="serinput" id="serinput" cols="100" rows="10"><?php echo $serialized; ?></textarea>
<br/>
<input id="SendButton" type="submit" name="send" value="send" />
<br/>
</form>
<h3>Result PHP code</h3>
<textarea name="seroutput" id="seroutput"cols="100" rows="10"><?php echo $genOutput; ?></textarea>
</body>

</html>
<?php 




?>