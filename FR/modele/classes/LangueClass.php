<?php

class langue{
	private $base;

	public function __construct($bdd){
		$this->base = $bdd;
	}

	public function addLangue($langue){
		// n'existe pas : à rajouter
		if( !array_key_exists($langue, $_SESSION['langue']) ){
			$_SESSION['langue'][$langue] = $langue;
		}
		// déjà dedans : à supprimer
		else{
			unset($_SESSION['langue'][$langue]);
		}
	}
}

?>