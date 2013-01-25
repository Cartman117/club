<html>
<head>
<title>TEST</title>
</head>
<body>
<script language="javascript" src="mod_club_form.js"></script>
<?php
	include("./mod_club_class_form.php");
	
	$formulaire =  new Form(NULL, "post");
	$formulaire->addInput("nom", "text", "Nom : ", NULL, 20, TRUE, TRUE);
	$formulaire->addInput("prenom", "text", "Prénom : ", NULL, 20, TRUE, TRUE);
	$formulaire->addInput("jourNaissance", "number", "Date de Naissance (jj/mm/aaaa) : ", NULL, 2, TRUE);
	$formulaire->addSelect("moisNaissance", "mois");
	$formulaire->addInput("anneeNaissance", "number", NULL, NULL, 4, TRUE, TRUE);
	$formulaire->addInput("adresse", "text", "Adresse : ", NULL, NULL, TRUE, TRUE);
	$formulaire->addInput("codePostal", "text", "Code Postal : ", NULL, 5, TRUE);
	$formulaire->addInput("ville", "text", "Ville : ", NULL, 5, TRUE, TRUE);	
	$formulaire->closeForm("register", "S'enregistrer");
?>
</body>
</html>