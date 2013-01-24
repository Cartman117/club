<?php
	class Form
	{
		private static $tableauMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
		private static $tableauJours = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
		
         /* Constructeur de la classe Form qui permet de créer un formulaire */
		 public function __construct($action = NULL, $method)
		 {
			 if($method == NULL)
			 {
				 echo'Formulaire impossible à créer';
			 }
			 else
			 {
				 if($action == null)
				 	$action = "";
			 	 echo"<form action=".$action." method=".$method.">";
			 }
		 } 
		 /* Permet d'ajouter un champs du type souhaité
		 	@param name Le nom du input.
			@param type Le type du input.
			@param labelValue La valeur du label, si l'on souhaite afficher un label devant l'input.
			@param maxLength Si l'on souhaite appliquer une taille de caractères maxi. pour l'input.
			@param required Boolean rendant obligatoire ou non l'input.
		 */
		 public function addInput($name, $type, $labelValue = NULL, $inputValue = NULL, $maxLength = NULL, $required = FALSE)
		 {
			if($name == NULL or $type==NULL)
			{
				echo'Veuillez indiquer le nom et le type du input';
			}
			else
			{
				if($labelValue != NULL)
				{	
					echo"<label for=".$name.">".$value."</label>";
				}
				
				echo"<input type=".$type." name=".$value."";
				
				if($inputValue != NULL)
				{
					echo"value=".$inputValue."";
				}
				
				if($maxLength != NULL)
				{
					echo"maxlength=".$maxLength."";
				}
				
				if($required)
				{
					echo'required="required"';
				}
				
				echo"/>";
			}
		 }
		 
		 public function addSelect($name, $type)
		 {
			 if($name == NULL or $type == NULL)
			 {
				 echo'Impossible d\'effectuer une liste d\'item.';
			 }
			 else
			 {
				 switch($type)
				 {
					 case "mois" : 
								foreach($var in $tableauMois)
								{
									
								}
								break;
					case "jours" : 
								foreach($var in $tableauJours)
								{
									
								}
								break;
				 }
			 }
		 }
		 /* Permet de fermer le formulaire
		 	@param submitName Nom du bouton submit.
			@param submitValue Valeur affichée pour le bouton submit.
		 */
		 public function closeForm($submitName, $submitValue)
		 {
			if($submitName == NULL or $submitValue==NULL)
			{
				
			}
			else
			{
				addInput($submitName, "submit", NULL, $submitValue);
				echo'</form>';
			}
		 }
	}
?>