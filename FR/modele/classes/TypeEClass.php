<?php 

class typeenseignement{
	private $base;

	public function __construct($bdd){
		$this->base = $bdd;
	}

	// selectionner tous les types d'enseignement de la base
	// et les créer en bouton
	public function getTypeE(){
		$requete = "select codeTE, libelleTE from typeenseignement";
		$res = $this->base->query($requete);

		while($row = $res->fetch()){
			echo("<button type='button' class='btn-form' value='".$row[0]."'>".$row[1]."</button><br>");
		}

	}

	// récupérer la liste d'UE d'une catégorie
	public function initListeUEType($type){
		// n'existe pas : à rajouter
		if( !array_key_exists($type, $_SESSION['typeE']) ){
			$_SESSION['typeE'][$type] = $type;
		}
		// déjà dedans : à supprimer
		else{
			unset($_SESSION['typeE'][$type]);
		}
	}
}

?>