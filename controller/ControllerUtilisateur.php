<?php
require_once File::build_path(array('model', 'ModelUtilisateur.php'));

class ControllerUtilisateur {

	protected static $object = 'utilisateur';

   // Affichage du formulaire de connexion
   public static function connect() {
      $view = 'connexion';
      $pagetitle = 'So\'Cap - Se connecter';
      $powerNeeded = !self::isConnected();
      require File::build_path(array('view', 'view.php'));
   }

   // Essaye de connecter un utilisateur
   public static function connected() {
      $titlePage = "Se connecter";
      if(!self::isConnected()) {
         if(isset($_POST['email'], $_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
            $email = strip_tags($_POST['email']);
            $password = strip_tags($_POST['password']);

            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
               $checkUser = ModelUtilisateur::selectCustom('email', $email);
               if($checkUser != false) {
                  if($checkUser[0]->get('nonce') == NULL) {
                     if(password_verify($password, $checkUser[0]->get('password'))) {
                        $_SESSION['login'] = true;
                        $_SESSION['idUser'] = $checkUser[0]->get('idUtilisateur');
                        $message = 'Connexion réalisée avec succès !';
                        $urlRedirect = 'index.php';
                        $view = 'success_action';
                        $pagetitle = 'So\'Cap - Connexion réussie';
                        $powerNeeded = self::isConnected();
                        require File::build_path(array('view', 'view.php'));
                     } else {
                        self::errorForm('Le mot de passe est incorrect', 'connexion', $titlePage);
                     }
                  } else {
                     self::errorForm('Vous devez valider votre adresse email en cliquant sur le lien qui vous a été envoyé par mail', 'connexion', $titlePage);
                  }
               } else {
                  self::errorForm('Cette adresse e-mail ne correspond à aucun compte !', 'connexion', $titlePage);
               }
            } else {
               self::errorForm('L\'adresse e-mail renseignée est invalide !', 'connexion', $titlePage);
            }
         } else {
            ControllerDefault::error('Vous devez renseigner tous les champs !');
         }
      } else {
         ControllerDefault::error('Vous êtes déjà connecté !');
      }
   }

   // Essaye de déconnecter un utilisateur
   public static function disconnect() {
      if(self::isConnected()) {
         unset($_SESSION['login']);
         unset($_SESSION['idUser']);
         $message = 'Déconnexion réalisée avec succès !';
         $urlRedirect = 'index.php';
         $view = 'success_action';
         $pagetitle = 'So\'Cap - Déconnexion';
         $powerNeeded = !self::isConnected();
         require File::build_path(array('view', 'view.php'));
      } else {
         ControllerDefault::error('Vous n\'êtes pas connecté, impossible de vous déconnecter !');
      }
   }

   // Affichage du formulaire d'inscription
   public static function register() {
      $view = 'register';
      $pagetitle = 'So\'Cap - S\'inscrire';
      $powerNeeded = !self::isConnected();
      require File::build_path(array('view', 'view.php'));
   }

