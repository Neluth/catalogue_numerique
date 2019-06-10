<?php

class annee{
	private $base;
	
	public function __construct($bdd){
		$this->base = $bdd;
	}

	public function getAllAnnees(){
		$requete = "select libelleA from annee";

		$res = $this->base->query($requete);

		while($row = $res->fetch()){
			echo("<option value='".$row[0]."'>".$row[0]."</option>");
		}
	}

	public function addAnnee($year){
		$_SESSION['annee'] = $year;
	}
}


?>