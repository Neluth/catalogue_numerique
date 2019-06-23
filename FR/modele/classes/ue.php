<?php

class ue{
	private $base;

	public function __construct($bdd){
		$this->base = $bdd;
	}

	public function getSkill($codeUE){
		return $this->requete($codeUE, "competences");
	}

	public function getCourseTitle($codeUE){
		return $this->requete($codeUE, "libelleUE");
	}

	public function getObjectif($codeUE){
		return $this->requete($codeUE, "objectif");
	}

	public function getProgramme($codeUE){
		return $this->requete($codeUE, "programme");
	}

	public function getCommentaire($codeUE){
		return $this->requete($codeUE, "commentaires");
	}

	public function getAntecedent($codeUE){
		$req = "select codeUE_antecedent from antecedent where codeUE = '".$codeUE ."'";
		$res = $this->base->query($req);
		$tab="";

		if( $res->rowCount()>0){
			$tab .= "<p>Antécédents : ";

			while( $row = $res->fetch()){
				$tab .= $row['codeUE_antecedent'] . " ";
			}

			$tab .= "</p>";
		}
		return $tab;
	}

	private function requete($codeUE, $param){
		$requete = "select ".$param." from UE where codeUE='". $codeUE ."' and langue_bdd = 'FR' ";
		$res = $this->base->query($requete);

		$tab ="";
		$tabFinal="";

		while($row = $res->fetch()){
			$tab .= $row[0];
		}

		// on sépare à chaque "- " 
		$split = explode("- ", $tab);

		foreach( $split as $val){
			if( strlen($val) > 0){
				$tabFinal .= "<p>";
				$tabFinal .=/* "- " .*/ $val;
				$tabFinal .= "</p>";
			}
		}

		return $tabFinal;
	}

	public function getInformationsUE($codeUE){
			$req = "select u.credits, u.automne, u.printemps, t.libelleTE from UE u, enseignement e, typeenseignement t where u.codeUE = '".$codeUE ."' and u.langue_bdd='FR' and e.codeTE = t.codeTE and e.codeUE = '".$codeUE . "'";
			$res = $this->base->query($req);
			$tab = "";

			// vides par défauts, remplis si enseignement
			$aut="";
			$print ="";

			//print_r($this->base->errorInfo());

			while($infosUE = $res->fetch()){
				// catégories
				$reqCateg = "select distinct codeC from informations_ue where codeUE ='". $codeUE . "'";
				$res2 = $this->base->query($reqCateg);
				$tab .= "<p>Catégorie(s) : ";
				while( $categ = $res2->fetch() ){
					$tab .= $categ[0] . " ";
				}
				$tab .="</p>";

				// crédits
				$tab .= "<p>Crédits : ". $infosUE[0] ." </p>";

				// semestres
				if( $infosUE[1] == "1")
					$aut = "automne";
				if( $infosUE[2] == "1")
					$print = "printemps";
				$separator="";
				// un séparateur s'il y a les deux semestres
				if( strlen($aut)>0 && strlen($print)>0 )
					$separator = ", ";
				$tab .= "<p>Semestre(s) : ". $aut. $separator. $print." </p>";

				// formations
				$reqForma = "select distinct codeF from informations_ue where codeUE ='". $codeUE . "'";
				$res2 = $this->base->query($reqForma);
				$tab .= "<p>Formation(s) : ";
				while( $categ = $res2->fetch() ){
					$tab .= $categ[0] . " ";
				}
				$tab .="</p>";

				// langue enseignement
				$tab .="<p>Type d'enseignement : ";
				$tab .=$infosUE['libelleTE'];
				$tab .='</p>';
			}

			return $tab;
		}


		public function getPedagogie($codeUE){
			$req = "select * from pedagogie where codeUE = '".$codeUE ."'";
			$res = $this->base->query($req);

			while($row = $res->fetch()){
				$cm=""; 
				$valcm="";
				$td="";
				$valtd="";
				$tp="";
				$valtp="";
				$the="";
				$valthe="";
				$prj="";
				$valprj="";

				if( $row['CM']>0 ){
					$cm = "<th>CM</th>";
					$valcm = "<td>".$row['CM']."h</td>";
				}
				if( $row['TD']>0 ){
					$td ="<th>TD</th>";
					$valtd="<td>".$row['TD']."h</td>";
				}
				if( $row['TP']>0){
					$tp = "<th>TP</th>";
					$valtp = "<td>".$row['TP']."h</td>";
				}
				if( $row['THE']>0){
					$the = "<th>THE</th>";
					$valthe = "<td>".$row['THE']."h</td>";
				}
				if( $row['PRJ']>0){
					$prj = "<th>PRJ</th>";
					$valprj = "<td>".$row['PRJ']."h</td>";
				}
				
			}
			
			$tab ="<table id='pedagogieTable' cellspacing='0' cellpadding='0'>
						<tr>".
							$cm
						    .$td
						    .$tp
						    .$the
							.$prj
						."</tr>

						<tr>"
							.$valcm
							.$valtd
						    .$valtp
						    .$valthe
							.$valprj
						."</tr>
					</table>";

			return $tab;
		}
}

?>