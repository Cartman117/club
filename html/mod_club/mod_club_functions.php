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
	
	function exportationPDFMineur($nom, $prenom, $date_naiss, $ville_naiss, $code_postal_naiss, $ville, $code_postal, $nom_rue, $num_rue, $nom_resp, $num_tel_resp)
	{
		require("./FPDF/fpdf.php");

		$pdf = new FPDF();
		$pdf->AddPage();
		
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(45);
		$pdf->Cell(20,10,'Formulaire d\'inscription Moins de 18 ans');
		$pdf->Ln(15);
		
		$pdf->SetFont('Arial','',13);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Je soussigné(e) M. ou Mme '.$nom_resp.' demeurant au '));
		$pdf->Ln();
		$pdf->Cell(10);
		$pdf->Cell(20, 10, utf8_decode($num_rue.' '.$nom_rue));
		$pdf->Ln();
		$pdf->Cell(10);
		$pdf->Cell(20, 10, utf8_decode($code_postal.' '.$ville));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Numéro de téléphone : '.$num_tel_resp));
		$pdf->Ln(15);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('J\'autorise mon enfant : '.$nom.' '.$prenom.' né le '.$date_naiss.' à '.$ville_naiss.' ('.$code_postal_naiss.')'));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('à pratiquer le triathlon, le duathlon et/ou les épreuves enchaînées (définies par la'));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Fédération Française de Triathlon) au sein du club de BILLOM TRIATHLON.'));
		$pdf->Ln(20);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('J\'autorise BILLOM TRIATHLON  à diffuser des photos (cadre de l\'entraînement'));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('et des compétitions) de mon enfant sur le site du club et aux médias.'));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('J\'autorise l\'encadrement du club à faire hospitaliser mon enfant si son état le nécessite'));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, '(CHRU ou clinique).');
		$pdf->Ln(20);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Personnes à prévenir en cas d\'urgence :'));
		$pdf->Cell(90);
		$pdf->Cell(20, 10, utf8_decode('Téléphone :'));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, '.................................................................');
		$pdf->Cell(90);
		$pdf->Cell(20, 10, '................................');
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, '.................................................................');
		$pdf->Cell(90);
		$pdf->Cell(20, 10, '................................');
		$pdf->Ln(20);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('N° de sécurité sociale : ..............................................'));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Nom/N° complémentaire : ..........................................'));
		$pdf->Ln(20);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, 'Mon enfant suit un traitement : .............................................................................');
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Mon enfant est allergique à ..................................................................................'));
		$pdf->Ln(15);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, 'Fait le ..........................');
		$pdf->Cell(50);
		$pdf->Cell(20, 10, 'Signature : ');
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('à ..................................'));
		$pdf->Output();
	}
	
	function exportationPDFMajeur($nom, $prenom, $date_naiss, $ville_naiss, $code_postal_naiss, $ville, $code_postal, $nom_rue, $num_rue, $num_tel)
	{
		require("./FPDF/fpdf.php");

		$pdf = new FPDF();
		$pdf->AddPage();
		
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(45);
		$pdf->Cell(20,10,'Formulaire d\'inscription Plus de 18 ans');
		$pdf->Ln(20);
		
		$pdf->SetFont('Arial','',13);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Nom : '.$nom));
		$pdf->Cell(40);
		$pdf->Cell(20, 10, utf8_decode('Prénom : '.$prenom));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Adresse : '.$num_rue.' '.$nom_rue));
		$pdf->Ln();
		$pdf->Cell(25);
		$pdf->Cell(20, 10, utf8_decode($code_postal.' '.$ville));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Date de naissance : '.$date_naiss));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Nationalité : .................................................................'));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Lieu de naissance : '.$ville_naiss.'               Code postal de naissance : '.$code_postal_naiss));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, 'Profession : .................................................................');
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('N° de téléphone fixe : ......................      N° de téléphone portable : '.$num_tel));
		$pdf->Ln(20);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Première licence Fédération Française de Triathlon'));
		$pdf->Cell(90);
		$pdf->Cell(7, 7, '', 1);
		$pdf->Ln(10);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Renouvellement à BILLOM Triathlon'));
		$pdf->Cell(60);
		$pdf->Cell(7, 7, '', 1);
		$pdf->Ln(20);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Ancien club ( Année Précédente) : ...........................................................'));
		$pdf->Ln(20);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Personnes à prévenir en cas d\'urgence :'));
		$pdf->Cell(90);
		$pdf->Cell(20, 10, utf8_decode('Téléphone :'));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, '.................................................................');
		$pdf->Cell(90);
		$pdf->Cell(20, 10, '................................');
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, '.................................................................');
		$pdf->Cell(90);
		$pdf->Cell(20, 10, '................................');
		$pdf->Ln(20);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('Je soussigné(e) Mr ou Mme '.$nom));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('autorise BILLOM Triathlon à diffuser sur son site web et les médias les photos'));
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('où j\'apparais en compétition ou à l\'entraînement.'));
		$pdf->Ln(20);
		$pdf->Cell(4);
		$pdf->Cell(20, 10, 'Fait le ..........................');
		$pdf->Cell(50);
		$pdf->Cell(20, 10, 'Signature : ');
		$pdf->Ln();
		$pdf->Cell(4);
		$pdf->Cell(20, 10, utf8_decode('à ..................................'));
	
		$pdf->Output();
	}
?>