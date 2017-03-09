<?php
Class cDuel {
	protected $BD;
	function cDuel($duel_id) {
		global $DB;
        $this->BD =& $DB;

		$data = array();

		$req = "`id`='".$duel_id."'";
		$res = $this->BD->getData('duels',$req);
		$duel = $res ? $res[0] : NULL;
		
		$req = "`id`='".$duel['player1']."'";
		$res = $this->BD->getData('users',$req);
		$player1 = $res ? $res[0] : NULL;
		$pets1 = $this->getPlayerPets($duel['player1_pets']);

		$req = "`id`='".$duel['player2']."'";
		$res = $this->BD->getData('users',$req);
		$player2 = $res ? $res[0] : NULL;
		$pets2 = $this->getPlayerPets($duel['player2_pets']);


        $this->data = array(
        	'player1'=>$player1,
        	'player2'=>$player2,
        	'pets1'=>$pets1,
        	'pets2'=>$pets2,
        	'duel'=>$duel);
	}
	function getPlayerPets($str) {
		$pts = explode(';',$str);
		$spl = array();

		$pets = array();
		$spells = array();

		while (list($key, $val) = each($pts)) {
			$s = explode('.',$val);
			array_push($spl,$s[1],$s[2],$s[3]);
			$pets[$s[0]] = array(
				'spell1' => $s[1],
				'spell2' => $s[2],
				'spell3' => $s[3],
				'id' => $s[0]
			);
		}
		
		$spl = array_unique($spl);
		$req = "`id`='".implode("' OR `id`='",$spl)."'";
		$res = $this->BD->getData('spell',$req);
		while(list($key,$v)=each($res)) $spells[$v['id']] = $v;

		while(list($key,$pet)=each($pets)) {
			$pets[$key]['spell1'] = $spells[$pet['spell1']];
			$pets[$key]['spell2'] = $spells[$pet['spell2']];
			$pets[$key]['spell3'] = $spells[$pet['spell3']];
		}

		return $pets;
	}
}
?>