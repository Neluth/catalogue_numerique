<?php 

	class Semestre{
		private $aut;
		private $print;
		private $base;

		public function __construct($bdd){
			$this->base = $bdd;
			$this->aut = false;
			$this->print = false;
		}

		public function initListeUESemester($sem){
			// 1 = automne
			if( $sem == 1 ){
				if($this->aut == false){
					$this->setAut(true);
					$this->changeSemestre('automne');
				}
				else{
					$this->setAut(false);
					$this->changeSemestre('automne');
				}
			}
			// 2 = printemps
			else{
				if($this->print == false){
					$this->setPrint(true);
					$this->changeSemestre('printemps');
				}
				else{
					$this->setPrint(false);
					$this->changeSemestre('printemps');
				}
			}
		}

		public function changeSemestre($sem){
			if( !$_SESSION['semestre'][$sem]  ){
				$_SESSION['semestre'][$sem] = true;
			}
			else
				$_SESSION['semestre'][$sem] = false;

		}

		private function setAut($bool){
			$this->aut = $bool;
		}

		private function setPrint($bool){
			$this->print = $bool;
		}
	}

?>