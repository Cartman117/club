<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Club - Enregistrement</title>
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
	}
	
	$connexionDatabase = Database::getInstance();
	if(isset($_POST['registerTournament']))
	{
		if(Form::checkValues($_POST))
		{
			if(isset($_POST['photos']))
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
								try
								{
									$connexionDatabase->insertDb("club_inscrit_pour_tournoi", 
										array("nom", "prenom", "date_naiss", "num_tel_resp",
											"ville", "code_postal", "nom_rue", "num_rue", 
											"mail", "licencie", "club", "num_licence", "sexe"),
										array($_POST['nom'], $_POST['prenom'],
											$dateNaissance->format("Y-m-d"), $_POST['numero'], 
											$_POST['ville'], $_POST['codePostal'],
											$_POST['nomRue'], $_POST['numRue'], $_POST['mail'], $_POST['formulaire'],
											$_POST['club'], $_POST['num_licence'], $_POST['sexe']));
									
									Message::showSuccessMessage("Votre inscription au tournoi s'est effectué correctement.");
								}
								catch(Exception $e)
								{
									Message::showErrorMessage("Une erreur s'est produite lors de votre inscription au tournoi. Veuillez réessayer.");
								}
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
				Message::showErrorMessage("L'autorisation à la publication des photos vous concernant est obligatoire.");
		}
		else
			Message::showErrorMessage("Veuillez remplir tous les champs.");
	}
	if(isset($_POST['setMailForTournament']))
	{
		$mail = $_POST['mail'];
		if(filter_var($mail, FILTER_VALIDATE_EMAIL))
		{
			$nbResults = mysqli_fetch_row($connexionDatabase->selectDb(array("club_inscrit_tournoi"), array("COUNT(*)"), "mail = ".$mail));
			if($nbResults[0] == 1)
				$addTournoi = TRUE;
			else
				Message::showErrorMessage("Vous ne vous êtes jamais inscrit avec ce tournoi.");
		}
		else
			Message::showErrorMessage("L'adresse mail est éronnée.");
	}
	if(isset($addTournoi))
	{
		echo'<div id="nouveauTournoi"><br/><h3>Inscription tournoi</h3><br/>Liste des tournois : ';
		
		$request = $connexionDatabase->selectDb(array("club_tournoi"), 
							array("*", "DATE_FORMAT(date,'%d/%m/%Y') AS date"),
								 "NOT EXISTS(SELECT * FROM club_inscrit_tournoi 
								 	WHERE club_inscrit_tournoi.id_tournoi = club_tournoi.id_tournoi 
								 	AND id_inscrit_pour_tournoi = ".$id_inscrit.")");
		
		while($results = mysqli_fetch_assoc($request))
		{
			Form::openInput("tournoi", "checkbox", NULL, $results['id_tournoi']);
			Form::closeInput();	
			echo $results['nom']."(".$results['date'].")";							
		}
	}	
		echo'Si vous vous êtes déjà inscrit à un tournois et que vous souhaitez vous inscrire à d\'autres tournois cliquez ici ';
		Form::addRadioButton("type", "ajoutTournoi", "afficheFormulaireAjoutTournoi()");
		echo' sinon cliquez ici ';
		Form::addRadioButton("type", "nouveauTournoi", "afficheFormulaireNouveauTournoi()");
		echo'<div id="ajoutTournoi"><br/>';
			Form::openForm(NULL, "POST", "labelMail");
			Form::openInput("mail", "text", "Mail utilisé pour les anciennes inscriptions :");
			Form::closeInput(TRUE, TRUE);
			Form::closeForm("setMailForTournament", "Ajouter les tournois");			
		echo'</div>';
		echo'<div id="nouveauTournoi"><br/><h3>Inscription tournoi</h3><br/>Liste des tournois : ';
		$request = $connexionDatabase->selectDb(array("club_tournoi"), array("id_tournoi","nom", "DATE_FORMAT(date,'%d/%m/%Y') AS date"));
		while($results = mysqli_fetch_assoc($request))
		{
			Form::openInput("tournoi", "checkbox", NULL, $results['id_tournoi']);
			Form::closeInput();	
			echo $results['nom']."(".$results['date'].")";							
		}

		echo'<hr/>';		
		Form::openForm(NULL, "post");
		echo'Sexe : Homme';
		Form::addRadioButton("sexe", "h");
		echo' Femme ';
		Form::addRadioButton("sexe", "f");
		echo'<br/>';
		
		Form::openInput("nom", "text", "Nom: ", NULL, 25);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("prenom", "text", "Prénom: ", NULL, 25);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("jourNaissance", "text", "Date de naissance (jj/mm/aaaa) : ", NULL, 2, TRUE); 
		Form::closeInput(TRUE);
		
		Form::addSelect("moisNaissance", "mois");
		
		Form::openInput("anneeNaissance", "text", NULL, NULL, 4);
		Form::closeInput(TRUE, TRUE);
				
		Form::openInput("numero", "text", "Numéro portable: ", NULL, 12);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("numRue", "text", "Numéro rue : ", NULL, 3);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("nomRue", "text", "Nom rue : ", NULL, 50);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("ville", "text", "Ville : ", NULL, 25);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("codePostal", "text", "Code Postal : ", NULL, 5);
		Form::closeInput(TRUE, TRUE);
		echo'Êtes-vous licencié ? Non';
		Form::addRadioButton("formulaire", 0, "cacheSuiteFormTournoi()");		
		echo' Oui ';
		Form::addRadioButton("formulaire", 1, "afficheSuiteFormTournoi()");
		echo'<br/><div id="formulaireTournament">';
		Form::openInput("club", "text", "Club : ", NULL, 25);
		Form::closeInput(TRUE, TRUE);
		Form::openInput("numLicence", "text", "Numéro de licence : ", NULL, 20);
		Form::closeInput(TRUE, TRUE);
		echo'</div>';
		Form::closeForm("registerTournament", "S'enregistrer");
		echo'</div>';
?>
</body>
</html>