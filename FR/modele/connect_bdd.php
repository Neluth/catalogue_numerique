<?php
	
	require_once("classes/AnneeClass.php");
	require_once("classes/FormationClass.php");
	require_once("classes/CategorieClass.php");
	require_once("classes/SemestreClass.php");
	require_once("classes/UEClass.php");
	require_once("classes/TypeEClass.php");
	require_once("classes/LangueClass.php");


	session_start();
	
	// singleton
	class bdd{

		private static $singleton = null;
		private $user;
		private $password;
		private $dataSource;
		private $base;
		private $annee;
		private $formation;
		private $categorie;
		private $semestre;
		private $typeE;
		private $ue;
		private $langue;

		private function __construct(){
			$this->user = "root";
			$this->password = "";
			$this->dataSource="mysql:host=localhost;dbname=catalogue_ue_v2";

			try{
				$this->base = new PDO($this->dataSource, $this->user,$this->password);
			}
			catch(PDOException $e){
				die("Erreur!" . $e->getMessage());
			}

			$this->annee = new annee($this->base);
			$this->formation = new formation($this->base);
			$this->categorie = new categorie($this->base);
			$this->semestre = new semestre($this->base);
			$this->ue = new ue($this->base);
			$this->typeE = new typeenseignement($this->base);
			$this->langue = new langue($this->base);


			if( !isset($_SESSION['formation']) )
				$_SESSION['formation'] = array();

			if( !isset($_SESSION['annee']) )
				$_SESSION['annee'] = array();

			if( !isset($_SESSION['categ']) )
				$_SESSION['categ'] = array();

			if( !isset($_SESSION['langue']) )
				$_SESSION['langue'] = array();

			if( !isset($_SESSION['typeE']) )
				$_SESSION['typeE'] = array();

			if( !isset($_SESSION['recherche']) )
				$_SESSION['recherche'] = "";

			if( !isset($_SESSION['semestre']) ){
				$_SESSION['semestre'] = array();
				$_SESSION['semestre']['automne'] = false;
				$_SESSION['semestre']['printemps'] = false;
			}


		}

		// obtenir l'objet : pas par new mais par getInstance
		// car singleton
		public static function getInstance(){
			if(is_null(self::$singleton)) {
		    	self::$singleton = new bdd();  
		    }
 
     		return self::$singleton;

		}

		// selectionner toutes les années de la base
		// et les entrer dans le select associé
		public function getAnnee(){
			$this->annee->getAllAnnees();
		}

		// selectionner toutes les formations de la base
		// et les entrer dans le select associé
		public function getFormation(){
			$this->formation->getAllFormations();
		}

		// selectionner toutes les catégories de la base
		// et les créer en bouton
		public function getCategorie(){
			$this->categorie->getAllCategories();
		}

		// selectionner tous les types d'enseignement de la base
		// et les créer en bouton
		public function getTypeE(){
			$this->typeE->getTypeE();

		}

		// récupérer le nom de l'UE selon son code
		public function getCourseTitle($codeUE){
			return($this->ue->getCourseTitle($codeUE));
		}

		// récupérer l'objectif de l'UE selon son code
		public function getObjectif($codeUE){
			return($this->ue->getObjectif($codeUE));
		}

		// récupérer le programme de l'UE selon son code
		public function getProgramme($codeUE){
			return($this->ue->getProgramme($codeUE));
		}

		// récupérer les informations de l'UE selon son code
		// pour sa fiche UE
		public function getInformationsUE($codeUE){
			return($this->ue->getInformationsUE($codeUE));
		}

		public function getAntecedent($codeUE){
			return $this->ue->getAntecedent($codeUE);
		}


		// récupérer les competences de l'UE selon son code
		public function getSkill($codeUE){
			return $this->ue->getSkill($codeUE);
		}

		public function getPedagogie($codeUE){
			return $this->ue->getPedagogie($codeUE);
		}

		// ajout/retrait categorie
		public function addCateg($codeC){
			$this->categorie->initListeUECateg($codeC);

			$this->sortList();
		}

		public function addSemester($sem){
			$this->semestre->initListeUESemester($sem);

			$this->sortList();
		}

		public function addFormation($form){
			$this->formation->addFormation($form);

			$this->sortList();
		}

		public function addAnnee($annee){
			$this->annee->addAnnee($annee);

			$this->sortList();
		}

		public function addLangue($lg){
			$this->langue->addLangue($lg);

			$this->sortList();
		}

		public function addTypE($typeE){
			$this->typeE->initListeUEType($typeE);

			$this->sortList();
		}

		public function recherche($rech){
			$_SESSION['recherche']=$rech;

			$this->sortList();
		}

		public static function headTab(){
			$tab="";
			$aut="";
			$print="";
			$formations="";
			$categories="";

			$tab .="<table style='width:100%;'>
						<tr>
					    	<th>UE</th>
						    <th>Intitulé</th> 
						    <th>Catégorie(s)</th>
						    <th>Semestre(s)</th>
						    <th>Crédits</th>
						    <th>Formation(s)</th>
						    <th></th>
						</tr>";
			return $tab;
		}
		public static function genererTab($ue, $nom, $categ, $aut, $print, $cred, $form){
			$tab="";

			$tab .= "<tr>" . "<td>".$ue."</td>"
				. "<td>".$nom."</td>"
				. "<td>".$categ."</td>"
				. "<td>".$aut." ".$print."</td>"
				. "<td>".$cred."</td>"
				. "<td>".$form."</td>"
				. "<td><i class='fas fa-plus-circle' value='".$ue."'></i></td>"
				. "<tr>";

			return $tab;
		}
		public static function endTab(){
			return "</table>";
		}

		// FONCTION DE TRI 
		// AVEC TOUS LES FILTRES
		public function sortList(){
			$tab="";
			$aut="";
			$print="";
			$formations="";
			$categories="";

			$tab .= bdd::headTab();

			$req = "select distinct ue.codeUE, ue.libelleUE, ue.credits, ue.automne, ue.printemps from UE ue, informations_ue iue, enseignement en where ue.codeUE = iue.codeUE and en.codeUE = ue.codeUE and langue_bdd ='FR'" ;

			// LES SEMESTRES
			if( $_SESSION['semestre']['automne'] && $_SESSION['semestre']['printemps'])
				$req .= "and (ue.automne = 1 OR ue.printemps = 1)";
			elseif( $_SESSION['semestre']['automne']){ // 1 = true
				$req .= "and ue.automne = 1";
			}
			elseif( $_SESSION['semestre']['printemps'])
				$req .= "and ue.printemps = 1";


			// LES CATEGORIES
			$sql = array();

			if(!empty($_SESSION['categ'])){
				foreach($_SESSION['categ'] as $val ){
					$sql[] = "codeC = '" . $val ."'";
				}

				if(sizeof($sql)>1){
					$req .= " and (".$sql[0];
				}
				elseif( !empty($sql))
					$req .= " and ".$sql[0];

				for($i=1; $i<sizeof($sql); ++$i){
					$req .= " OR " . $sql[$i];
					// dernier indice, fermer parenthèse
					if( $i == sizeof($sql)-1)
						$req .= ")";
				}
			}

			// RECHERCHE PAR MOTS CLES
			if( !empty($_SESSION['recherche']) ){
				$reqRech = "select codeUE, motscles from ue";
				$resRech = $this->base->query($reqRech);

				$tabMotsCles="";

				//UE trouvées
				$codeArray = array();

				while( $rowRech = $resRech->fetch()){
					$code = $rowRech['codeUE'];
					$mots = $rowRech['motscles'];

					$tabMotsCles['code']=$code;
					$tabMotsCles['mots']=$mots;
					
					foreach( $tabMotsCles as $val){
						// on sépare à chaque "," 
						$split = explode(",", $val);

						// array des mots clés
						$tabMotsCles['mots'] = $split;
						
						foreach( $tabMotsCles['mots'] as $mot){
							if( strtolower($_SESSION['recherche']) == strtolower(trim($mot)) ){
								array_push($codeArray, $tabMotsCles['code']);
							}
						}
					}
				}

				if( sizeof($codeArray)==1 ){
					$req .= " and ue.codeUE ='" .$codeArray[0]. "'";
				}
				elseif( sizeof($codeArray)>1  ){
					$req .= " and (";
					for( $i = 0; $i<sizeof($codeArray); $i++){
						if( $i<sizeof($codeArray)-1 ){
							$req .= " ue.codeUE = '".$codeArray[$i] . "' OR";
						}
						else
							$req .= " ue.codeUE = '".$codeArray[$i] . "'";

					}
					$req .= ")";
				}
			}

			// L'ANNEE
			if( !empty($_SESSION['annee']) ){
				$codeA = "select codeA, libelleA from annee";
				$resAnnee = $this->base->query($codeA);
				$annee ="";

				while( $row = $resAnnee->fetch()){
					if( $row['libelleA'] == $_SESSION['annee'])
						$annee = $row['codeA'];
				}
				
				$req .= " and en.codeA = '". $annee . "'";
			}


			// LA FORMATION
			if( !empty($_SESSION['formation']) && $_SESSION['formation']!="all"){
				$req .= " and iue.codeF = '".$_SESSION['formation']. "'";
			}

			// LANGUE
			$lg = array();

			foreach($_SESSION['langue'] as $val ){
				$lg[] = "langue_enseignement = '" . $val ."'";
			}

			if(sizeof($lg)>1){
				$req .= " and (".$lg[0];
			}
			elseif( !empty($lg))
				$req .= " and ".$lg[0];

			for($i=1; $i<sizeof($lg); ++$i){
				$req .= " OR " . $lg[$i];
				// dernier indice, fermer parenthèse
				if( $i == sizeof($lg)-1)
					$req .= ")";
			}

			// TYPE ENSEIGNEMENT
			$TE = array();
			foreach($_SESSION['typeE'] as $val ){
				$TE[] = "en.codeTE = '" . $val ."'";
			}

			if(sizeof($TE)>1){
				$req .= " and (". $TE[0];
			}
			elseif( !empty($TE))
				$req .= " and ".$TE[0];

			for($i=1; $i<sizeof($TE); ++$i){
				$req .= " OR " . $TE[$i];
				// dernier indice, fermer parenthèse
				if( $i == sizeof($TE)-1)
				$req .= ")";
			}

			$res = $this->base->query($req);
			//print_r($this->base->errorInfo());
			while( $infosUE = $res->fetch()){
				// selectionner la catégorie de l'UE par jointure
				$reqCateg = "select distinct codeC from informations_ue where codeUE ='". $infosUE[0] . "'";
				$res2 = $this->base->query($reqCateg);

				// selectionner la formation par jointure
				$reqForm = "select distinct codeF from informations_ue i where i.codeUE = '". $infosUE[0] . "'";
				$res3 = $this->base->query($reqForm);

				if( $infosUE[3] == "1")
					$aut = "automne";
				if( $infosUE[4] == "1")
					$print = "printemps";
				
				while($categ = $res2->fetch()){
					$categories .= $categ[0]."<br>";
				}

				while($form = $res3->fetch()){
					$formations .= $form[0]."<br>";
				}

				$tab .= bdd::genererTab($infosUE[0], $infosUE[1], $categories, $aut, $print,$infosUE[2], $formations);

				$aut="";
				$print="";
				$formations="";
				$categories="";		
			}

			$tab .= bdd::endTab();
			echo($tab);
		}
	} 
	
?>