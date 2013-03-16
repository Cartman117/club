<?php
	class Member
	{
		private $id, $name, $firstName, $birthday, $birthplace, $birthplaceCodePostal, $city, $codePostal, $streetName, $streetNumber,
		$nomResp, $phoneNumber, $licencie, $num_licence, $sexe;

		function __construct($id)
		{
			if(is_numeric($id))
			{
				try
				{
					$connexionDatabase = Database::getInstance();
					$infosMember = $connexionDatabase->selectDb(array("club_inscrit"), 
						array("*", "DATE_FORMAT(date_naiss,'%d-%m-%Y') AS date_naiss"), "id_inscrit = ".$id);
							
					$infosMember = mysqli_fetch_assoc($infosMember);
					
					$this->id = $infosMember['id_inscrit'];
					$this->name = $infosMember['nom'];
					$this->firstName = $infosMember['prenom'];
					$this->birthday = new DateTime($infosMember['date_naiss']);
					$this->birthplace = $infosMember['ville_naiss'];
					$this->birthplaceCodePostal = $infosMember['code_postal_naiss'];
					$this->city = $infosMember['ville'];
					$this->codePostal = $infosMember['code_postal'];
					$this->streetName = $infosMember['nom_rue'];
					$this->streetNumber = $infosMember['num_rue'];
					$this->nomResp = $infosMember['nom_resp'];				
					$this->phoneNumber = $infosMember['num_tel_resp'];
					$this->licencie = $infosMember['licencie'];
					$this->num_licence = $infosMember['num_licence'];
					$this->sexe = $infosMember['sexe'];
				}
				catch(Exception $e)
				{
					throw new Exception("Impossible de récupérer le membre.");
				}
			}
			else
				throw new Exception("Impossible de récupérer le membre.");
		}
		
		function showMember()
		{
			echo"<p><b>".$this->firstName." ".$this->name."</b> est né le ".$this->birthday->format("d/m/Y")." à ".$this->birthplace."(".
			$this->birthplaceCodePostal.")<br/>";
			if($this->isMajeur())
			{
				echo"Numéro de téléphone : ".$this->phoneNumber;
			}
			else
			{
				echo"Nom du responsable : ".$this->nomResp."<br/>Numéro de téléphone du responsable : ".$this->phoneNumber;
			}
			echo"<br/>Adresse : ".$this->streetNumber.", ".$this->streetName."<br/>".$this->codePostal." ".$this->city."</p>";
		}
		
		function isMajeur()
		{
			$dateActuelle = new DateTime("now");
			$dateActuelle = $dateActuelle->setTime(0,0,0);
			return ($this->birthday->diff($dateActuelle)->y >= 18);
		}		
		
		function isLicencie()
		{
			return $this->licencie;
		}
		
		
		function validateLicence()
		{
			if(!$this->isLicencie())
			{
				$connexionDatabase = Database::getInstance();
				$connexionDatabase->updateDb("club_inscrit", "licencie", 1, "id_inscrit = ".$this->id);
			}
		}
		
		function getId()
		{
			return $this->id;
		}
		
		static function checkIsValidate($id)
		{
			if(is_numeric($id))
			{
				$connexionDatabase = Database::getInstance();
				$nbResults = mysqli_fetch_row($connexionDatabase->selectDb(array("club_inscrit"), array("COUNT(*)"), "id_compte = ".$id));
				if($nbResults[0] == 1)
					return FALSE;
				else
					return TRUE;			
			}
			else
				return FALSE;
		}

	}
?>