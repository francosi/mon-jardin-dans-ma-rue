<?php
error_reporting(-1);
ini_set("display_errors", 1);


class AuthController extends Zend_Controller_Action
{

    public function init()//initialisation des variable
    {
        $this->_helper->layout->setLayout('auth');// on fixe le layout désiré
    	Zend_Layout::startMvc();

    	$greenId = $this->_getParam('greenpodid');// on récupere l'id du pot dans l'url
//     	if($greenId == "")// si elle est vide
//     	{
//     		echo '<script>alert("l\'adresse n\'est pas valable")</script>';// on affiche un message d'alerte 
//     	}
    	Zend_Layout::getMvcInstance()->assign('greenId', $greenId);// on envoie la variable dans le layout
    }

    public function indexAction()// vue index
    {
    	$greenId = $this->_getParam('greenpodid');
    	$this->view->greenId = $greenId;// on recupere l'id du pot et on l'envoie dans la vue
        //$this->_redirect('auth/login');
    }
    
    public function adorerAction()
    {
    	$greenId = $this->_getParam('greenpodid');
    	$this->view->greenId = $greenId;// on recupere l'id du pot et on l'envoie dans la vue
    	//$this->_redirect('auth/login');
    }

    public function selfieAction()
    {

    	$greenId = $this->_getParam('greenpodid');
    	$this->view->greenid = $greenId;// on recupere l'id du pot et on l'envoie dans la vue
    }
    

