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
	Message::showErrorMessage("Hey");
	Message::showInformationMessage("Hey");
	Message::showSuccessMessage("Hey");
	if(isset($_POST['registerMineur']))
	{
		require("./mod_club_functions.php");
		require("./mod_club_class_db.php");
		$connexionDatabase = Database::getInstance();
		
			if(verifyName($_POST['prenomEnfant']) && verifyName($_POST['nomEnfant']) 
				&& verifyName($_POST['nomResponsable']))
			{
				$jourNaissance = $_POST['jourNaissance'];
				$moisNaissance = $_POST['moisNaissance'];
				$anneeNaissance = $_POST['anneeNaissance'];
				if(preg_match("/^[0-9]{1,2}/", $jourNaissance) && preg_match("/[0-9]{4}/", $anneeNaissance))
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
									$connexionDatabase->insertDb("club_inscrit", array("nom", "prenom", "date_naiss",
										"ville_naiss", "code_postal_naiss", "ville", "code_postal", "nom_rue", "num_rue", 
										"nom_resp", "num_tel_resp", "licencie", "sexe"), array($_POST['nomEnfant'],
											$_POST['prenomEnfant'], $dateNaissance->format("d-m-Y") ,$_POST['villeNaissance'],
											$_POST['codePostalNaissance'], $_POST['ville'], $_POST['codePostal'],
											$_POST['nomRue'], $_POST['numRue'], $_POST['nomResponsable'],
											$_POST['numResponsable'], 0, $_POST['sexe']));
								}
								catch(Exception $e)
								{
									echo $e;
								}
							}
							else
								echo"Addr";
						}
						else
							echo "Pb tel";
					}
					else
					{
						echo'Erreur d\'âge';
					}
				}
			}
			else
			{
				echo'Erreur prénom, nom';
			}
	}
	elseif(isset($_POST['registerMajeur']))
	{
		require("./mod_club_functions.php");
		require("./mod_club_class_db.php");
		$connexionDatabase = Database::getInstance();
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
						if(verifyPhone($_POST['num']))
						{
							if(verifyAdress($_POST['numRue'], $_POST['nomRue'], $_POST['codePostal'], $_POST['ville'])
								&& verifyCodePostal($_POST['codePostalNaissance']) &&  verifyText($_POST['villeNaissance']))
							{
								try
								{
									$connexionDatabase->insertDb("club_inscrit", array("nom", "prenom", "date_naiss",
										"ville_naiss", "code_postal_naiss", "ville", "code_postal", "nom_rue", "num_rue", 
										"num_tel_resp", "licencie", "sexe"), array($_POST['nom'],
											$_POST['prenom'], $dateNaissance->format("d-m-Y") ,$_POST['villeNaissance'],
											$_POST['codePostalNaissance'], $_POST['ville'], $_POST['codePostal'],
											$_POST['nomRue'], $_POST['numRue'], $_POST['num'], 0, $_POST['sexe']));
								}
								catch(Exception $e)
								{
									echo $e;
								}
							}
							else
								echo"Addr";
						}
						else
							echo "Pb tel";
					}
					else
					{
						echo'Erreur d\'âge';
					}
				}
			}
			else
			{
				echo'Erreur prénom, nom';
			}
	}
	else
	{
		include("./mod_club_class_form.php");
		echo'Êtes vous mineur';
		Form::addRadioButton("formulaire", "mineur", "afficheFormulaireMineur()");
		echo' ou  majeur ';
		Form::addRadioButton("formulaire", "majeur", "afficheFormulaireMajeur()");
		echo'<br/><div id="mineur">';
		
		Form::openForm(NULL, "post");
		echo'Sexe : Homme';
		Form::addRadioButton("sexe", 1);
		echo' Femme ';
		Form::addRadioButton("sexe", 0);
		echo'<br/>';
		Form::openInput("nomEnfant", "text", "Nom enfant: ", NULL, 25);
		Form::closeInput(FALSE, TRUE);
		
		Form::openInput("prenomEnfant", "text", "Prénom enfant: ", NULL, 25);
		Form::closeInput(FALSE, TRUE);
		
		Form::openInput("jourNaissance", "number", "Date de naissance (jj/mm/aaaa) : ", NULL, 2); 
		Form::closeInput(FALSE);
		
		Form::addSelect("moisNaissance", "mois");
		
		Form::openInput("anneeNaissance", "number", NULL, NULL, 4);
		Form::closeInput(FALSE, TRUE);
		
		Form::openInput("villeNaissance", "text", "Ville de naissance: ", NULL, 25);
		Form::closeInput(FALSE);
		
		Form::openInput("codePostalNaissance", "text", "Code postal du lieu de naissance : ", NULL, 5);
		Form::closeInput(FALSE, TRUE);
		
		Form::openInput("nomResponsable", "text", "Nom responsable: ", NULL, 25);
		Form::closeInput(FALSE, TRUE);
		
		Form::openInput("num", "text", "Numéro portable: ", NULL, 12);
		Form::closeInput(FALSE, TRUE);
		
		Form::openInput("numRue", "text", "Numéro rue : ", NULL, 3);
		Form::closeInput(FALSE, TRUE);
		
		Form::openInput("nomRue", "text", "Nom rue : ", NULL, 50);
		Form::closeInput(FALSE, TRUE);
		
		Form::openInput("codePostal", "text", "Code postal : ", NULL, 5);
		Form::closeInput(FALSE);
		
		Form::openInput("ville", "text", "Ville : ", NULL, 25);
		Form::closeInput(FALSE, TRUE);
	
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
		
		Form::openInput("jourNaissance", "number", "Date de naissance (jj/mm/aaaa) : ", NULL, 2, TRUE); 
		Form::closeInput(TRUE);
		
		Form::addSelect("moisNaissance", "mois");
		
		Form::openInput("anneeNaissance", "number", NULL, NULL, 4);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("villeNaissance", "text", "Ville de naissance: ", NULL, 25);
		Form::closeInput(TRUE);
		
		Form::openInput("codePostalNaissance", "text", "Code postal du lieu de naissance : ", NULL, 5);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("numero", "text", "Numéro portable: ", NULL, 12);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("numRue", "text", "Numéro rue : ", NULL, 3);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("nomRue", "text", "Nom rue : ", NULL, 50);
		Form::closeInput(TRUE, TRUE);
		
		Form::openInput("codePostal", "text", "Code Postal : ", NULL, 5);
		Form::closeInput(TRUE);
		
		Form::openInput("ville", "text", "Ville : ", NULL, 25);
		Form::closeInput(TRUE, TRUE);
	
		Form::closeForm("registerMajeur", "S'enregistrer");	
		echo'</div>';
	}
?>
</body>
</html>