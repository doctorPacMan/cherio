<?
Class DB {
	var $host = '127.0.0.1';
	var $name = 'test';
	var $user = 'root';
	var $pass = '';
	var $link;
	function DB($name, $user=null, $pass=null){
    	// $dblink = mysql_connect ($DB["server"], $DB["login"], $DB["password"]) or die("Could not connect : " . mysql_error());
    	
    	$this->name = $name;
    	$this->user = $user;
    	$this->pass = $pass;
    	$this->link = mysql_connect($this->host,$this->user,$this->pass) or die("Could not connect : " . mysql_error());
		mysql_select_db($this->name) or die("Could not select database");
    	// $this->dblink = $this->Connect();

	}
	function Connect() {
	    global $DB;
    	// $dblink = mysql_connect ($DB["server"], $DB["login"], $DB["password"]) or die("Could not connect : " . mysql_error());
    	$dblink = mysql_connect() or die("Could not connect : " . mysql_error());
	    mysql_select_db($DB["name"]) or die("Could not select database");
    	return $dblink;
	}
	// ============================================================================= Get
	function getDataAll($table) {
		$query = "SELECT * FROM ".$table;
		$req = mysql_query($query) or die("Query failed : " . mysql_error());
		$res = array();
	    while ($line = mysql_fetch_array($req, MYSQL_ASSOC)) {
			// echo "<pre>".print_r($line,true)."</pre><hr>";
    	    array_push($res, $line);
		}
    	mysql_free_result($req);
		mysql_close($this->link);
		echo "<h4>RES:</h4><pre>".print_r($res,true)."</pre>";
	}
	function getData($table, $usl=false, $idz=false){
		return 'null';
/*
	    if ($usl) $query = "SELECT * FROM ".$table." WHERE ".$usl."";
    	else $query = "SELECT * FROM ".$table;
    
	    $result = mysql_query($query) or die("Query failed : " . mysql_error());
 
	    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    	    foreach ($line as $k => $col_value) $assa[$k]=$col_value;
        	if ($idz) $temp[$assa['id']]=$assa;
        	else $temp[]=$assa;
		}
    	mysql_free_result($result);

	
		if (isset($temp)) return $temp;
		else return false;
*/
	}
	// ============================================================================= Add
	function addData($table,$data){
	    $Fstring="`".key($data)."`"; 
    	$Cstring=$data[key($data)];

    	foreach ($data as $field => $dcont) {
    		if($Cstring!=$dcont){
	    		$Fstring.=",`".$field."`";
	    		$Cstring.=",".$dcont;
    		}
	    }
	    $query="INSERT INTO ".$table."(".$Fstring.") values(".$Cstring.")";
    	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	    @mysql_free_result($result);
	}
	// ============================================================================= Edit
	function editData($table,$usl,$data){
	    $Fstring="`".key($data)."` = ".$data[key($data)]; 

	    foreach ($data as $field => $dcont) if($field!=key($data)) $Fstring.=",`".$field."` = ".$dcont;

		$query="UPDATE `".$table."` SET ".$Fstring." WHERE ".$usl." LIMIT 1";
	    $result = mysql_query($query) or die("Query failed : " . mysql_error());

	    @mysql_free_result($result);
	}
	// ============================================================================= Delete
	function deleteData($table,$usl){
		$query="DELETE FROM `".$table."` WHERE ".$usl;
    	$result = mysql_query($query) or die("Query failed : " . mysql_error());

	    @mysql_free_result($result);
	}
	// ============================================================================= Get
	function checkExist($table, $field, $wha){
	    $query = "SELECT `".$field."` FROM ".$table." WHERE `".$field."`='".$wha."'";
    
	    $result = mysql_query($query) or die("Query failed : " . mysql_error());
 
	    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    	    foreach ($line as $k => $col_value) $assa[$k]=$col_value;
		}
    	mysql_free_result($result);

		if (!empty($assa)) 
			return true;
		else 
			return false;
	}

	function killConnect(){
    	mysql_close($this->dblink);
	}

	function makeQuery($query, $par=false){
	    $result = mysql_query($query) or die("Query failed : " . mysql_error());
	    
	    if ($par) 
	    {
		    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
		    {
    		    foreach ($line as $k => $col_value) $assa[$k]=$col_value;
    		    if(!empty($assa)) $assa2[] = $assa;
			}
	    	mysql_free_result($result);

	    	if(!empty($assa2)) return $assa2;
		}
	    else 
	    	return true;
	}

}
/*
$Database->addData("table", array (
		"name"			=> "'Asshole'",
		"birthday" 		=> "'1953-01-01'",
		"info" 			=> "'—цукан'",
		"mail" 			=> "'mail'",
		"creation_date"	=> "NOW()"
		));

*/
?>