    public function albumAction()
    {

    	$greenId = $this->_getParam('greenpodid');
    	$this->view->greenid = $greenId;// on recupere l'id du pot et on l'envoie dans la vue
    }
    
    
    public function loginAction()
    {

    	$greenId = $this->_getParam('greenpodid');
    	$this->view->greenid = $greenId;// on recupere l'id du pot et on l'envoie dans la vue
    	
    	if($greenId != "")
    	{
	    	$potModel = new Application_Model_Pots();
	    	$greenpod = $potModel->getPotById($greenId);// on récupere dans la bdd le pot avec son id
	    	$this->view->greenpod = $greenpod;// on l'envoie sur la vue    	
    	}
    	
        $db = $this->_getParam('db');// on recupere les information de la bdd
        $this->view->title = "Connexion";  
        $form = new Application_Form_Auth($_POST);  
       

        if ($this->_request->isPost()) {// si une requete a été posté
        	$formData = $this->_request->getPost();// on stock les données envoyées dans $formData
        	
//////////////////////////////////////// connection user//////////////////////////////////////////////////////////        	
	        if (!empty($_POST) && isset($_POST['login']) && isset($_POST['password'])) { // si on recoit le login et le mot de passe
	        	$adapter = new Zend_Auth_Adapter_DbTable(
	        			$db,
	        			'utilisateur',
	        			'mail',
	        			'mdp'
	        	);// on initialise le systeme d'authentification
	        	
	        	$adapter->setIdentity($_POST['login']);// on fixe l'identité
	        	$adapter->setCredential(md5($_POST['password']));// on fixe le mot de passe
	        	
	        	$auth = Zend_Auth::getInstance();
	        	$result = $auth->authenticate($adapter);// tentative d'authentification
	        	
	        	if ($result->isValid()) {// si l'auth a marché
	        		$storage = $auth->getStorage ();
	        		$storage->write ( $adapter->getResultRowObject ( null, 'password' ) );
	        		
	        		
	        		$userModel = new Application_Model_Utilisateur();
	        		$currentUser = $userModel->getUserId($_POST['login'], md5($_POST['password']));// on recupere l'utilisateur concerné
	        		
	        		if( $greenId != "")
	        		{
	        			
		        		$potModel = new Application_Model_Pots();
		        		$pot = $potModel->getPotById($greenId);// on recupere le pot concerné 
		        		$potModel->updatePotWithPlanUser($greenId,$pot[0]['plante_id'],$_POST['latitude'],$_POST['longitude']);
	        		

		        		$potUtilisateurModel = new Application_Model_UtilisateurPots();
		        		$userPot = $potUtilisateurModel->getPotById($greenId, $currentUser[0]['utilisateur_id']);
		        		if(count($userPot)<1)
		        		{
			        		$row = $potUtilisateurModel->createRow();// création de la liaison 
			        		$row->utilisateur_id = $currentUser[0]['utilisateur_id'];
			        		$row->pot_id = $greenId;
	
			        		$idpotUtilisateur = $row->save();// on ajoute un nouvelle liaison pot utilisateur dans la bdd
		        		}
		        	}
	        		// on met a jour le pot avec la latitude, longitude,utilisateur_id, et plante_id
	        		$this->_helper->redirector ( 'index', 'index' );// on redirige vers la partie connecté
	        	} else {
	        		$this->view->message = 'Il n\'existe pas d\'utilisateur avec ce mot de passe' ;
	        	}
	  		}

//////////////////////////////////////inscription user/////////////////////////////////////////////////////////////////
  			
  			 
  		}
  		
  		
////////////////////////////////////////connection RS/////////////////////////////////////////////  		
  		if(isset($_REQUEST["provider"]))// si on recoit la variable provider dans l'url
  		{
  			 
  			//On appelle la librairie
  			$config = "/home/monjardigu/www/webapp/API/hybridauth/config.php";
  			require_once( "/home/monjardigu/www/webapp/API/hybridauth/Hybrid/Auth.php" );
  			require_once( "/home/monjardigu/www/webapp/API/hybridauth/Hybrid/Endpoint.php" );// on integre les fichier necessaire a l'authentification facebook
  			
	  		if (isset($_REQUEST['hauth.start']) || isset($_REQUEST['hauth_done']))
			{
			    Hybrid_Endpoint::process();
			}
  			//Initialisation
  			try{$hybridauth = new Hybrid_Auth( $config );// instanciation de la class hybrid_auth
  			//On  affecte le provider
  			$provider = @ trim( strip_tags( $_GET["provider"] ) );
  			// On tente une authentification
  			$adapter = $hybridauth->authenticate( $provider );// on authentifie l'utilisateur 
  			$adapter = $hybridauth->getAdapter( $provider );// on recupere l'adapter une fois authentifié
  			//On récupère les informations du profile
  			$user_data = $adapter->getUserProfile();// on recupere les données du profil
  			
  			/* les variables sont stockées dans $user_data.
  			 On interroge notre base de données pour voir si l'adresse email($user_data->email) est déjà attachée à un compte*/
  			if($user_data)//Si le compte existe on authentifie
  			{
  			//Création des variables de session
	  			//echo'<script>alert("'.$user_data->firstName.'");</script>';
	  			$userModel = new Application_Model_Utilisateur();
	  			$currentUser = $userModel->getUserId($user_data->email, md5($user_data->lastName.$provider.$user_data->firstName));
	  			// on recupere un utilisateur avec les données
	  			if(count($currentUser) < 1)// si il n'existe pas dans notre bdd
	  			{
		  			$utilisateur = new Application_Model_Utilisateur();
		  			$row = $utilisateur->createRow();
		  				
		  			$row->nom = $user_data->lastName;
		  			$row->prenom = $user_data->firstName;
		  			$row->mail = $user_data->email;
		  			$row->mdp = md5($user_data->lastName.$provider.$user_data->firstName);
		  			$row->flag = $provider;
		  			
		  			$iduser = $row->save();// on ajoute un nouvelle utilisateur dans la bdd
	  			}
	  			
	  			$adapterC = new Zend_Auth_Adapter_DbTable(
	  					$db,
	  					'utilisateur',
	  					'mail',
	  					'mdp'
	  			);// on initialise le systeme d'authentification
	  			
	  			$adapterC->setIdentity($user_data->email);// on fixe l'identité
	  			$adapterC->setCredential(md5($user_data->lastName.$provider.$user_data->firstName));// on fixe le mot de passe
	  			
	  			$auth = Zend_Auth::getInstance();
	  			$result = $auth->authenticate($adapterC);// on authentifie
	  			
	  			
	  			if ($result->isValid()) {// si l'authentification a marché
	  				
	  				$storage = $auth->getStorage ();
	  				$storage->write ( $adapterC->getResultRowObject ( null, 'password' ) );
	  				$adapter->logout();// on déconnecte l'adapter

	  				
	  				if( $_REQUEST['id'] != "none")
	  				{

		  				$potModel = new Application_Model_Pots();
		  				$pot = $potModel->getPotById($greenId);// on recupere le pot concerné
		  				$potModel->updatePotWithPlanUser($greenId,$pot[0]['plante_id'],$_REQUEST['lat'],$_REQUEST['long']);
		  				 
		  				$currentUser = $userModel->getUserId($user_data->email, md5($user_data->lastName.$provider.$user_data->firstName));
		  				
		  				$potUtilisateurModel = new Application_Model_UtilisateurPots();
		  				$userPot = $potUtilisateurModel->getPotById($_REQUEST['id'], $currentUser[0]['utilisateur_id']);
		  				if(count($userPot)<1)
		  				{
			  				$row = $potUtilisateurModel->createRow();// création de la liaison
			  				$row->utilisateur_id = $currentUser[0]['utilisateur_id'];
			  				$row->pot_id = $_REQUEST['id'];
			  				
			  				$idpotUtilisateur = $row->save();// on ajoute un nouvelle liaison pot utilisateur dans la bdd
		  				}
	  				}
	  				 // on met a jour le pot avec les nouvelles données
	  				$this->_helper->redirector ( 'index', 'index' );// on redirige vers le mode connecté
	  			} else {
	  				$this->view->message = 'Il n\'existe pas d\'utilisateur avec ce mot de passe' ;
	  			}
  			
  			}
  			else
  			{
  			/*Sinon on redirige le visiteur vers le formulaire d'inscription en récupérant au préalable les données qui nous intéressent en vue de pré-remplir les champs*/
  			header('location:/inscription.php?email='.$user_data->email);// sinon on redirige vers la page d'inscription
  			exit;
  			}
  			}
catch( Exception $e ){
  		
  					// In case we have errors 6 or 7, then we have to use Hybrid_Provider_Adapter::logout() to
  					// let hybridauth forget all about the user so we can try to authenticate again.
  					// Display the recived error,
  					// to know more please refer to Exceptions handling section on the userguide
  					switch( $e->getCode() ){
  					case 0 : echo "Unspecified error."; break;
  					case 1 : echo "Hybriauth configuration error."; break;
  					case 2 : echo "Provider not properly configured."; break;
  					case 3 : echo "Unknown or disabled provider."; break;
  					case 4 : echo "Missing provider application credentials."; break;
  					case 5 : echo "Authentication failed. "
  							. "The user has canceled the authentication or the provider refused the connection.";
  									case 6 : echo "User profile request failed. Most likely the user is not connected "
                  . "to the provider and he should to authenticate again.";
  									$adapter->logout();
  									break;
        case 7 : echo "User not connected to the provider.";
  											$adapter->logout();
               break;
    }
    echo "
  		
  									<b>Original error message:</b> " . $e->getMessage();
  									echo "<hr /><h3>Trace</h3> <pre>" . $e->getTraceAsString() . "</pre>";
  					}
  					}
  					
        $this->view->form = $form; // on envoie le formulaire d'authentification sur la vue
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance ();// on recupere la personne authentifier
        if ($auth->hasIdentity ()) {// si un utilisateur est identifié
            $auth->clearIdentity(); // on le déconnecte
            $this->_helper->redirector ( 'index', 'index' );// on le redirige vers le mode non connecté
        }  
            
    }

