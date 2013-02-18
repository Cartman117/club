<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Validation des membres</title>
</head>
<body>
<?php
	require("./mod_club_class_message.php");
	require("./mod_club_functions.php");
	require("./mod_club_class_db.php");
	$connexionDatabase = Database::getInstance();
?>
    <table>
    	<caption>Membres à valider</caption>
        <tr>
        	<th>Nom</th>
            <th>Prénom</th>
            <th>Visualiser</th>
        </tr>
<?php
	$request = $connexionDatabase->selectDb("club_inscrit", array("id_inscrit", "nom", "prenom"), "licencie = 0");
	while($results = mysqli_fetch_assoc($request))
	{
		echo"<tr>
				<td>".$results['nom']."</td>
				<td>".$results['prenom']."</td>
				<td><form action=\"\" method=\"post\"><input type=\"hidden\" value=\"".$results['id_inscrit']."\" name=\"id_inscrit\"/><input type=\"submit\" name=\"visualize\" value=\"Visualiser\"/></form></td>
			</tr>";
				
	}
?>
</table>
</body>
</html>