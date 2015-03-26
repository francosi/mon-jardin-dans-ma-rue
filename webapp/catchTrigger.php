<?php
	$body=rawurldecode(file_get_contents("php://input")); 

	if($body != null){

		//parser le json
		$trigger = json_decode(substr($body,5));

		$id = null;
		$value = null;
		$latitude = null;
		$longitude = null;
		$date = null;

		// test si l'id a bien été renseigné
		if($trigger->{'environment'}->{'id'}!=null){
			$id = $trigger->{'environment'}->{'id'};
		}

		// test si la valeur a bien été renseigné
		if($trigger->{'triggering_datastream'}->{'value'}!=null && $trigger->{'triggering_datastream'}->{'value'}->{'current_value'}!=null){
			$value = $trigger->{'triggering_datastream'}->{'value'}->{'current_value'};
		}

		// test si la latitude et la longitude ont bien été renseignées
		if($trigger->{'environment'}!=null && $trigger->{'environment'}->{'location'}!=null){
			if($trigger->{'environment'}->{'location'}->{'lat'}!=null){
				$latitude = $trigger->{'environment'}->{'location'}->{'lat'};
			}
			if($trigger->{'environment'}->{'location'}->{'lng'}!=null){
				$longitude = $trigger->{'environment'}->{'location'}->{'lng'};
			}
		} 

		// test si la date a bien été renseignée
		if($trigger->{'triggering_datastream'}->{'at'}!=null){
			$date = $trigger->{'triggering_datastream'}->{'at'};
		}

		$typeDonnee = 1;

		// insérer les infos en bases
		insertNewValue($id, $value, $latitude, $longitude, $date, $typeDonnee);

		// récupérer les informations utilisateurs (mail)
		$users = selectUsersDuPot($id);

		// envoi du mail
		foreach ($users as $user) {
			sendMail($user['mail'],$user['nom'],$user['prenom']);
		}
	}


	function insertNewValue($potId, $value, $latitude, $longitude, $date, $typeDonnee){

		$queryString = "INSERT INTO historique(pot_id, valeur, latitude, longitude, type_valeur, date) 
							VALUES (:potId, :value, :latitude, :longitude, :typeDonnee, :date)";

		try {
		    $db = new PDO('mysql:host=10.0.244.133;dbname=greencityzen', 'root', 'greencityzen', [PDO::ATTR_PERSISTENT => true]);
		    $query = $db->prepare($queryString);
		    $query->bindParam(':potId', $potId);
		    $query->bindParam(':value', $value);
		    $query->bindParam(':date', $date);
		    $query->bindParam(':latitude', $latitude);
		    $query->bindParam(':longitude', $longitude);
		    $query->bindParam(':typeDonnee', $typeDonnee);
			$query->execute();

		    $db = null;
		} catch (PDOException $e) {
		    print "Erreur !: " . $e->getMessage() . "<br/>";
		    die();
		}
	}

	function selectUsersDuPot($potId){
		$queryString = "SELECT mail,nom,prenom FROM utilisateur u JOIN utilisateurpots up ON up.utilisateur_id=u.utilisateur_id
						WHERE up.pot_id = :potId";

		try {
		    $db = new PDO('mysql:host=10.0.244.133;dbname=greencityzen', 'root', 'greencityzen', [PDO::ATTR_PERSISTENT => true]);
		    $query = $db->prepare($queryString);
		   	$query->bindParam(':potId', $potId);
		    $query->execute();
		    $db = null;
		    $users = array();
		    while($user = $query->fetch(PDO::FETCH_ASSOC)){
		    	$users[]=$user;
		    }
		   	return $users;
		} catch (PDOException $e) {
		    print "Erreur !: " . $e->getMessage() . "<br/>";
		    die();
		}
	}

	function sendMail($mailTo, $name, $firstName){
		// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini 
 		$headers  = 'MIME-Version: 1.0' . "\r\n"; 
 		$headers .= 'Content-type: text/plain; charset=utf-8' . "\r\n"; 
  
 		// En-têtes additionnels 
 		$headers .= 'From: Mon jardin dans ma rue <no-reply@greencityzen.com>' . "\r\n"; 
 		$headers .= 'Content-Transfer-Encoding: 8bit' . "\r\n";  
  
 		return mail($mailTo, utf8_decode('Ton Greenpod a besoin d\'aide'), 
 				utf8_decode(''.$firstName.','. "\r\n".'
Mon petit doigt me dis que ton Greenpod a soif.'. "\r\n".'
Peux tu passer le voir ? '. "\r\n".''. "\r\n".'
 						
La vigie des Jardineurs'), $headers); 

	}

?>