    public function preDispatch()
    {
         // Gestion de l'état de connexion de l'utilisateur
        if (Zend_Auth::getInstance ()->hasIdentity ()) {     
             
            
            if ('logout' != $this->getRequest ()->getActionName ()) {
                $this->_helper->redirector ( 'index', 'index' );
            }
        } else {
            if ('logout' == $this->getRequest ()->getActionName ()) {
                $this->_helper->redirector ( 'index' );
            }
        }
        
    }
    
    public function inscriptionAction()
    {

    	$db = $this->_getParam('db');
    	$greenId = $this->_getParam('greenpodid');
    	$this->view->greenid = $greenId;// on envoie l'id du pot vers la vue
    	 
    	$potModel = new Application_Model_Pots();
    	$greenpod = $potModel->getPotById($greenId);
    	$this->view->greenpod = $greenpod;// on envoie le pot vers la vue
    	
    	
    	if ($this->_request->isPost()) {
    		$formData = $this->_request->getPost();// on recupere les données envoyer 
    		
    	if(isset($_POST['mdp']) && isset($_POST['cmdp']))// si on recoit mdp er cmdp
  			{
  				if($_POST['mdp'] == $_POST['cmdp'])// si mdp = cmdp
  				{
  					$utilisateur = new Application_Model_Utilisateur();
  					$ExistUser = $utilisateur->getUserMail($_POST['mail']);
  					
  					if(count($ExistUser)==0)
  					{
	  					try{
	  						$row = $utilisateur->createRow();
	  			    
	  						$row->nom = $_POST['nom'];
	  						$row->prenom = $_POST['prenom'];
	  						$row->mail = $_POST['mail'];
	  						$row->mdp = md5($_POST['mdp']);
	  						$row->flag = 'greencityzen';
	  						
	  							$iduser = $row->save();// on créer un nouvelle utilisateur
	
	  							if($greenpod[0]['plante_id'] == NULL)// si aucune plante n'est attaché 
	  							{
	  								
	  								
	  								try{
	  								
	  									
	  									$potModel = new Application_Model_Pots();
						        		$pot = $potModel->getPotById($greenId);//
						        		$potModel->updatePotWithPlanUser($greenId,$pot[0]['plante_id'],$_POST['latitude'],$_POST['longitude']);
	  								
	
						        		$potUtilisateurModel = new Application_Model_UtilisateurPots();
						        		$userPot = $potUtilisateurModel->getPotById($greenId, $iduser);
						        		if(count($userPot)<1)
						        		{
						        			$row = $potUtilisateurModel->createRow();// création de la liaison
						        			$row->utilisateur_id = $iduser;
						        			$row->pot_id = $greenId;
						        		
						        			$idpotUtilisateur = $row->save();// on ajoute un nouvelle liaison pot utilisateur dans la bdd
						        		}
	  								}// on met a jour le pot concerné
	  								
	  								catch (Exception $e) {
			  							throw new Exception("update déconne");
			  						}
	  							}
	  							
	  							else // si une plante est attaché
	  							{
				  								
					        		$potModel = new Application_Model_Pots();
					        		$pot = $potModel->getPotById($greenId);//
					        		$potModel->updatePotWithPlanUser($greenId,$pot[0]['plante_id'],$_POST['latitude'],$_POST['longitude']);
					        		// on met a jour le pot
					        		
					        		$potUtilisateurModel = new Application_Model_UtilisateurPots();
					        		$userPot = $potUtilisateurModel->getPotById($greenId, $iduser);
					        		if(count($userPot)<1)
					        		{
					        			$row = $potUtilisateurModel->createRow();// création de la liaison
					        			$row->utilisateur_id = $iduser;
					        			$row->pot_id = $greenId;
					        		
					        			$idpotUtilisateur = $row->save();// on ajoute un nouvelle liaison pot utilisateur dans la bdd
					        		}
	  							}
	  							
	  							$message = '
	 '.$_POST['prenom'].', tu peux sourire, il ne te reste plus qu\'un clic pour finaliser ton inscription 
	 à la communauté des Jardineurs:
	
			http://www.monjardindansmarue.fr/webapp/index/map
	
	------ Pour Mémoire ------  									
	Identifiant: '.$_POST['mail'].'
	Mot de passe: il va falloir manger du poisson pour t\'en souvenir
	
	                        ';// creation du mail
	  							
	  							$mail = new Zend_Mail('UTF-8');
	  							$mail->setBodyText($message);
	  							$mail->setFrom('coucou@greencityzen.fr', 'Mon jardin dans ma rue');// emetteur
	  							$mail->addTo($_POST['mail'], $_POST['nom']);// destinataire
	  							$mail->setSubject('Confirmer l\'inscription de ton GreenPod');// objet du mail
	  							$ret = $mail->send();// envoie du mail
	  							
	  							$adapter = new Zend_Auth_Adapter_DbTable(
	  									$db,
	  									'utilisateur',
	  									'mail',
	  									'mdp'
	  							);// on initialise l'authentification
	  							 
	  							$adapter->setIdentity($_POST['mail']);// on fixe l'identité
	  							$adapter->setCredential(md5($_POST['mdp']));// on fixe le mot de passe
	  							 
	  							$auth = Zend_Auth::getInstance();
	  							$result = $auth->authenticate($adapter);// on authentifie
	  							 
	  							if ($result->isValid()) {// si l'authentification a marché
	  								$storage = $auth->getStorage ();
	  								$storage->write ( $adapter->getResultRowObject ( null, 'password' ) );
	  								$this->_helper->redirector ( 'profil', 'index' );// on redirige vers le mode connecté
	  							} else {
	  								$this->view->message = 'Il n\'existe pas d\'utilisateur avec ce mot de passe' ;
	  							}
	  							 
	  						
	  						
	  					
	  					}
	  					catch (Exception $e) {
	  						$this->view->message = $e->getMessage();
	  						
	  					}
  					}
  					else 
  					{
  						echo '<script>alert("L\'adresse email est déjà enregistré");</script>';
  					}
  				}
  			}
    	}
    }
    
    
    public function mdplooseAction()
    { 
        $this->view->title = "Mot de passe ou identifiant perdu";  
        $form = new Application_Form_Mdploose($_POST);  // declaration du formulaire de pert de mot de passe
        $this->view->message = '' ;       
        
         //Récupération du mail du site
        $datas_site = new Zend_Config_Ini(APPLICATION__INI_PATH, 'datas_site');   
        $site_mail = $datas_site->site->mail; // on recupere notre email
        
        if ($this->_request->isPost()) {// si une requete a été posté
            $formData = $this->_request->getPost();  
                
            try{
                 //Vérifier que les données sont valides
                if (!$form->isValid($formData)) throw new Exception("Vérifiez les données que vous avez saisies"); 
               
                 //Rechercher un gestionnaire correspondant au mot clé 
                $utilisateur = new Application_Model_Utilisateur(); 
                $the_gestionnaire = $utilisateur->getUserMail($form->getValue('keyword'));                     
                
                 //Si le gestionnaire existe => générer nouveau mdp et envoyer par mail
                if( !empty($the_gestionnaire) ){
                
                     //Générer mot de passe
                    $chrs = 8;
                		$chaine = ""; 
                		$list = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                		mt_srand((double)microtime()*1000000);
                		$newstring="";
                
                		while( strlen( $newstring )< $chrs ) {
                
                			$newstring .= $list[mt_rand(0, strlen($list)-1)];
                
                		}
                    
                      
                    try{    
                		$utilisateur->updateUserWithMail($form->getValue('keyword'), md5($newstring));// on met a jour l'utilisateur avec les nouvelles données
                        $this->view->message = 'Modification réussie';   
                        $saved = true; 
                    }catch (Exception $e) {   
                        $saved = false; 
                        throw new Exception("Echec de modification du mot de passe");  
                    } 
                    
                     //Si la modification a réussie => Envoyer mail de confirmation avec ident et mdp
                    if($saved){
                        $message = '
 Voici vos identifiants de connexion: 
Identifiant: '.$the_gestionnaire[0]['mail'].'
Mot de passe: '.$newstring.'                         
                        ';
                                        
                        $mail = new Zend_Mail();
                        $mail->setBodyText($message);
                        $mail->setFrom('projet.cityzen@gmail.com', 'Greencitzen');
                        $mail->addTo('tedpint@hotmail.fr', $the_gestionnaire[0]['prenom']);
                        $mail->setSubject('Vos nouveaux identifiants de connexion');
                        $ret = $mail->send();  
                        
                        if($ret) $this->view->message = 'Vos nouveaux identifiants vous ont été envoyés par email.';   
                        else $this->view->message = 'Echec de l\'envoi du mail, veuillez contacter l\'administrateur du site.';                       
                    
                    }
                
                }else $this->view->message = 'Aucun utilisateur avec cet identifiant ou cet email n\'a été trouvé.';    
                
                
                //echo '<pre>'; print_r($the_gestionnaire); echo '</pre>';                 
                
                
            } catch (Exception $e) {
                $this->view->message = $e->getMessage();
                $form->populate($formData); 
            } 
            
        }    
        
        $this->view->form = $form; // on envoie le formulaire vers la vue
    }


}









