<?
Class cUser {
	function cUser() {
		$this->data = array();
		$this->name = 'anonymous';
		$this->hash = false;

/*		
		if(!empty($_SESSION['user'])) 
		else {
			$this->data = array();
			$this->name = 'anonymous';
			$this->hash = false;
		}
*/
	}
	function register($data) {
		
		$this->data = $data;
/*	
		if($data['ssid']) session_id($data['ssid']);
		else {
			$rn = array("ssid"=>"'".session_id()."'");
			$DB->editData('users', "`id`='".$data['id']."'", $rn);
			$data['ssid'] = $rn['ssid'];
		}
		//echo("<pre>".print_r($data,true)."</pre>");
		$this->data = $data;
		$this->name = $data['name'];
		$this->hash = $data['ssid'];
		
		$_SESSION['user'] = $data;
		return $data;
*/

	}
}
?>