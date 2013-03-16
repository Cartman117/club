<?php
	class Database
	{
		private static $_instance = null;
		private $connexion;
		
		private function __construct()
		{
			$this->connexion = mysqli_connect("localhost", "root", "", "joomla")
				or die("Problème de connexion avec la base de donnée");
		}
		
		public static function getInstance()
		{
			if(is_null(self::$_instance))
				self::$_instance = new Database();
			return self::$_instance;
		}
		
		public function closeDb()
		{
			mysqli_close($this->connexion);
		}
		
		public function insertDb($nomTable, $tableColonne, $tableValeur)
		{
			$request = "INSERT into ".$nomTable;
			if(!empty($tableColonne))
			{
				$request .= " (";
				$i = 1;
				foreach($tableColonne as &$value)
				{
					if($this->checkEmptyValue($value))
					{
							
						$request .= $value;
					
						if(count($tableColonne) != $i)
							$request .= ", ";
						
						$i++;
					}
					else
						throw new Exception("Veuillez remplir tous les champs.");
				}
				$request .= ")";
			}
			if(!empty($tableValeur))
			{
				$request .= "VALUES (";
				$i = 1;
				foreach($tableValeur as &$value)
				{
					if($this->checkEmptyValue($value))
					{
						$value = $this->cleanValue($value);
						
						$request .= "'".$value."'";
							
						if(count($tableColonne) != $i)
							$request .= ", ";
							
						$i++;
					}
					else
						throw new Exception("Veuillez remplir tous les champs.");
				}
				$request .= ")";
			}
			else
				throw new Exception("Aucune donnée a été reçue, veuillez réessayer");
						
			try
			{
				$this->connexion->query($request);
			}
			catch(Exception $e)
			{
				throw new Exception("Une erreur s'est produite lors de l'enregistrement de vos données. Veuillez réessayer.");
			}
		}
		
		public function selectDb($nomTable, $tableColonne, $condition = NULL)
		{
			$request = "SELECT ";
			if(!empty($tableColonne))
			{
				$i = 1;
				foreach($tableColonne as &$value)
				{
					if($this->checkEmptyValue($value))
					{
						$request .= $value;
					
						if(count($tableColonne) != $i)
							$request .= ", ";
						
						$i++;
					}
					else
						throw new Exception("Veuillez remplir tous les champs.");
				}
				$i = 1;
				if(!empty($nomTable))
				{
					$request .= " FROM ";
					foreach($nomTable as &$value)
					{
						if($this->checkEmptyValue($value))
						{
							$request .= $value;
						
							if(count($nomTable) != $i)
								$request .= ", ";
							
							$i++;
						}
					}
				}
				else
					throw new Exception("Nom de table incorrect.");
			}
			else
				throw new Exception("Aucune donnée a été reçue, veuillez réessayer");
				
			if(!empty($condition))
				$request.= " WHERE ".$condition;
						
			try
			{
				return $this->connexion->query($request);
			}
			catch(Exception $e)
			{
				throw new Exception("Une erreur s'est produite lors de la sélection de vos données. Veuillez réessayer.");
			}
		}		
		
		public function updateDb($tableName, $column, $value, $condition = NULL)
		{
			$request = "UPDATE ";
			if(!empty($tableName) && !empty($column))
			{
				$request .= $tableName." SET ".$column." = ";
				
				if($this->checkEmptyValue($value))
					$request .= $value;
				else
					throw new Exception("La modification a échoué.");
			}
			else
				throw new Exception("Aucune donnée a été reçue, veuillez réessayer.");
			
			if(!empty($condition))
				$request.= " WHERE ".$condition;
			
			try
			{
				$this->connexion->query($request);				
			}
			catch(Exception $e)
			{
				throw new Exception("Une erreur s'est produite lors de la modification des données. Veuillez réessayer.");
			}
		}		
		
		public function deleteDb($tableName, $condition = NULL)
		{
			$request = "DELETE FROM ";
			if(!empty($tableName))
			{
				$request .= $tableName;
			}
			else
				throw new Exception("Aucune donnée a été reçue, veuillez réessayer.");
			
			if(!empty($condition))
				$request.= " WHERE ".$condition;
			
			try
			{
				$this->connexion->query($request);				
			}
			catch(Exception $e)
			{
				throw new Exception("Une erreur s'est produite lors de la modification des données. Veuillez réessayer.");
			}
		}
		
		private function cleanValue($value)
		{
			$valueSecured = $this->connexion->real_escape_string($value);
			$valueSecured = htmlentities($value);
			return $valueSecured;
		}
		
		private function checkEmptyValue($value)
		{
			return $value.trim("") != "";
		}
		
		//Fonction qui permet de recuperer le nom du tournoi pour renommer le fichier Excel qu'on va créer
		private function getTournamentName($idTournoi)
		{
			return $this->selectDb(array("club_tournoi"),array("nom"), "id_tournoi = '".$idTournoi."'");
		}
		
		//Fonction qui permet d'exporter la table passé en paramètre dans un fichier Excel	
		private function exportationExcel($idTournoi)
		{
			$file = 'export';
			$csv_output = "";
			$csv_output = "Nom;Prenom;Date de naissance;Mail;Numéro téléphone responsable;Numero de rue;Nom de rue;Code Postal;Ville;Club;Sexe;\n";
			$values = $this->selectDb(array("club_inscrit_pour_tournoi inscrit", "club_inscrit_tournoi tournoi"), array("nom", "prenom", "date_naiss", "mail", "num_tel_resp", "num_rue", "nom_rue", "code_postal", "ville", "club", "sexe"), "inscrit.id_inscrit_pour_tournoi = tournoi.id_inscrit_pour_tournoi AND id_tournoi='".$idTournoi."'");
			while ($rowr = mysqli_fetch_row($values))
			{
				for ($j=0;$j<11;$j++)
				{
					$csv_output .= $rowr[$j]."; ";
				}
				$csv_output .= "\n";
			}
			$filename = $file."_".date("Y-m-d_H-i",time());
			return $csv_output;
		}
	}
?>