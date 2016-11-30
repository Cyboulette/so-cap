<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
  <?php 
    if(isset($displayError) && !empty($displayError)) {
      echo '<div class="alert alert-danger">'.$displayError.'</div>';
    }
    $email = strip_tags($user->get('email'));
    $prenom = strip_tags($user->get('prenom'));
    $nom = strip_tags($user->get('nom'));
  ?>
  <form role="form" action="index.php?controller=utilisateur&action=updateProfil" method="POST">
    <fieldset>
      <h2>Modifier votre Profil</h2>
      <hr class="colorgraph">

      <div class="form-group">
        <label for="email">Votre e-mail</label>
        <input type="email" name="email" id="email" value="<?=$email?>" class="form-control input-lg" placeholder="Saisissez votre adresse e-mail">
      </div>

      <div class="form-group">
        <label for="prenom">Votre prénom</label>
        <input type="text" name="prenom" id="prenom" value="<?=$prenom?>" class="form-control input-lg" placeholder="Saisissez votre prénom">
      </div>

      <div class="form-group">
        <label for="nom">Votre nom</label>
        <input type="text" name="nom" id="nom" value="<?=$nom?>" class="form-control input-lg" placeholder="Saisissez votre nom">
      </div>

      <div class="alert alert-info">
        Tous les champs sont obligatoires
      </div>

      <hr class="colorgraph">

      <div class="form-group">
        <label for="password">Changer votre mot de passe</label>
        <input type="password" name="password" id="password" value="<?=$password?>" class="form-control input-lg" placeholder="Saisissez votre mot de passe">
        <label for="password_confirm">Confirmer le changement de mot de passe</label>
        <input type="password" name="password_confirm" id="password_confirm" value="<?=$password_confirm?>" class="form-control input-lg" placeholder="Confirmez votre mot de passe">
      </div>

      <hr class="colorgraph">

      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <input type="submit" class="btn btn-lg btn-success btn-block" value="Modifier le profil">
        </div>
      </div>
    </fieldset>
  </form>
</div>