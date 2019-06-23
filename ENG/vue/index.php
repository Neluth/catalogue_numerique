<?php
	// require init
	require_once '../modele/connect_bdd.php';

	$bdd = bdd::getInstance();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Catalogue des UE - UTT</title>

		<meta charset="utf-8">
		<html lang="fr">
	    <meta name="robots" content="all,follow">
	    <meta name="googlebot" content="index,follow,snippet,archive">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="keywords" content="UTT, UE UTT, catalogue, guide des UE">
	    <meta name="description" content="Catalogue numérique des UE de l'UTT">

	    <link href="https://fonts.googleapis.com/css?family=Roboto|Ubuntu" rel="stylesheet">
	    <link rel="stylesheet" type="text/css" href="css/style.css">
	    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	</head>
	<body>
		<header>
			<a href="../../fr/vue/index.php">FR</a>
			<a href="index.php" id="activeLG">EN</a>
		</header>

		<h1>Welcome on the UTT course catalog</h1>

		<div class="page">
			<div id="filtres">
				<div id="rechForm">
				   	<input id="rechInput" type="text" name="recherche" placeholder="Search...">
	  				<button id="rechSubmit"><i class="fas fa-search"></i></button>
	  				<!-- par mot-clé ? -->
				</div>

				<div id="autresFiltres">
					<div class="filtreBloc" id="annees">
						<i class="far fa-calendar-alt"></i><label for="annee">Year</label>
						<br/><select class="form-control" id="annee" name="annee">
							<?php $bdd->getAnnee(); ?>
						</select>
					</div>


					<div class="filtreBloc" id="formations">
						<i class="fas fa-graduation-cap"></i><label for="formation">Degrees & Master</label>
						<br/><select class="form-control" id="formation" name="formation">
							<option value="all">All</option>
							<?php ($bdd->getFormation()) ?>
						</select>
					</div>

					<div class="filtreBloc" id="lgform">
						<i class="fas fa-language"></i><label for="langue">Language</label>
						<br/>
						<button type="button" class="btn-form" value="fr">
							<img src="img/france.png">
						</button>
						<button type="button" class="btn-form" value="eng">
							<img src="img/eng.png">
						</button>
					</div>

					<div class="filtreBloc">
						<i class="fas fa-cloud-sun"></i><label for="semestre">Semester</label>
						<br/>
						<div id="semestre">
							<button type="button" class="btn-form" value="1">Autumn</button><br/>
							<button type="button" class="btn-form" value="2">Spring</button>
						</div>
					</div>

					<div class="filtreBloc" id="categForm">
						<i class="fas fa-shapes"></i><label for="categorie">Category</label>
						<br/>
						<div id="categorie">
							<?php $bdd->getCategorie() ?>
						</div>
					</div>

					<div class="filtreBloc" id="typeEForm">
						<i class="fas fa-school"></i><label for="enseignement">Type d'enseignement</label>
						<br/>
						<div id="enseignement">
							<?php $bdd->getTypeE() ?>
						</div>
					</div>
				
				</div>
			</div>
			<div id="container">
				<div class="bandeau">
					<?php echo ($bdd->sortList()); ?>
				</div>
			</div> 
		</div>

		<footer>
			
		</footer>


		<script type="text/javascript" src="js/js.js"></script>
		<script type="text/javascript">

			// chargement de la fiche UE d'après son codeUE
			function moreDetails(codeUE){
				// garder en mémoire la liste d'UE avec filtres
				var saveListe = $('.bandeau').html();

				$.ajax({
					url: '../controleur/controller.php',
					type: 'POST',
					data: { 'codeUE': codeUE,
							'function' : 'getInfosUE' },
					success:function(html) {
		               	$('.bandeau').html(html);

		               	//clic des triangles pour montrer
		               	$('.blocUE i').click(function(){
		               		var id = $(this).attr('id');
		               		var classe = $(this).attr('class');

		               		var split = classe.split(" ");

		               		// retirer
		               		if( split[1] == "fa-sort-down"){
						    	$('.'+id).fadeOut();
								$(this).removeClass('fa-sort-down');
								$(this).addClass('fa-sort-up');
		               		}
		               		// afficher
		               		else{
						    	$('.'+id).fadeIn();
								$(this).addClass('fa-sort-down');
								$(this).removeClass('fa-sort-up');
		               		}

		               	});


						//clic du bouton retour généré
						$('.returnbtn').click(function(){
							// retour à la liste d'UE telle qu'elle était
							$('.bandeau').html(saveListe);

							// clic du bouton + généré par liste
							$('#container .bandeau td i').click(function() {
								var code = $(this).attr('value');
								moreDetails(code);
							});
						});
		           	}
				});
			}


			// refresh page : destroy session
			$(window).bind('beforeunload',function(){
			   $.ajax({
					url: '../controleur/controller.php',
					type: 'POST',
					data: { 'function' : 'deco' },
					success:function(html) {
						//alert('ok');
		               	$('.bandeau').html(html);
		            }
				});
			});

			$(window).load(function(){
				// clic du bouton + 
				$('#container .bandeau td i').click(function() {
					var code = $(this).attr('value');
					moreDetails(code);
				});

				// rendre bouton actif ou inactif au clic
				$('#categorie button, #semestre button, #typeEForm button').click(function(){
					var id = $(this).attr('id');

					if( id == "active")
						$(this).removeAttr('id');
					else
						$(this).attr('id', 'active');

				});
				
				// rendre img active ou non au clic
				$('#lgform button').click(function(){
					var id = $(this).children().attr('id');

					if( id == "active")
						$(this).children().removeAttr('id');
					else
						$(this).children().attr('id', 'active');

				});


				// recherche
				$('#rechForm input').keypress(function(e) {
				    	// 13 = entrée
				    if(e.which == 13) {
				    	var rech = this.value;
				        rechercheFunc(rech);
				    }
				});
				$('#rechForm button').click(function(){
				    var rech = document.getElementById('rechInput').value;
					rechercheFunc(rech);
				});

				//clic d'un semestre
				$('#semestre button').click(function(){
					var sem = $(this).attr('value');

					$.ajax({
						url: '../controleur/controller.php',
						type: 'POST',
						data: { 'semestre' : sem,
								'function' : 'semestre' },
						success:function(html) {
		                	$('.bandeau').html(html);
		                	$('#container .bandeau td i').click(function() {
								var code = $(this).attr('value');
								moreDetails(code);
							});
		                }
					});
				});

				// clic d'une categorie
				$('#categForm button').click(function(){
					var code = $(this).attr('value');

					$.ajax({
						url: '../controleur/controller.php',
						type: 'POST',
						data: { 'codeCateg' : code,
								'function' : 'categorie' },
						success:function(html) {
		                	$('.bandeau').html(html);

							$('#container .bandeau td i').click(function() {
								var code = $(this).attr('value');
								moreDetails(code);
							});
		            	}

					});
				});

				//clic de la langue
				$('#lgform button').click(function(){
					var lg = $(this).attr('value');

					$.ajax({
						url: '../controleur/controller.php',
						type: 'POST',
						data: { 'langue' : lg,
								'function' : 'langue' },
						success:function(html) {
		                	$('.bandeau').html(html);

							$('#container .bandeau td i').click(function() {
								var code = $(this).attr('value');
								moreDetails(code);
							});
		            	}

					});
				});

				// clic d'un type d'enseignement
				$('#typeEForm button').click(function(){
					var code = $(this).attr('value');

					$.ajax({
						url: '../controleur/controller.php',
						type: 'POST',
						data: { 'typeE' : code,
								'function' : 'typeE' },
						success:function(html) {
		                	$('.bandeau').html(html);

							$('#container .bandeau td i').click(function() {
								var code = $(this).attr('value');
								moreDetails(code);
							});
		            	}

					});
				});

				// selection d'une annee
				$('#annees select').change(function(){
					var annee = $( "#annees option:selected" ).val();

					$.ajax({
						url: '../controleur/controller.php',
						type: 'POST',
						data: { 'annee' : annee,
								'function' : 'annee' },
						success:function(html) {
		                	$('.bandeau').html(html);

							$('#container .bandeau td i').click(function() {
								var code = $(this).attr('value');
								moreDetails(code);
							});
		            	}

					});
				});

				// selection d'une formation 
				$('#formations select').change(function(){
					var form = $( "#formations option:selected" ).val();

					$.ajax({
						url: '../controleur/controller.php',
						type: 'POST',
						data: { 'formation' : form,
								'function' : 'formation' },
						success:function(html) {
		                	$('.bandeau').html(html);

							$('#container .bandeau td i').click(function() {
								var code = $(this).attr('value');
								moreDetails(code);
							});
		            	}

					});
				});


				function rechercheFunc(rech){
					$.ajax({
						url: '../controleur/controller.php',
						type: 'POST',
						data: { 'rech' : rech,
								'function' : 'recherche' },
						success:function(html) {
		                	$('.bandeau').html(html);

							$('#container .bandeau td i').click(function() {
								var code = $(this).attr('value');
								moreDetails(code);
							});
		            	}

					});
				}

			});

		</script>
	</body>
</html>