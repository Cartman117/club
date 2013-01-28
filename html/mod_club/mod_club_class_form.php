<?php
	class Form
	{
		private static $tableauMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
		private static $tableauJours = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
		
         /* Constructeur de la classe Form qui permet de créer un formulaire */
		 public static function openForm($action = NULL, $method)
		 {
			 if($method == NULL)
				 echo'Formulaire impossible à créer';
			 else
			 	 echo"<form action=\"".$action."\" method=\"".$method."\">";
		 } 
		 /* Permet d'ouvrir un input.
		 	@param name Le nom du input.
			@param type Le type du input.
			@param labelValue La valeur du label, si l'on souhaite afficher un label devant l'input.
		 */
		 public static function openInput($name, $type, $labelValue = NULL, $inputValue = NULL, $maxLength = NULL)
		 {
			if($name == NULL or $type==NULL)
				echo'Veuillez indiquer le nom et le type du input';
			else
			{
				if($labelValue != NULL)
					echo"<label for=\"".$name."\">".$labelValue."</label>";
				
				echo"<input type=\"".$type."\" name=\"".$name."\"";
				
				if($labelValue != NULL)
					echo" id=\"".$name."\"";
				
				if($inputValue != NULL)
					echo" value=\"".$inputValue."\"";
					
				if($maxLength != NULL)
					echo" maxlength=\"".$maxLength."\"";
			}
		 }
		 	 
		 /* Permet de fermer un input.
		 	@param maxLength Si l'on souhaite appliquer une taille de caractères maxi. pour l'input.
			@param required Boolean rendant obligatoire ou non l'input.
			@param br Effectuer un saut de ligne en fin d'input.
		 */
		 public static function closeInput($required = FALSE, $br = FALSE)
		 {
			if($required)
				echo' required="required"';
				
			echo'/>';			
		    
			if($br)
				echo'<br/>';
		 }
		 
		 /*
		 
		 */
		 public static function addRadioButton($name, $value, $javascriptName = NULL)
		 {
			Form::openInput($name, "radio", NULL ,$value);
			 
			 if($javascriptName != NULL)
				 echo" onClick=\"".$javascriptName."\"";
				 
			 Form::closeInput();
		 }
		 
		 /*
		 	
		 */
		 public static function addSelect($name, $type, $br = FALSE)
		 {
			 if($name == NULL or $type == NULL)
			 	echo'Impossible d\'effectuer une liste d\'item.';
			 else
			 {
				 echo"<select name=\"".$name."\">";
				 $i = 1;
				 switch($type)
				 {
					 case "mois" : 
								foreach(Form::$tableauMois as $var)
								{
									echo"<option value=\"".$i."\">".$var."</option>";
									$i++;
								}
								break;
					case "jours" : 
								foreach(Form::tableauJours as $var)
								{
									echo"<option value=\"".$i."\">".$var."</option>";
									$i++;
								}
								break; 
				 }
				
				 echo"</select>";
				 
				 if($br)
				 	echo'<br/>';
			 }
		 }
		 
		 /* Permet de fermer le formulaire
		 	@param submitName Nom du bouton submit.
			@param submitValue Valeur affichée pour le bouton submit.
		 */
		 public static function closeForm($submitName, $submitValue)
		 {
			if($submitName == NULL || $submitValue==NULL)
				echo"Impossible de fermer le formulaire.";
			else
			{	
				Form::openInput($submitName, "submit", NULL, $submitValue, NULL, FALSE, TRUE);
				Form::closeInput();
				echo'</form>';
			}
		 }
	}
?>