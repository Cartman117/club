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
	require("./mod_club_class_member.php");

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
		Message::showInformationMessage("Vous ne possédez pas les droits nécessaires pour accèder à cette page.");
		die();
	}
	
	$connexionDatabase = Database::getInstance();

	if(isset($_POST['visualize']))
	{
		try
		{
			$member = new Member($_POST['id']);
			$member->showMember();
			
			session_start();
			$_SESSION['memberToValidate'] = $member;	
			Form::openForm(NULL, "post");
			Form::closeForm("validate", "Valider le membre");
		}
		catch(Exception $e)
		{
			Message::showErrorMessage($e->getMessage());
		}
	}
	elseif(isset($_POST['validate']))
	{
		session_start();
		$memberToRegister = &$_SESSION['memberToValidate'];
		if(!is_object($memberToRegister))
			Message::showErrorMessage("Il est impossible de valider le membre.");
		else
		{	try
			{
				unset($_SESSION['memberToValidate']);
				$memberToRegister->validateLicence();
				Message::showSuccessMessage("La validation du membre s'est bien effectuée.<br/> Vous allez être redirigé vers la liste des membres à valider.");
				
				header('refresh:2;url=');  
			}
			catch(Exception $e)
			{
				Message::showErrorMessage("Il est impossible de valider le membre.");
			}
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
						<input type=\"hidden\" value=\"".$results['id_inscrit']."\" name=\"id\"/>
						<input type=\"submit\" name=\"visualize\" value=\"Visualiser\"/>
						</form></td>
				</tr>";					
		}
		echo'</table>';
	}
?>
</body>
</html>