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
		return (preg_match("/^[a-zA-Z0-9]+", $texte));
	}
	
	function exportExcel($idTournoi)
	{
		header("Content-type: application/vnd.ms-excel");
		header("Content-disposition: csv" . date("Y-m-d") . ".csv");
		include "mod_club_class_db.php";
		$link = Database::getInstance();
		$tournamentName = $link->getTournamentName($idTournoi);
		header("Content-disposition: filename=".$tournamentName.".xls");
		$csv_output = $link->exportationExcel($idTournoi);
		print $csv_output;
		exit;
	}
?>