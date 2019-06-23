<?php

	class formation{
		private $base;

		public function __construct($bdd){
			$this->base = $bdd;
		}

		public function getAllFormations(){
			$requete = "select codeF from formation where idF > 0";

			$res = $this->base->query($requete);

			while($row = $res->fetch()){
				echo("<option value='".$row[0]."'>".$row[0]."</option>");
			}
		}

		public function addFormation($forma){
			$_SESSION['formation'] = $forma;
		}
	}

?>