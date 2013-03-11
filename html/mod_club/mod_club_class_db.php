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
				$request .= " FROM ".$nomTable;
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
				throw new Exception("Une erreur s'est produite lors de l'enregistrement de vos données. Veuillez réessayer.");
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
	}
?>