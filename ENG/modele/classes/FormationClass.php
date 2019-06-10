<?php

	class formation{
		private $base;

		public function __construct($bdd){
			$this->base = $bdd;
		}

		public function getAllFormations(){
			$requete = "select libelle_en from formation";

			$res = $this->base->query($requete);

			while($row = $res->fetch()){
				echo("<option value='".$row[0]."'>".$row[0]."</option>");
			}
		}

		public function addFormation($forma){
			$req = "select codeF from formation where libelle_en = '".$forma ."'";
			$res = $this->base->query($req);

			while( $row = $res->fetch()){
				$_SESSION['formation'] = $row['codeF'];
			}
			
		}
	}

?>