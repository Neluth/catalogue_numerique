<?php

	class categorie{
		private $base;
		// en session
		//$_SESSION['categ'] = array();

		public function __construct($bdd){
			$this->base = $bdd;
		}

		public function getAllCategories(){
			$requete = "select codeC from categorie";

			$res = $this->base->query($requete);

			while($row = $res->fetch()){
				echo("<button type='button' class='btn-form' value='".$row[0]."'>".$row[0]."</button><br>");
			}
		}

		// récupérer la liste d'UE d'une catégorie
		public function initListeUECateg($codeC){
			// n'existe pas : à rajouter
			if( !array_key_exists($codeC, $_SESSION['categ']) ){
				$_SESSION['categ'][$codeC] = $codeC;
			}
			// déjà dedans : à supprimer
			else{
				unset($_SESSION['categ'][$codeC]);
			}
		}
	}

?>