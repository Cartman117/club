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
	require("./mod_club_class_member.php");
	$connexionDatabase = Database::getInstance();

	if(isset($_POST['visualize']))
	{
		try
		{
			$member = new Member($_POST['id_inscrit']);
			$member->showMember();
		}
		catch(Exception $e)
		{
			Message::showErrorMessage($e->getMessage());
		}
	}
	else
	{
?>
		<table>
    	<caption>Membres à valider</caption>
        <tr>
        	<th>Nom</th>
            <th>Prénom</th>
            <th>Date de naissance</th>
            <th>Visualiser</th>
        </tr>
<?php
		$request = $connexionDatabase->selectDb("club_inscrit", array("id_inscrit", "nom", "prenom", "DATE_FORMAT(date_naiss,'%d/%m/%Y') AS date_naiss"), "licencie = 0");
		while($results = mysqli_fetch_assoc($request))
		{
			echo"<tr>
					<td>".$results['nom']."</td>
					<td>".$results['prenom']."</td>
					<td>".$results['date_naiss']."</td>
					<td><form action=\"\" method=\"post\">
						<input type=\"hidden\" value=\"".$results['id_inscrit']."\" name=\"id_inscrit\"/>
						<input type=\"submit\" name=\"visualize\" value=\"Visualiser\"/>
						</form></td>
				</tr>";					
		}
		echo'</table>';
	}
?>
</body>
</html>