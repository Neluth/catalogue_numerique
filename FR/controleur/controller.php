<?php
	require_once('../modele/connect_bdd.php');
	$bdd = bdd::getInstance();

	// appel de la fonction associée
	switch($_POST['function']){
		case 'getInfosUE':
			include '../vue/ficheUE.php';
			break;
		case 'recherche':
			$bdd->recherche($_POST['rech']);
			break;
		case 'annee':
			$bdd->addAnnee($_POST['annee']);
			break;
		case 'categorie':
			$bdd->addCateg($_POST['codeCateg']);
			break;
		case 'semestre' :
			$bdd->addSemester($_POST['semestre']);
			break;
		case 'formation' :
			$bdd->addFormation($_POST['formation']);
			break;
		case 'langue' :
			$bdd->addLangue($_POST['langue']);
			break;
		case 'typeE' :
			$bdd->addTypE($_POST['typeE']);
			break;
		case 'deco':
			session_destroy();
			break;
	}


?>