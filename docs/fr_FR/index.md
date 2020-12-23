# Présentation 
 

Ce plugin vous permet de communiquer avec vos appareils Android (Telephone, TV, shield,etc…) via le service Join TASKER.
Vous pourrez ainsi envoyer des notifications, ouvrir des applications, envoyer un sms depuis le téléphone ou même lancer un appel.

# Installation du plugin 
Dans Jeedom, aller dans "plugin"/"gestion des plugins", trouver Jointasker et activez le

# Configuration des équipements 
### Installation de Join Tasker sur votre téléphone :
Utilisation du lien : https://play.google.com/store/apps/details?id=com.joaomgcd.join
Une fois installé, aller sur la page web de join tasker https://joinjoaomgcd.appspot.com/ 
Choisir votre équipement dans la liste puis « JOIN API » et « Show », récupéré les informations du DeviceID et de l’API Key pour plus tard

![Getting Started](/docs/assets/images/JoinWeb.jpg)

### Configuration d’un équipement dans Jeedom : 
Faire « Ajouter » et positionner les informations de Clé Join et DeviceID récupérer précédemment dans l’équipement. 
L’icône est le lien de l’icône qui apparait de la notification sur le téléphone

![Getting Started](/docs/assets/images/JoinEquipement.jpg)

# Utilisation de l’équipement
Dans le dashboard, on retrouve 4 possibilités :
-	Envoyer un sms : rentrer le numéro de la personne à joindre et le texte du sms
-	Notification : Titre de la notification et le message
-	Ouvrir App : Ouvre l’application (Netflix, Youtube, etc…)
-	Téléphone : Lancement automatiquement une communication vers le numéro choisi
