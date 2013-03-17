<?php	
	function verifyName($texte)
	{
		return (preg_match(
			"/^([A-Z][a-zàâäéèêëïîçùûüôö]+|[A-Z][\'][a-zàâäéèêëïîçùûüôö]+)(([\-\ ][A-Z]?[a-zàâäéèêëïîçùûüôö]+)*|[\ ][A-Z][\'][a-zàâäéèêëïîçùûüôö]+)?$/", $texte));
	}

	function verifyText($texte)
	{
		return (preg_match("/^(([a-zA-ZàâéèêôùûïîçÀÂÉÈÔÙÛÇäöëüÄÖËÜ](\-|( )*)){1,50})$/",$texte));
	}
	
	function verifyPhone($numTel)
	{
		return preg_match("/^((\+33)|0)[0-9]{9}$/", $numTel);
	}
	
	function verifyCodePostal($codePostal)
	{
		return(preg_match("/^[0-9]{5}$/", $codePostal));
	}
	
	function verifyAdress($numRue, $nomRue, $codePostal, $ville)
	{
		return(verifyCodePostal($codePostal) && preg_match("/^[0-9]{1,3}$/", $numRue) && verifyText($nomRue)
				&& verifyName($ville));
	}
	
	function verifyLicence($texte)
	{
		return (preg_match("/[a-zA-Z0-9]+/", $texte));
	}
	
	function exportExcel($idTournoi)
	{	
		require("./mod_club_class_message.php");
		require("./mod_club_class_db.php");
		$connexionDatabase = Database::getInstance();
		$tournament = $connexionDatabase->getTournamentName($idTournoi);
		$tournamentName = $tournament['nom'].".csv";
			
		header('Content-Encoding: UTF-8');
		header("Content-type: application/vnd.ms-excel; charset=UTF-8");
		header("Content-disposition: attachment; filename=$tournamentName");
		
		$csv_output = $connexionDatabase->exportationExcel($idTournoi);
		print $csv_output;
		exit;
	}
	
	if(isset($_POST['exportTournament']))
	{
		if(isset($_POST['id']) && (is_numeric($_POST['id'])))
		{
			try
			{
				exportExcel($_POST['id']);
			}
			catch(Exception $e)
			{
				Message::showErrorMessage("Une erreur s'est produite lors de l'exportation du tournoi. Veuillez réessayer.");
			}
		}
	}
?>