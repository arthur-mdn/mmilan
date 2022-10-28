# mmilan


SETUP 

1 - Se connecter à phpmyadmin. 
	Créer une base de donnée avec encodage utf-8_unicode !
	importer le fichier dump.

2 - créer (ou utiliser une existante) une configuration adresse mail et récupérer les identifiants SMTP
	éditer table settings avec les valeurs correspondantes.
	
	- instance_email_host
	- instance_email_password
	- instance_email_port
	- instance_email_username
	
3 - changer url du projet (toujours dans la table settings)
	- instance_url
	
	
	
4 - Copier le fichier /app/config_sample.php en tant que /app/config.php et NE PAS modifier le fichier sample.
5 - Modifier le fichier /app/config.php  : 
	- DB_SERVER	url mysql
	- DB_USERNAME	username mysql		
	- DB_PASSWORD	password mysql
	- DB_NAME		database name
	


6 - (facultatif) Une fois le dév démarré, vous pouvez ajouter votre nom aux crédits.
	
  
  
/!\  ATTENTION  /!\
Lors d'un push, merci de NE PAS push le fichier /app/config.php contenant vos identifiants de connexion sql. Merci !
