<?php
	require_once('../modele/connect_bdd.php');

	$bdd = bdd::getInstance();
	$courseTitle = $bdd->getCourseTitle($_POST['codeUE']);
	$objectif = $bdd->getObjectif($_POST['codeUE']);
	$programme = $bdd->getProgramme($_POST['codeUE']);
	$comp = $bdd->getSkill($_POST['codeUE']);
	$infosUE = $bdd->getInformationsUE($_POST['codeUE']);
	$pedagogie = $bdd->getPedagogie($_POST['codeUE']);

	$comment = $bdd->getCommentaire($_POST['codeUE']);

	$antecedents = $bdd->getAntecedent($_POST['codeUE']);
?>
<!DOCTYPE html>
	<table id='infosUETable' style='width:100%;'>
		<tr>
		   	<th>
		   		<i class='fas fa-arrow-left returnbtn'></i>
		   		<p><?php echo($courseTitle); ?></p>
		   	</th> 
		 </tr>

		<tr style='width:100%;'>
			<td style='width: 100%;'>
				<div id='infosUEdiv'>
					<section class='blocUE'>
						<div class='titleUE'>
							<i class='fas fa-sort-down' id="informationbloc"></i>
							<h2>Informations</h2>
						</div>
						<div class='ficheUE'>
							<div class="informationbloc">
								<?php echo($infosUE); ?>
								<?php echo($antecedents); ?>
							</div>
						</div>
					</section>
					<section class='blocUE'>
						<div class='titleUE'>
							<i class='fas fa-sort-down' id="pedagogiebloc"></i>
							<h2>Timetable</h2>
						</div>
						<div class='ficheUE'>
							<div class="pedagogiebloc">
								<?php echo($pedagogie); ?>
							</div> 
						</div>
					</section>
					<section class='blocUE'>
						<div class='titleUE'>
							<i class='fas fa-sort-up' id="objectifbloc"></i>
							<h2>Objectives</h2>
						</div>
						<div class='ficheUE'>
							<div class="hide objectifbloc"><?php echo($objectif); ?> </div>
						</div>
					</section>
					<section class='blocUE'>
						<div class='titleUE'>
							<i class='fas fa-sort-up' id="programmebloc"></i>
							<h2>Syllabus</h2>
						</div>
						<div class='ficheUE'>
							<div class="hide programmebloc"><?php echo($programme); ?></div>
						</div>
					</section>
					<section class='blocUE'>
						<div class='titleUE'>
							<i class='fas fa-sort-up' id="competencebloc"></i>
							<h2>Skills</h2>
						</div>
						<div class='ficheUE'>
							<div class="hide competencebloc"><?php echo($comp); ?> </div>
						</div>
					</section>

					<section class='blocUE'>
						<div class='titleUE'>
							<i class='fas fa-sort-up' id="commentairesbloc"></i>
							<h2>Comments</h2>
						</div>
						<div class='ficheUE'>
							<div class="hide commentairesbloc"><?php echo($comment); ?> </div>
						</div>
					</section>
				</div>
			</td>
		</tr>
	</table>