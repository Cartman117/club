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
?>