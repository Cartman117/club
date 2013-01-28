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

	$formulaire =  new Form(NULL, "post");
	
	echo'Êtes vous mineur';
	$formulaire->addRadioButton("formulaire", "mineur", "afficheFormulaireMineur()");
	echo' ou  majeur ';
	$formulaire->addRadioButton("formulaire", "majeur", "afficheFormulaireMajeur()");
	echo'<br/><div id="mineur">';
	$formulaire->openInput("nom", "text", "Nom : ", NULL, 20);
	$formulaire->closeInput(TRUE, TRUE);
	
	$formulaire->openInput("prenom", "text", "Prénom : ", NULL, 20);
	$formulaire->closeInput(TRUE, TRUE);
	
	$formulaire->openInput("jourNaissance", "number", "Date de Naissance (jj/mm/aaaa) : ", NULL, 2, TRUE); 
	$formulaire->closeInput(TRUE);
	
	$formulaire->addSelect("moisNaissance", "mois");
	
	$formulaire->openInput("anneeNaissance", "number", NULL, NULL, 4);
	$formulaire->closeInput(TRUE, TRUE);
	
	$formulaire->openInput("adresse", "text", "Adresse : ");
	$formulaire->closeInput(TRUE, TRUE);
	
	$formulaire->openInput("codePostal", "text", "Code Postal : ", NULL, 5);
	$formulaire->closeInput(TRUE);
	
	$formulaire->openInput("ville", "text", "Ville : ", NULL, 5);
	$formulaire->closeInput(TRUE, TRUE);

	$formulaire->closeForm("register", "S'enregistrer");
	echo'</div>
	<div id="majeur">TEST</div>';
	
?>
</body>
</html>