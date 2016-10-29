<?php
require_once File::build_path(array('model', 'ModelUtilisateur.php'));

class ControllerUtilisateur {

	protected static $object = 'utilisateur';

   public static function connect() {
      $view = 'connexion';
      $pagetitle = 'So\'Cap - Se connecter';
      require File::build_path(array('view', 'view.php'));
   }

   // Essaye de connecter un utilisateur
   public static function connected() {
      if(!self::isConnected()) {
         if(isset($_POST['email'], $_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
            $email = strip_tags($_POST['email']);
            $password = strip_tags($_POST['password']);

            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
               $checkUser = ModelUtilisateur::selectCustom('email', $email);
               if($checkUser != false) {
                  if(password_verify($password, $checkUser[0]->get('password'))) {
                     $_SESSION['login'] = true;
                     $_SESSION['idUser'] = $checkUser[0]->get('idUtilisateur');
                     $message = 'Connexion réalisée avec succès !';
                     $view = 'success_action';
                     $pagetitle = 'So\'Cap - Connexion réussie';
                     require File::build_path(array('view', 'view.php'));
                  } else {
                     ModelUtilisateur::error('Le mot de passe est incorrect');
                  }
               } else {
                  ModelUtilisateur::error('Cette adresse e-mail ne correspond à aucun compte !');
               }
            } else {
               ModelUtilisateur::error('L\'adresse e-mail renseignée est invalide !');
            }
         } else {
            ModelUtilisateur::error('Vous devez renseigner tous les champs !');
         }
      } else {
         ModelUtilisateur::error('Vous êtes déjà connecté !');
      }
   }

   // Essaye de déconnecter un utilisateur
   public static function disconnect() {
      if(self::isConnected()) {
         unset($_SESSION['login']);
         unset($_SESSION['idUser']);
         $message = 'Déconnexion réalisée avec succès !';
         $view = 'success_action';
         $pagetitle = 'So\'Cap - Déconnexion';
         require File::build_path(array('view', 'view.php'));
      } else {
         ModelUtilisateur::error('Vous n\'êtes pas connecté, impossible de vous déconnecter !');
      }
   }

   // Détermine si un utilisateur est connecté ou non
   public static function isConnected() {
      if(isset($_SESSION['login'], $_SESSION['idUser'])) {
         return true;
      } else {
         return false;
      }
   }
}
?>