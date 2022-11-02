# mmilan


SETUP 

1. Se connecter à phpmyadmin. 
	Créer une base de donnée avec encodage utf-8_unicode !
	importer le fichier dump mmilan.sql.

2. UTILISER LE SMTP GMAIL EXISTANT
	éditer table settings avec les valeurs correspondantes.
	
	- Requêtes pour modifier les parametres smtp gmail
	UPDATE `settings` SET `SettingsValue` = 'smtp.gmail.com' WHERE `settings`.`SettingsName` = 'instance_email_host';
	UPDATE `settings` SET `SettingsValue` = '587' WHERE `settings`.`SettingsName` = 'instance_email_port';
	UPDATE `settings` SET `SettingsValue` = 'mmilan.tln@gmail.com' WHERE `settings`.`SettingsName` = 'instance_email_username';
	UPDATE `settings` SET `SettingsValue` = 'MMILAN2022' WHERE `settings`.`SettingsName` = 'instance_email_password';

	
3. changer url du projet (toujours dans la table settings)
	- instance_url ( par ex http://localhost )
	
	
	
4. Copier le fichier /app/config_sample.php en tant que /app/config.php et NE PAS modifier le fichier sample.
5. Modifier le fichier /app/config.php  : 
	- DB_SERVER	url mysql
	- DB_USERNAME	username mysql		
	- DB_PASSWORD	password mysql
	- DB_NAME	database name
	


6. (facultatif) Une fois le dév démarré, vous pouvez ajouter votre nom aux crédits.
	
  
  
/!\  ATTENTION  /!\
Lors d'un push, merci de NE PAS push le fichier /app/config.php contenant vos identifiants de connexion sql. Merci !
