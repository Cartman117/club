<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Validation des membres</title>
	<link href="./mod_club.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
	require("./mod_club_class_message.php");
	require("./mod_club_functions.php");
	require("./mod_club_class_db.php");
	require("./mod_club_class_form.php");
	
	session_start();
	
	define( '_JEXEC', 1 );
	define('JPATH_BASE', '../../../..' );
	define( 'DS', DIRECTORY_SEPARATOR );
	
	require_once (JPATH_BASE .DS.'includes'.DS.'defines.php');
	require_once (JPATH_BASE .DS.'includes'.DS.'framework.php');
	
	$mainframe = JFactory::getApplication('site');
	$mainframe->initialise();
	
	$user = JFactory::getUser();
	jimport( 'joomla.user.helper' );
	$groups = JUserHelper::getUserGroups($user->id);
	
	if (!isset($groups[8]) || $groups[8]!=8)
	{
		Message::showInformationMessage("Vous ne possédez pas les droits nécessaires pour accèder à cette page ou n'êtes pas connecté(e).");
		die();
	}
	
	$connexionDatabase = Database::getInstance();
		
	if(isset($_POST['addTournament']))
	{
		$connexionDatabase = Database::getInstance();
		if(Form::checkValues($_POST))
		{
			if(verifyText($_POST['nomTournoi']))
			{
				$jourTournoi = $_POST['jourTournoi'];
				$moisTournoi = $_POST['moisTournoi'];
				$anneeTournoi = $_POST['anneeTournoi'];
				if(preg_match("/^[0-9]{1,2}/", $jourTournoi) && preg_match("/[0-9]{4}/", $anneeTournoi) && $jourTournoi <= 31)
				{
					$dateTournoi = new DateTime($anneeTournoi."-".$moisTournoi."-".$jourTournoi);
					$dateActuelle = new DateTime("now");
					$dateActuelle = $dateActuelle->setTime(0,0,0);
					if($dateTournoi > $dateActuelle)
					{
						try
						{
							$connexionDatabase->insertDb("club_tournoi", 
								array("nom", "date"), 
								array($_POST['nomTournoi'], $dateTournoi->format("Y-m-d")));
									
							Message::showSuccessMessage("L'ajout du tournoi s'est effectué correctement.");
						}
						catch(Exception $e)
						{
							Message::showErrorMessage("Une erreur s'est produite lors de l'ajoutdu tournoi. Veuillez réessayer.");
						}						
					}
					else
						Message::showErrorMessage("La date du tournoi doit être supérieur à la date actuelle.");
				}
				else
					Message::showErrorMessage("La date incorrecte.");
			}
			else
				Message::showErrorMessage("Le nom du tounoi est incorrect.");				
		}
		else
			Message::showErrorMessage("Veuillez remplir tous les champs.");
	}
	if(isset($_POST['deleteTournament']))
	{
		$connexionDatabase = Database::getInstance();
		if(Form::checkValues($_POST))
		{
			if(is_numeric($_POST['id']))
			{
				try
				{
					$connexionDatabase->deleteDb("club_tournoi", " id_tournoi = ".$_POST['id']);
							
					Message::showSuccessMessage("La suppression du tournoi s'est effectuée correctement.");
				}
				catch(Exception $e)
				{
					Message::showErrorMessage("Une erreur s'est produite lors de la suppression du tournoi. Veuillez réessayer.");
				}
			}
		}
	}
	?>
    <h3>Ajouter un tournoi</h3>
    <?php
	Form::openForm(NULL, "post");
	Form::openInput("nomTournoi", "text", "Nom tournoi: ", NULL, 30);
	Form::closeInput(TRUE, TRUE);
	Form::openInput("jourTournoi", "text", "Date du tournoi (jj/mm/aaaa) : ", NULL, 2); 
	Form::closeInput(TRUE);
	Form::addSelect("moisTournoi", "mois");
	Form::openInput("anneeTournoi", "text", NULL, NULL, 4);
	Form::closeInput(TRUE, TRUE);
	Form::closeForm("addTournament", "Ajouter");
?>
<hr/>
<table>
    	<caption>Tournois</caption>
        <tr>
        	<th>Nom</th>
            <th>Date</th>
            <th>Supprimer</th>
        </tr>
<?php
		$request = $connexionDatabase->selectDb(array("club_tournoi"), array("id_tournoi","nom", "DATE_FORMAT(date,'%d/%m/%Y') AS date"));
		while($results = mysqli_fetch_assoc($request))
		{
			echo"<tr>
					<td>".$results['nom']."</td>
					<td>".$results['date']."</td>
					<td><form action=\"\" method=\"post\">
						<input type=\"hidden\" value=\"".$results['id_tournoi']."\" name=\"id\"/>
						<input type=\"submit\" name=\"deleteTournament\" value=\"Supprimer\"/>
						</form>
					</td>
				</tr>";					
		}
		echo'</table>';
?>
	</body>
</html>