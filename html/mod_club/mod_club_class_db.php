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
						throw new Exception("L'enregistrement de vos données à échoué. Veuillez réassayer.");
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
						echo $requete.'<br/>';
					}
					else
						throw new Exception("Veuillez remplir tous les champs");
				}
				$requete .= ")";
			}
			else
				throw new Exception("Aucune donnée a été reçue, veuillez réassayer");
				
			echo $requete.'<br/>';
			
			$this->connexion->query($requete) or die($this->connexion->error);
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