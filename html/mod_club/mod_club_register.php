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
	$groups = JUserHelper::getUserGroups($user->id);
	
	if ($user->guest == 1 || !Member::checkIsValidate($user->id))
	{
		Message::showInformationMessage("Vous n'êtes pas connecté(e) ou êtes déjà inscrit.");
		die();
	}
	
	$register = FALSE;
	
	if(isset($_POST['registerMineur']))
	{
		$connexionDatabase = Database::getInstance();
		if(Form::checkValues($_POST))
		{
			if(isset($_POST['photos']))
			{
				if(verifyName($_POST['prenomEnfant']) && verifyName($_POST['nomEnfant']) 
					&& verifyName($_POST['nomResponsable']))
				{
					$jourNaissance = $_POST['jourNaissance'];
					$moisNaissance = $_POST['moisNaissance'];
					$anneeNaissance = $_POST['anneeNaissance'];
					if(preg_match("/^[0-9]{1,2}/", $jourNaissance) && preg_match("/[0-9]{4}/", $anneeNaissance) && $jourNaissance <= 31)
					{
						$dateNaissance = new DateTime($anneeNaissance."-".$moisNaissance."-".$jourNaissance);
						$dateActuelle = new DateTime("now");
						$dateActuelle = $dateActuelle->setTime(0,0,0);
						if($dateNaissance->diff($dateActuelle)->y < 18)
						{
							if(verifyPhone($_POST['numResponsable']))
							{
								if(verifyAdress($_POST['numRue'], $_POST['nomRue'], $_POST['codePostal'], $_POST['ville'])
									&& verifyCodePostal($_POST['codePostalNaissance']) &&  verifyText($_POST['villeNaissance']))
								{
									try
									{
										$connexionDatabase->insertDb("club_inscrit", 
											array("id_compte", "nom", "prenom", "date_naiss",
												"ville_naiss", "code_postal_naiss", "ville", "code_postal", "nom_rue", "num_rue", 
												"nom_resp", "num_tel_resp", "licencie", "sexe"), 
											array($user->id, $_POST['nomEnfant'],
												$_POST['prenomEnfant'], $dateNaissance->format("Y-m-d") ,$_POST['villeNaissance'],
												$_POST['codePostalNaissance'], $_POST['ville'], $_POST['codePostal'],
												$_POST['nomRue'], $_POST['numRue'], $_POST['nomResponsable'],
												$_POST['numResponsable'], 0, $_POST['sexe']));
												
										Message::showSuccessMessage("Votre inscription s'est effectué correctement.");
										$register = TRUE;
									}
									catch(Exception $e)
									{
										Message::showErrorMessage("Une erreur s'est produite lors de votre inscription. Veuillez réassayer.");
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
	if(isset($_POST['registerMajeur']))
	{
		$connexionDatabase = Database::getInstance();
		$connexionDatabase = Database::getInstance();
		if(Form::checkValues($_POST))
		{
			if(isset($_POST['photos']))
			{
				if(verifyName($_POST['prenom']) && verifyName($_POST['nom']))
				{
					$jourNaissance = $_POST['jourNaissance'];
					$moisNaissance = $_POST['moisNaissance'];
					$anneeNaissance = $_POST['anneeNaissance'];
					if(preg_match("/^[0-9]{1,2}/", $jourNaissance) && preg_match("/[0-9]{4}/", $anneeNaissance))
					{
						$dateNaissance = new DateTime($anneeNaissance."-".$moisNaissance."-".$jourNaissance);
						$dateActuelle = new DateTime("now");
						$dateActuelle = $dateActuelle->setTime(0,0,0);
						if($dateNaissance->diff($dateActuelle)->y >= 18)
						{
							if(verifyPhone($_POST['numero']))
							{
								if(verifyAdress($_POST['numRue'], $_POST['nomRue'], $_POST['codePostal'], $_POST['ville'])
									&& verifyCodePostal($_POST['codePostalNaissance']) &&  verifyText($_POST['villeNaissance']))
								{
									try
									{
										$connexionDatabase->insertDb("club_inscrit", 
											array("id_compte", "nom", "prenom", "date_naiss",
												"ville_naiss", "code_postal_naiss", "ville", "code_postal", "nom_rue", "num_rue", 
												"num_tel_resp", "licencie", "sexe"),
											array($user->id, $_POST['nom'],
												$_POST['prenom'], $dateNaissance->format("Y-m-d"),$_POST['villeNaissance'],
												$_POST['codePostalNaissance'], $_POST['ville'], $_POST['codePostal'],
												$_POST['nomRue'], $_POST['numRue'], $_POST['numero'], 0, $_POST['sexe']));
										
										Message::showSuccessMessage("Votre inscription s'est effectué correctement.");
										$register = TRUE;
									}
									catch(Exception $e)
									{
										Message::showErrorMessage("Une erreur s'est produite lors de votre inscription. Veuillez réassayer.");
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
	if(!$register)
	{
		echo'Êtes vous mineur ';
		Form::addRadioButton("formulaire", "mineur", "afficheFormulaireMineur()");
		echo' ou  majeur ';
		Form::addRadioButton("formulaire", "majeur", "afficheFormulaireMajeur()");
		echo'<br/><hr/><div id="mineur">';
		
		Form::openForm(NULL, "post");
		echo'Sexe : Homme ';
		Form::addRadioButton("sexe", 1);
		echo' Femme ';
		Form::addRadioButton("sexe", 0);
		echo'<br/><br/>';
		Form::openInput("nomEnfant", "text", "Nom enfant: ", NULL, 25);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("prenomEnfant", "text", "Prénom enfant: ", NULL, 25);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("jourNaissance", "text", "Date de naissance (jj/mm/aaaa) : ", NULL, 2); 
		Form::closeInput(TRUE);
		
		Form::addSelect("moisNaissance", "mois");
		
		Form::openInput("anneeNaissance", "text", NULL, NULL, 4);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("codePostalNaissance", "text", "Code postal du lieu de naissance : ", NULL, 5);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("villeNaissance", "text", "Ville de naissance: ", NULL, 25);
		Form::closeInput(TRUE, TRUE);		
		
		Form::openInput("nomResponsable", "text", "Nom responsable: ", NULL, 25);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("numResponsable", "text", "Numéro portable: ", NULL, 12);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("numRue", "text", "Numéro rue : ", NULL, 3);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("nomRue", "text", "Nom rue : ", NULL, 50);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("codePostal", "text", "Code postal : ", NULL, 5);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("ville", "text", "Ville : ", NULL, 30);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("photos", "checkbox");
		Form::closeInput();
		echo' J\'autorise les photos sur lesquelles mon enfant sera présent à être publiées sur ce site.<br/> Cette autorisation est obligatoire pour la validation de cette inscription.<br/>';
		
		Form::closeForm("registerMineur", "S'enregistrer");
		echo'</div>
		<div id="majeur">';
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
		
		Form::openInput("codePostalNaissance", "text", "Code postal du lieu de naissance : ", NULL, 5);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("villeNaissance", "text", "Ville de naissance: ", NULL, 25);
		Form::closeInput(TRUE, TRUE);
				
		Form::openInput("numero", "text", "Numéro portable: ", NULL, 12);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("numRue", "text", "Numéro rue : ", NULL, 3);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("nomRue", "text", "Nom rue : ", NULL, 50);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("codePostal", "text", "Code Postal : ", NULL, 5);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("ville", "text", "Ville : ", NULL, 25);
		Form::closeInput(TRUE, TRUE);		
		
		Form::openInput("photos", "checkbox");
		Form::closeInput();
		echo' J\'autorise les photos sur lesquelles je suis présent(e) à être publiées sur ce site.<br/> Cette autorisation est obligatoire pour la validation de votre inscription.<br/>';
	
		Form::closeForm("registerMajeur", "S'enregistrer");
	}
?>
</body>
</html>