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
			$requete = "INSERT into ".$nomTable;
			if(!empty($tableColonne))
			{
				$requete .= " (";
				$i = 1;
				foreach($tableColonne as &$value)
				{
					if($this->checkEmptyValue($value))
					{
							
						$requete .= $value;
					
						if(count($tableColonne) != $i)
							$requete .= ", ";
						
						$i++;
					}
					else
						throw new Exception("Veuillez remplir tous les champs.");
				}
				$requete .= ")";
			}
			if(!empty($tableValeur))
			{
				$requete .= "VALUES (";
				$i = 1;
				foreach($tableValeur as &$value)
				{
					if($this->checkEmptyValue($value))
					{
						$value = $this->cleanValue($value);
						
						$requete .= "'".$value."'";
							
						if(count($tableColonne) != $i)
							$requete .= ", ";
							
						$i++;
					}
					else
						throw new Exception("Veuillez remplir tous les champs.");
				}
				$requete .= ")";
			}
			else
				throw new Exception("Aucune donnée a été reçue, veuillez réassayer");
						
			try
			{
				$this->connexion->query($requete);
			}
			catch(Exception $e)
			{
				throw new Exception("Une erreur s'est produite lors de l'enregistrement de vos données. Veuillez réassayer.");
			}
		}
		
		public function selectDb($nomTable, $tableColonne, $condition = NULL)
		{
			$requete = "SELECT ";
			if(!empty($tableColonne))
			{
				$i = 1;
				foreach($tableColonne as &$value)
				{
					if($this->checkEmptyValue($value))
					{
							
						$requete .= $value;
					
						if(count($tableColonne) != $i)
							$requete .= ", ";
						
						$i++;
					}
					else
						throw new Exception("Veuillez remplir tous les champs.");
				}
				$requete .= " FROM ".$nomTable;
			}
			if(!empty($condition))
				$requete.= " WHERE ".$condition;
			else
				throw new Exception("Aucune donnée a été reçue, veuillez réassayer");
						
			try
			{
				return $this->connexion->query($requete);
			}
			catch(Exception $e)
			{
				throw new Exception("Une erreur s'est produite lors de l'enregistrement de vos données. Veuillez réassayer.");
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
				throw new Exception("Aucune donnée a été reçue, veuillez réassayer.");
			
			if(!empty($condition))
				$requete.= " WHERE ".$condition;
			
			try
			{
				$this->connexion->query($requete);
			}
			catch(Exception $e)
			{
				throw new Exception("Une erreur s'est produite lors de la modification des données. Veuillez réassayer.");
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
	}
?>