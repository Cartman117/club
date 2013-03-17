<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Club - Inscription tournois</title>
	<link href="./mod_club.css" rel="stylesheet" type="text/css" />
    <script src="./mod_club_form.js"></script>
</head>
<body>
<?php
	require("./mod_club_class_message.php");
	require("./mod_club_functions.php");
	require("./mod_club_class_db.php");
	require("./mod_club_class_form.php");
	require("./mod_club_class_member.php");
	
	define('_JEXEC', 1);
	define('JPATH_BASE', '../../../..');
	define('DS', DIRECTORY_SEPARATOR);
	
	require_once (JPATH_BASE .DS.'includes'.DS.'defines.php');
	require_once (JPATH_BASE .DS.'includes'.DS.'framework.php');
	
	$mainframe = JFactory::getApplication('site');
	$mainframe->initialise();
	
	$user = JFactory::getUser();
	jimport('joomla.user.helper');
	if ($user->guest != 1)
	{
		$member = TRUE;
		$m = new Member($user->id);
		$nomForm = $m->getNom();
		$prenomForm = $m->getPrenom();
		$phoneForm = $m->getNumTel();
		$numStreetForm = $m->getNumRue();
		$nameStreetForm = $m->getNomRue();
		$cityForm = $m->getVille();
		$postalCodeForm = $m->getCodePostal();
	}
	else
	{
		$nomForm = "";
		$prenomForm = "";
		$mailForm = "";
		$phoneForm = "";
		$numStreetForm = "";
		$nameStreetForm = "";
		$cityForm = "";
		$postalCodeForm = "";
	}
	
	$connexionDatabase = Database::getInstance();
	$addTournament = FALSE;
	if(isset($_POST['registerTournament']))
	{
		if(!empty($_POST['tournoi']))
		{
			$mail = $_POST['mail'];
			if(filter_var($mail, FILTER_VALIDATE_EMAIL))
			{
				if(verifyName($_POST['prenom']) && verifyName($_POST['nom']))
				{
					$jourNaissance = $_POST['jourNaissance'];
					$moisNaissance = $_POST['moisNaissance'];
					$anneeNaissance = $_POST['anneeNaissance'];
					if(preg_match("/^[0-9]{1,2}/", $jourNaissance) && preg_match("/[0-9]{4}/", $anneeNaissance) && $jourNaissance <= 28)
					{
						$dateNaissance = new DateTime($anneeNaissance."-".$moisNaissance."-".$jourNaissance);
						if(verifyPhone($_POST['numero']))
						{
							if(verifyAdress($_POST['numRue'], $_POST['nomRue'], $_POST['codePostal'], $_POST['ville']))
							{
								if($_POST['licencie'] == 1 && verifyLicence($_POST['numLicence']) && verifyText($_POST['club']))
								{
									$valueArray =  array($_POST['nom'], $_POST['prenom'],
												$dateNaissance->format("Y-m-d"), $_POST['numero'], 
												$_POST['ville'], $_POST['codePostal'],
												$_POST['nomRue'], $_POST['numRue'], $_POST['mail'], 1,
												$_POST['club'], $_POST['numLicence'], $_POST['sexe']);
												
									$valueColumn = array("nom", "prenom", "date_naiss", "num_tel_resp",
												"ville", "code_postal", "nom_rue", "num_rue", 
												"mail", "licencie","club","num_licence", "sexe");								
								}
								else
								{	
									if($_POST['licencie'] == 0)
									{
										$valueArray =  array($_POST['nom'], $_POST['prenom'],
												$dateNaissance->format("Y-m-d"), $_POST['numero'], 
												$_POST['ville'], $_POST['codePostal'],
												$_POST['nomRue'], $_POST['numRue'], $_POST['mail'], 0, $_POST['sexe']);
												
										$valueColumn = array("nom", "prenom", "date_naiss", "num_tel_resp",
												"ville", "code_postal", "nom_rue", "num_rue", 
												"mail", "licencie", "sexe");
									}
									else
										$valueArray = NULL;
								}
								if($valueArray != NULL)
								{
									try
									{
										$success = $connexionDatabase->insertDb("club_inscrit_pour_tournoi", $valueColumn, $valueArray);
										$lastId = $connexionDatabase->lastCreatedId();
										if(!$success)
											throw new Exception();
										else
										{
											Message::showSuccessMessage("Votre inscription au tournoi s'est effectué correctement.");
											foreach($_POST['tournoi'] as &$value)
											{
												if(is_numeric($value))
												{
													$success = $connexionDatabase->insertDb("club_inscrit_tournoi", NULL,
																			 array($lastId,$value));
													if(!$success)
														throw new Exception();
												}
											}
										}
									}
									catch(Exception $e)
									{
										Message::showErrorMessage($e);
									}
								}
								else
									Message::showErrorMessage("Une erreur s'est produite lors de votre inscription au tournoi. Veuillez réessayer.");
							}
							else
								Message::showErrorMessage("Les données d'adresse que vous avez entré sont incorrects.");
						}
						else
							Message::showErrorMessage("Le numéro de téléphone que vous avez entré est incorrect.");
					}
					else
						Message::showErrorMessage("Votre âge est incorrect.");
				}
				else
					Message::showErrorMessage("Votre prénom ou nom sont incorrects.");
			}
			else
				Message::showErrorMessage("Votre mail est incorrect.");			
		}
		else
			Message::showErrorMessage("Vous n'avez pas sélectionné de tournois.");
	}
	if(isset($_POST['setMailForTournament']))
	{
		$mail = $_POST['mail'];
		if(filter_var($mail, FILTER_VALIDATE_EMAIL))
		{
			$resultQuery = $connexionDatabase->selectDb(array("club_inscrit_pour_tournoi"), array("id_inscrit_pour_tournoi"), "mail = '".$mail."'");
			$nbResults = mysqli_fetch_assoc($resultQuery);
			$id_inscrit = $nbResults['id_inscrit_pour_tournoi'];
			if($id_inscrit != NULL && is_numeric($id_inscrit))
				$addTournament = TRUE;
			else
				Message::showErrorMessage("Vous ne vous êtes jamais inscrit à un tournoi avec cette adresse mail.");
		}
		else
			Message::showErrorMessage("Votre mail est incorrect.");
	}
	
	if(isset($_POST['addTournament']) && is_numeric($_POST['id_inscrit']))
	{
		try
		{
			foreach($_POST['tournoi'] as &$value)
			{
				if(is_numeric($value))
				{
					$success = $connexionDatabase->insertDb("club_inscrit_tournoi", NULL,
											 array($_POST['id_inscrit'],$value));
					if(!$success)
						throw new Exception();
				}
			}
			Message::showSuccessMessage("Votre inscription au(x) tournoi(s) s'est effectué correctement.");
		}
		catch(Exception $e)
		{
			Message::showErrorMessage("Une erreur s'est produite lors de votre inscription aux tournois.<br/>Il est possible que vous ayez déjà utilisé l'adresse mail indiquée. Si c'est le cas nous vous invitons à  choisir l'autre option d'inscription aux tournois.");
		}
	}
	if($addTournament)
	{
		echo'<div><br/><h3>Inscription tournoi</h3>';
		
		$request = $connexionDatabase->selectDb(array("club_tournoi"), 
							array("*", "DATE_FORMAT(date,'%d/%m/%Y') AS date"),
								 "NOT EXISTS(SELECT * FROM club_inscrit_tournoi 
								 	WHERE club_inscrit_tournoi.id_tournoi = club_tournoi.id_tournoi 
								 	AND id_inscrit_pour_tournoi = ".$id_inscrit.")");
		$nb = mysqli_num_rows($request);
		if($nb > 0)
		{
			echo'<br/>Liste des tournois : ';
			Form::openForm(NULL, "POST");
			while($results = mysqli_fetch_assoc($request))
			{			
				Form::openInput("tournoi[]", "checkbox", NULL, $results['id_tournoi']);
				Form::closeInput();	
				echo " ".$results['nom']."(".$results['date'].")";							
			}
			Form::openInput("id_inscrit", "hidden", NULL, $id_inscrit);
			Form::closeInput(TRUE);
			Form::closeForm("addTournament", "Ajouter les tournois");
		}
		else
			Message::showInformationMessage("Aucun tournoi n'est disponible pour le moment.");
		echo'</div>';
	}
	else	
	{
		Message::showInformationMessage("Si vous ne vous êtes jamais inscrit à un tournoi, veuillez sélectionner la première option d'inscription. <br/>Si vous vous êtes déjà inscrit, veuillez sélectionner la deuxième.");
		echo'Option 1 ';
		Form::addRadioButton("type", "nouveauTournoi", "afficheFormulaireNouveauTournoi()");
		echo' Option 2 ';
		Form::addRadioButton("type", "ajoutTournoi", "afficheFormulaireAjoutTournoi()");
		echo'<div id="ajoutTournoi"><br/>';
			Form::openForm(NULL, "POST", "formMail");
			Form::openInput("mail", "text", "Mail utilisé pour les anciennes inscriptions :",NULL,50);
			Form::closeInput(TRUE, TRUE);
			Form::closeForm("setMailForTournament", "Ajouter les tournois");			
		echo'</div>';
				
		Form::openForm(NULL, "post");
		echo'<div id="nouveauTournoi"><br/><h3>Inscription tournoi</h3>';
		
		$request = $connexionDatabase->selectDb(array("club_tournoi"), array("id_tournoi","nom", "DATE_FORMAT(date,'%d/%m/%Y') AS date"));
		$nb = mysqli_num_rows($request);
		if($nb > 0)
		{	
			echo'<br/>Liste des tournois : ';
			while($results = mysqli_fetch_assoc($request))
			{
				Form::openInput("tournoi[]", "checkbox", NULL, $results['id_tournoi']);
				Form::closeInput();	
				echo " ".$results['nom']."(".$results['date'].") ";							
			}
			echo'<hr/>';
			echo'Sexe : Homme';
			Form::addRadioButton("sexe", "h");
			echo' Femme ';
			Form::addRadioButton("sexe", "f");
			echo'<br/>';
			
			Form::openInput("nom", "text", "Nom: ", $nomForm, 25);
			Form::closeInput(TRUE, TRUE);
			
			Form::openInput("prenom", "text", "Prénom: ", $prenomForm, 25);
			Form::closeInput(TRUE, TRUE);
			
			Form::openInput("mail", "text", "Mail: ", NULL, 50);
			Form::closeInput(TRUE, TRUE);
			
			Form::openInput("jourNaissance", "text", "Date de naissance (jj/mm/aaaa) : ", NULL, 2, TRUE); 
			Form::closeInput(TRUE);
			
			Form::addSelect("moisNaissance", "mois");
			
			Form::openInput("anneeNaissance", "text", NULL, NULL, 4);
			Form::closeInput(TRUE, TRUE);
					
			Form::openInput("numero", "text", "Numéro portable: ", $phoneForm, 12);
			Form::closeInput(TRUE, TRUE);
			
			Form::openInput("numRue", "text", "Numéro rue : ", $numStreetForm, 3);
			Form::closeInput(TRUE, TRUE);
			
			Form::openInput("nomRue", "text", "Nom rue : ", $nameStreetForm, 50);
			Form::closeInput(TRUE, TRUE);
			
			Form::openInput("ville", "text", "Ville : ", $cityForm, 30);
			Form::closeInput(TRUE, TRUE);
			
			Form::openInput("codePostal", "text", "Code Postal : ", $postalCodeForm, 5);
			Form::closeInput(TRUE, TRUE);
			
			echo'Êtes-vous licencié ? Non';
			Form::addRadioButton("licencie", 0, "cacheSuiteFormTournoi()");		
			echo' Oui ';
			Form::addRadioButton("licencie", 1, "afficheSuiteFormTournoi()");
			echo'<br/><div id="formulaireTournament">';
			
			Form::openInput("club", "text", "Club : ", NULL, 25);
			Form::closeInput(FALSE, TRUE);
			
			Form::openInput("numLicence", "text", "Numéro de licence : ", NULL, 20);
			Form::closeInput(FALSE, TRUE);
			
			echo'</div>';
			Form::closeForm("registerTournament", "S'enregistrer");
		}
		else
			Message::showInformationMessage("Aucun tournoi n'est disponible pour le moment.");
		echo'</div>';
	}
?>
</body>
</html>