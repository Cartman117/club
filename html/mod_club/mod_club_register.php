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
	include("./mod_club_class_form.php");
	echo'Êtes vous mineur';
	Form::addRadioButton("formulaire", "mineur", "afficheFormulaireMineur()");
	echo' ou  majeur ';
	Form::addRadioButton("formulaire", "majeur", "afficheFormulaireMajeur()");
	echo'<br/><div id="mineur">';
	
	Form::openForm(NULL, "post");
	echo'Sexe : Homme';
	Form::addRadioButton("sexe", "homme");
	echo' Femme ';
	Form::addRadioButton("sexe", "femme");
	echo'<br/>';
	Form::openInput("nomEnfant", "text", "Nom enfant: ", NULL, 20);
	Form::closeInput(TRUE, TRUE);
	
	Form::openInput("prenomEnfant", "text", "Prénom enfant: ", NULL, 20);
	Form::closeInput(TRUE, TRUE);
	
	Form::openInput("jourNaissance", "number", "Date de naissance (jj/mm/aaaa) : ", NULL, 2, TRUE); 
	Form::closeInput(TRUE);
	
	Form::addSelect("moisNaissance", "mois");
	
	Form::openInput("anneeNaissance", "number", NULL, NULL, 4);
	Form::closeInput(TRUE, TRUE);
	
	Form::openInput("nomResponsable", "text", "Nom responsable: ", NULL, 20);
	Form::closeInput(TRUE, TRUE);
	
	Form::openInput("numResponsable", "text", "Numéro fixe: ", NULL, 20);
	Form::closeInput(TRUE, TRUE);
	
	Form::openInput("adresse", "text", "Adresse : ");
	Form::closeInput(TRUE, TRUE);
	
	Form::openInput("codePostal", "text", "Code Postal : ", NULL, 5);
	Form::closeInput(TRUE);
	
	Form::openInput("ville", "text", "Ville : ", NULL, 5);
	Form::closeInput(TRUE, TRUE);

	Form::closeForm("registerMineur", "S'enregistrer");
	echo'</div>
	<div id="majeur">';
	
	Form::openForm(NULL, "post");
	echo'Sexe : Homme';
	Form::addRadioButton("sexe", "homme");
	echo' Femme ';
	Form::addRadioButton("sexe", "femme");
	echo'<br/>';
	
	Form::openInput("nomEnfant", "text", "Nom: ", NULL, 20);
	Form::closeInput(TRUE, TRUE);
	
	Form::openInput("prenomEnfant", "text", "Prénom: ", NULL, 20);
	Form::closeInput(TRUE, TRUE);
	
	Form::openInput("jourNaissance", "number", "Date de naissance (jj/mm/aaaa) : ", NULL, 2, TRUE); 
	Form::closeInput(TRUE);
	
	Form::addSelect("moisNaissance", "mois");
	
	Form::openInput("anneeNaissance", "number", NULL, NULL, 4);
	Form::closeInput(TRUE, TRUE);
	
	Form::openInput("numero", "text", "Numéro fixe: ", NULL, 20);
	Form::closeInput(TRUE, TRUE);
	
	Form::openInput("adresse", "text", "Adresse : ");
	Form::closeInput(TRUE, TRUE);
	
	Form::openInput("codePostal", "text", "Code Postal : ", NULL, 5);
	Form::closeInput(TRUE);
	
	Form::openInput("ville", "text", "Ville : ", NULL, 5);
	Form::closeInput(TRUE, TRUE);

	Form::closeForm("registerMajeur", "S'enregistrer");	
	echo'</div>';
	
?>
</body>
</html>