   // Essaye d'inscrire un utilisateur
   public static function registered() {
      $titlePage = "S'inscrire";
      if(!self::isConnected()) {
         if(isset($_POST['email'],$_POST['password'],$_POST['password_confirm'],$_POST['prenom'], $_POST['nom'])) {
            $email = strip_tags($_POST['email']);
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
               $checkUser = ModelUtilisateur::selectCustom('email', $email);
               if($checkUser == false) {
                  $password = strip_tags($_POST['password']);
                  $password_confirm = strip_tags($_POST['password_confirm']);
                  if(!empty($password) &&  !ctype_space($password)) {
                     if($password_confirm == $password) {
                        $prenom = strip_tags($_POST['prenom']);
                        if(!empty($prenom) && !ctype_space($prenom)) {
                           $nom = strip_tags($_POST['nom']);
                           if(!empty($nom) && !ctype_space($nom)) {
                              $data = array(
                                 'idUtilisateur' => NULL,
                                 'email' => $email,
                                 'password' => password_hash($password, PASSWORD_DEFAULT),
                                 'prenom' => $prenom,
                                 'nom' => $nom,
                                 'rang' => 2,
                                 'nonce' => ModelUtilisateur::generateRandomHex()
                              );
                              $resultSave = ModelUtilisateur::save($data);
                              // ENVOYER LE MAIL ICI
                              if($resultSave) {
                                 $message = 'Inscription réalisée avec succès !';
                                 $urlRedirect = 'index.php';
                                 $view = 'success_action';
                                 $pagetitle = 'So\'Cap - Inscription';
                                 $powerNeeded = !self::isConnected();
                                 require File::build_path(array('view', 'view.php'));
                              } else {
                                 self::errorForm('Impossible de vous inscrire, merci de nous contacter', 'register', $titlePage);
                              }
                           } else {
                              self::errorForm('Vous devez saisir votre nom', 'register', $titlePage);
                           }
                        } else {
                           self::errorForm('Vous devez saisir votre prénom', 'register', $titlePage);
                        }
                     } else {
                        self::errorForm('Les mots de passe ne correspondent pas', 'register', $titlePage);
                     }
                  } else {
                     self::errorForm('Vous ne pouvez pas avoir un mot de passe vide !', 'register', $titlePage);
                  }
               } else {
                  self::errorForm('Cette adresse e-mail est déjà inscrite sur notre site', 'register', $titlePage);
               }
            } else {
               self::errorForm('Vous devez saisir une adresse e-mail valide !', 'register', $titlePage);
            }
         } else {
            ControllerDefault::error('Merci de saisir tous les champs !');
         }
      } else {
         ControllerDefault::error('Vous ne pouvez pas vous inscrire en étant déjà connecté !');
      }
   }

   // Valide l'adresse e-mail après une inscription
   public static function validate() {
      $titlePage = 'Valider son inscription';
      if(isset($_GET['key'], $_GET['email'])) {
         $key = strip_tags($_GET['key']);
         $email = strip_tags($_GET['email']);
         if(!empty($key) && !empty($email)) {
            $checkUser = ModelUtilisateur::selectCustom('email', $email);
            if($checkUser != false) {
               $user = $checkUser[0];
               if($key == $user->get('nonce')) {
                  $checkUpdate = $user->validate();
                  if($checkUpdate) {
                     $message = 'Validation de votre e-mail réalisée avec succès !';
                     $urlRedirect = 'index.php';
                     $view = 'success_action';
                     $pagetitle = 'So\'Cap - Validation de l\'adresse e-mail';
                     $powerNeeded = !self::isConnected();
                     require File::build_path(array('view', 'view.php'));
                  } else {
                     ControllerDefault::error('Impossible de valider votre adresse e-mail, veuillez nous contacter');
                  }
               } else {
                  ControllerDefault::error('Cette clé de validation est invalide !');
               }
            } else {
               ControllerDefault::error('Ce mail n\'est pas inscrit sur notre site !');
            }
         } else {
            ControllerDefault::error('Impossible de valider sans recevoir les données');
         }
      } else {
         ControllerDefault::error('Impossible de valider sans recevoir les données');
      }
   }

   public static function profil() {
      $view = 'profil';
      $pagetitle = 'So\'Cap - Votre Profil';
      $powerNeeded = self::isConnected();
      $user = ModelUtilisateur::select($_SESSION['idUser']);
      if($user != false) {
         require File::build_path(array('view', 'view.php'));
      } else {
         ControllerDefault::error('Votre profil n\'existe pas !');
      }
   }

   public static function updateProfil() {
      if(isset($_POST['email'], $_POST['prenom'], $_POST['nom'])) {
         $email = strip_tags($_POST['email']);
         $prenom = strip_tags($_POST['prenom']);
         $nom = strip_tags($_POST['nom']);

         if(isset($_POST['password'], $_POST['password_confirm'])) {
            $newPassword = $_POST['password'];
            $newPasswordConfirm = $_POST['password_confirm'];
         } else {
            $newPassword = false;
            $newPasswordConfirm = false;
         }
         if(!empty($prenom) && !ctype_space($prenom)) {
            if(!empty($nom) && !ctype_space($nom)) {
               if(!empty($email) && !ctype_space($email)) {
                  if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                     $currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
                     if($currentUser != false) {
                        $data = array(
                           'email' => $email,
                           'prenom' => $prenom,
                           'nom' => $nom,
                           'idUtilisateur' => $currentUser->get('idUtilisateur')
                        );
                        if($newPassword != false && $newPasswordConfirm != false) {
                           if($newPassword == $newPasswordConfirm) {
                              $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                              $data['password'] = $newPassword;
                           } else {
                              ControllerDefault::error('Le mot de passe et sa confirmation doivent être identiques !');
                              return;
                           }
                        }
                        $checkUpdateProfil = ModelUtilisateur::update_gen($data, 'idUtilisateur');
                        if($checkUpdateProfil) {
                           $message = 'Profil mis à jour avec succès !';
                           $urlRedirect = 'index.php?controller=utilisateur&action=profil';
                           $view = 'success_action';
                           $pagetitle = 'So\'Cap - Mise à jour du profil';
                           $powerNeeded = self::isConnected();
                           require File::build_path(array('view', 'view.php'));
                        } else {
                           ControllerDefault::error('Impossible de mettre à jour votre profil, veuillez nous contacter !');
                        }
                     } else {
                        ControllerDefault::error('Impossible de retrouver votre profil, veuillez nous contacter !');
                     }
                  } else {
                     ControllerDefault::error('Votre e-mail doit être dans un format correct !');
                  }
               } else {
                  ControllerDefault::error('Vous devez saisir votre e-mail !');
               }
            } else {
               ControllerDefault::error('Vous devez saisir votre nom !');
            }
         } else {
            ControllerDefault::error('Vous devez saisir votre prénom !');
         }
      } else {
         ControllerDefault::error('Vous devez renvoyer tous les champs obligatoires !');
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

   /*public static function checkRang($rang) {
      if(self::isConnected()) {
         $userConnected = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser']);
         if($userConnected != false) {
            $user = $userConnected[0];
            return $user->getInfosRang();
         } else {
            return 0;
         }
      } else {
         return 0;
      }
   }*/

   public static function errorForm($error, $view, $titlePage) {
      $displayError = $error;
      $view = $view;
      $pagetitle = 'So\'Cap - '.$titlePage;
      $powerNeeded = true;
      require File::build_path(array('view', 'view.php'));
   }

}
?>