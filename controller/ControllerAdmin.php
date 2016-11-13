<?php
require_once File::build_path(array('model', 'ModelUtilisateur.php'));
require_once File::build_path(array('model', 'ModelProduit.php'));
require_once File::build_path(array('model', 'ModelCategorie.php'));
require_once File::build_path(array('model', 'ModelCommande.php'));

class ControllerAdmin {

	protected static $object = 'admin';

	// Charge l'index de l'administration
	public static function index() {
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			$powerNeeded = ($currentUser->getPower() == Conf::$power['admin']);

			$view = 'index';
			$pagetitle = 'So\'Cap - Administration';

			$nombreUsers = ModelUtilisateur::getNombreActifsAndValid();
			$nombreProduits = count(ModelProduit::selectAll());
			$nombreCommandes = count(ModelCommande::selectAll());
			$argentTotal = (is_null(ModelCommande::getTotalMontant()) ? '0' : ModelCommande::getTotalMontant());

			require File::build_path(array('view', 'view.php'));
		} else {
			ModelUtilisateur::error('Vous ne pouvez pas accéder à cette page sans être connecté !');
		}
	}

	public static function listProduits() {
		$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
		$powerNeeded = ($currentUser->getPower() == Conf::$power['admin']);
		
		if($powerNeeded) {
			if(isset($_POST['actionP']) && !empty($_POST['actionP'])) {
				switch ($_POST['actionP']) {
					case 'updateStockProduit':
						if(isset($_POST['idProduit'], $_POST['stock'])) {
							$idProduit = strip_tags($_POST['idProduit']);
							$stock = strip_tags($_POST['stock']);
							$produit = ModelProduit::select($idProduit);
							if($produit != false) {
								if(is_numeric($stock)) {
									if($stock >= 0) {
										$checkUpdateStock = $produit->updateStock($stock);
										if($checkUpdateStock) {
											$notif = '<div class="alert alert-success">Le stock pour ce produit a bien été mis à jour !</div>';
										} else {
											$notif = '<div class="alert alert-danger">Impossible de mettre à jour le stock pour ce produit !</div>';
										}
									} else {
										$notif = '<div class="alert alert-danger">Le stock doit être >= 0 !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Le stock doit être une valeur entière !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">Le produit demandé n\'existe pas !</div>';
							}
						} else {
							$notif = '<div class="alert alert-danger">Merci de remplir correctement le formulaire !</div>';
						}
						break;
					case 'addProduit':
						if(isset($_POST['idProduit'],$_POST['label'],$_POST['categorie'],$_POST['prix'], $_POST['favori'], $_POST['stock'])) {
							$idProduit = $_POST['idProduit'];
							if($idProduit == "null") {
								$label = strip_tags($_POST['label']);
								if(!empty($label) && !ctype_space($label)) {
									$checkCategorie = ModelCategorie::select($_POST['categorie']);
									if($checkCategorie != false) {
										$idCategorie = $checkCategorie->get('idCategorie');
										$prix = strip_tags($_POST['prix']);
										if(is_numeric($prix)) {
											if($prix >= 0) {
												$stock = strip_tags($_POST['stock']);
												if(is_numeric($stock) && $stock >= 0) {
													$favorited = strip_tags($_POST['favori']);
													if($favorited != "1" && $favorited != "0") {
														$favorited = "0";
													}

													if(isset($_POST['description'])) {
														$description = strip_tags($_POST['description']);
													} else {
														$description = NULL;
													}
													
													$produit = new ModelProduit(0, $label, $idCategorie, $description, $prix, $favorited);
													$idProduitSaved = $produit->save();

													if($idProduitSaved != false) {
														$produit = ModelProduit::select($idProduitSaved);
														if($produit != false) {
															$checkUpdateStock = $produit->updateStock($stock);
															if($checkUpdateStock != false) {
																$notif = '<div class="alert alert-success">Produit ajouté avec succès !</div>';
															} else {
																$notif = '<div class="alert alert-danger">Impossible d\'initialiser le stock pour ce produit !</div>';
															}
														} else {
															$notif = '<div class="alert alert-danger">Impossible d\'ajouter ce produit !</div>';
														}
													} else {
														$notif = '<div class="alert alert-danger">Impossible d\'ajouter ce produit !</div>';
													}
												} else {
													$notif = '<div class="alert alert-danger">Le stock doit être une valeur entière >= 0 !</div>';
												}
											} else {
												$notif = '<div class="alert alert-danger">Le prix du produit doit être >= 0 !</div>';
											}
										} else {
											$notif = '<div class="alert alert-danger">Vous devez saisir un prix correct !</div>';
										}
									} else {
										$notif = '<div class="alert alert-danger">La catégorie sélectionnée n\'existe pas !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Le nom du produit ne peut être vide !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">Erreur lors de la transmission des données !</div>';
							}
						} else {
							$notif = '<div class="alert alert-danger">Merci de remplir correctement le formulaire !</div>';
						}
						// Dans le cas d'un ajout et qu'on ne veut pas resaisir TOUT le formulaire on transmet les data.
						if(isset($_POST)) {
							$dataPosted = '<script>var dataPosted = '.json_encode($_POST).'</script>';
						}
						break;
					case 'updateProduit':
						if(isset($_POST['idProduit'],$_POST['label'],$_POST['categorie'],$_POST['prix'])) {
							$idProduit = strip_tags($_POST['idProduit']);
							$produit = ModelProduit::select($idProduit);
							if($produit != false) {
								$label = strip_tags($_POST['label']);
								if(!empty($label) && !ctype_space($label)) {
									$checkCategorie = ModelCategorie::select($_POST['categorie']);
									if($checkCategorie != false) {
										$idCategorie = $checkCategorie->get('idCategorie');
										$prix = strip_tags($_POST['prix']);
										if(is_numeric($prix)) {
											if($prix >= 0) {
												if(isset($_POST['description'])) {
													$description = strip_tags($_POST['description']);
												} else {
													$description = NULL;
												}
												
												$data = array(
													'label' => $label,
													'categorieProduit' => $idCategorie,
													'description' => $description,
													'prix' => $prix,
												);

												$checkUpdateProduit = $produit->update($data);
												if($checkUpdateProduit) {
													$notif = '<div class="alert alert-success">Le produit a bien été mis à jour !</div>';
												} else {
													$notif = '<div class="alert alert-danger">Impossible de mettre à jour ce produit !</div>';
												}
											} else {
												$notif = '<div class="alert alert-danger">Le prix du produit doit être >= 0 !</div>';
											}
										} else {
											$notif = '<div class="alert alert-danger">Vous devez saisir un prix correct !</div>';
										}
									} else {
										$notif = '<div class="alert alert-danger">La catégorie sélectionnée n\'existe pas !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Le nom du produit ne peut être vide !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">Le produit demandé n\'existe pas !</div>';
							}
						} else {
							$notif = '<div class="alert alert-danger">Merci de remplir correctement le formulaire !</div>';
						}
						break;
					case 'deleteProduit':
						if(isset($_POST['idProduit'],$_POST['confirm'])) {
							$idProduit = strip_tags($_POST['idProduit']);
							$produit = ModelProduit::select($idProduit);
							if($produit != false) {
								$confirm = strip_tags($_POST['confirm']);
								if($confirm == true) {
									$checkDeleteProduit = ModelProduit::delete($produit->get('idProduit'));
									if($checkDeleteProduit) {
										$notif = '<div class="alert alert-success">Le produit a bien été supprimé !</div>';
									} else {
										$notif = '<div class="alert alert-danger">Impossible de supprimer ce produit !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Vous devez confirmer la suppression !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">Le produit demandé n\'existe pas !</div>';
							}
						} else {
							$notif = '<div class="alert alert-danger">Merci de remplir correctement le formulaire !</div>';
						}
						break;
					case 'addCategorie':
						if(isset($_POST['labelCategorie'])) {
							$labelCategorie = strip_tags($_POST['labelCategorie']);
							if(!empty($labelCategorie) && !ctype_space($labelCategorie)) {
								$checkCategorie = ModelCategorie::selectCustom('label', $labelCategorie);
								if($checkCategorie == false) {
									$newCategorie = new ModelCategorie(0, $labelCategorie);
									$checkCategorieSave = $newCategorie->save();
									if($checkCategorieSave != false) {
										$notif = '<div class="alert alert-success">Catégorie ajoutée avec succès !</div>';
									} else {
										$notif = '<div class="alert alert-danger">Impossible d\'ajouter cette catégorie, veuillez nous contacter !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Ce nom de catégorie existe déjà !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">Vous ne pouvez pas saisir un nom de catégorie vide !</div>';
							}
						} else {
							$notif = '<div class="alert alert-danger">Merci de remplir correctement le formulaire !</div>';
						}
						break;
					case 'updateCategorie':
						if(isset($_POST['idCategorie'],$_POST['label'])) {
							$idCategorie = strip_tags($_POST['idCategorie']);
							$categorie = ModelCategorie::select($idCategorie);
							if($categorie != false) {
								$label = strip_tags($_POST['label']);
								if(!empty($label) && !ctype_space($label)) {
									$data = array(
										'label' => $label,
									);

									$checkUpdateCategorie = $categorie->update($data);
									if($checkUpdateCategorie) {
										$notif = '<div class="alert alert-success">La catégorie a bien été mise à jour !</div>';
									} else {
										$notif = '<div class="alert alert-danger">Impossible de mettre à jour cette catégorie !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Le nom de la catégorie ne peut être vide !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">La catégorie demandée n\'existe pas !</div>';
							}
						} else {
							$notif = '<div class="alert alert-danger">Merci de remplir correctement le formulaire !</div>';
						}
						break;
					case 'deleteCategorie':
						if(isset($_POST['idCategorie'],$_POST['confirm'])) {
							$idCategorie = strip_tags($_POST['idCategorie']);
							$categorie = ModelCategorie::select($idCategorie);
							if($categorie != false) {
								$confirm = strip_tags($_POST['confirm']);
								if($confirm == true) {
									$checkDeleteCategorie = ModelCategorie::delete($categorie->get('idCategorie'));
									if($checkDeleteCategorie) {
										$notif = '<div class="alert alert-success">La catégorie a bien été supprimée !</div>';
									} else {
										$notif = '<div class="alert alert-danger">Impossible de supprimer cette catégorie, elle doit certainement contenir des produits !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Vous devez confirmer la suppression !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">La catégorie demandée n\'existe pas !</div>';
							}
						} else {
							$notif = '<div class="alert alert-danger">Merci de remplir correctement le formulaire !</div>';
						}
						break;
					default:
						break;
				}
			}
		}
		$tab_p = ModelProduit::selectAll();
		$view = 'listProduits';
		$pagetitle = 'So\'Cap - Administration - Liste des produits';
		require File::build_path(array('view', 'view.php'));
	}

	public static function changeFavori() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idProduit'],$_POST['favori'])) {
					$idProduit = strip_tags($_POST['idProduit']);
					$newFavori = strip_tags($_POST['favori']);
					$produit = ModelProduit::select($idProduit);

					if($produit != false) {
						if($produit->get('favorited') != $newFavori) {
							$data = array(
								'favorited' => $newFavori
							);
							$checkUpdate = $produit->updateFavori($newFavori);
							if($checkUpdate) {
								$retour['result'] = true;
								$retour['idProduit'] = $produit->get('idProduit');
								$retour['newFavori'] = $newFavori;
								if($newFavori == 1) {
									$retour['newIcon'] = '<i class="fa fa-star" aria-hidden="true"></i>';
								} else {
									$retour['newIcon'] = '<i class="fa fa-star-o" aria-hidden="true"></i>';
								}
								$retour['message'] = '<div class="alert alert-success">Favori mis à jour pour le produit selectionné !</div>';
							} else {
								$retour['result'] = false;
								$retour['message'] = '<div class="alert alert-danger">Impossible de mettre à jour le produit !</div>';
							}
						} else {
							$retour['result'] = false;
							$retour['message'] = '<div class="alert alert-danger">Le produit est déjà dans l\'état souhaité !</div>';
						}
					} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">Le produit n\'existe pas !</div>';
					}
				} else {
					$retour['result'] = false;
					$retour['message'] = '<div class="alert alert-danger">Vous n\'avez pas envoyé correctement les données !</div>';
				}
			} else {
				$retour['result'] = false;
				$retour['message'] = '<div class="alert alert-danger">Vous n\'avez pas les droits nécessaires pour accéder à cette page !</div>';
			}
		} else {
			$retour['result'] = false;
			$retour['message'] = '<div class="alert alert-danger">Vous devez être connecté pour accéder à cette page !</div>';
		}
		echo json_encode($retour);
	}

	// Toutes les méthodes s'appellant nameForm permettent de récupérer un formulaire dans la modal qui est géré plus haut par le controller

	public static function stockForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idProduit'])) {
					$idProduit = strip_tags($_POST['idProduit']);
					$produit = ModelProduit::select($idProduit);

					if($produit != false) {
						$form = '<form method="POST" role="form">
							<div class="form-group">
								<label for="stockActual">Stock pour le produit <u>'.strip_tags($produit->get('label')).'</u></label>
								<input type="text" name="stock" autocomplete="off" id="stockActual" class="form-control" value="'.$produit->getStock().'" placeholder="Nombre de produits en stock" />
							</div>
							<input type="hidden" name="idProduit" value="'.$produit->get('idProduit').'">
							<input type="hidden" name="actionP" value="updateStockProduit">
							<button type="submit" class="btn btn-success">Modifier</button>
						</form>';
						$retour['result'] = true;
						$retour['message'] = $form;
	 				} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">Le produit n\'existe pas !</div>';
					}
				} else {
					$retour['result'] = false;
					$retour['message'] = '<div class="alert alert-danger">Vous n\'avez pas envoyé correctement les données !</div>';
				}
			} else {
				$retour['result'] = false;
				$retour['message'] = '<div class="alert alert-danger">Vous n\'avez pas les droits nécessaires pour accéder à cette page !</div>';
			}
		} else {
			$retour['result'] = false;
			$retour['message'] = '<div class="alert alert-danger">Vous devez être connecté pour accéder à cette page !</div>';
		}
		echo json_encode($retour);
	}
}

?>