<?php
require_once File::build_path(array('model', 'ModelUtilisateur.php'));
require_once File::build_path(array('model', 'ModelProduit.php'));
require_once File::build_path(array('model', 'ModelCategorie.php'));
require_once File::build_path(array('model', 'ModelCommande.php'));
require_once File::build_path(array('model', 'ModelRang.php'));

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
			ControllerDefault::error('Vous ne pouvez pas accéder à cette page sans être connecté !');
		}
	}

	public static function produits() {
		$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
		$powerNeeded = ($currentUser->getPower() == Conf::$power['admin']);
		
		// Gère les actions 
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
													
													$data = array(
														'idProduit' => NULL,
														'label' => $label,
														'categorieProduit' => $idCategorie,
														'description' => $description,
														'prix' => $prix,
														'favorited' => $favorited
													);

													$idProduitSaved = ModelProduit::save($data, 'id');

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
									$data = array(
										'idCategorie' => NULL,
										'label' => $labelCategorie
									);
									$checkCategorieSave = ModelCategorie::save($data);
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
					case 'addImage':
						$image = $_FILES['image'];
						if(isset($_POST['idProduit']) && !empty($image['name'])) {
							$idProduit = strip_tags($_POST['idProduit']);
							$produit = ModelProduit::select($idProduit);
							if($produit != false) {
								$extensionsOK = array('jpg', 'jpeg', 'gif', 'png');
								$extensionUpload = strtolower(substr(strrchr($image['name'], '.'), 1));
								if (in_array($extensionUpload, $extensionsOK)) { //Si c'est la bonne extension de fichier !
									$dir = "assets/images/produits/image_upload_" . time() . ".png";
									$resultat = move_uploaded_file($image['tmp_name'], $dir);
									if($resultat) {
										$urlVisuel = str_replace("assets/images/produits/", "", $dir);
										$checkAddImage = $produit->addImage($urlVisuel);
										if($checkAddImage) {
											$notif = '<div class="alert alert-success">Visuel ajouté avec succès !</div>';
										} else {
											$testFile = "assets/images/produits/".$urlVisuel;
											if(file_exists($testFile)) {
												unlink($testFile);
											}
											$notif = '<div class="alert alert-danger">Erreur inconnue lors de le l\'enregistrement, veuillez nous contacter !</div>';
										}
									} else {
										$notif = '<div class="alert alert-danger">Impossible d\'enregistrer le visuel</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Merci d\'envoyer un visuel au format png/jpg/jpeg/gif !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">Le produit demandé n\'existe pas !</div>';
							}
						} else {
							$notif = '<div class="alert alert-danger">Merci de remplir correcteeeement le formulaire !</div>';
						}
						break;
					case 'deleteImage':
						if(isset($_POST['idVisuel'],$_POST['idProduit'],$_POST['confirm'])) {
							$idVisuel = strip_tags($_POST['idVisuel']);
							$idProduit = strip_tags($_POST['idProduit']);
							$visuel = ModelProduit::getImage($idProduit, $idVisuel);

							if($visuel != false) {
								$confirm = strip_tags($_POST['confirm']);
								if($confirm == true) {
									$urlVisuel = "assets/images/produits/".$visuel['nomImage'];
									$checkDeleteImage = ModelProduit::deleteImage($idVisuel);
									if($checkDeleteImage) {
										if(file_exists($urlVisuel)) {
											unlink($urlVisuel);
										}
										$notif = '<div class="alert alert-success">L\'image a bien été supprimée !</div>';
									} else {
										$notif = '<div class="alert alert-danger">Impossible de supprimer cette image, veuillez nous contacter !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Vous devez confirmer la suppression si vous cliquez sur le bouton "Confirmer" !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">Le visuel demandé n\'existe pas !</div>';
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

		// Affiche la vue par défaut = lister tous les produtis du site
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

	public static function editForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idProduit'])) {
					$idProduit = strip_tags($_POST['idProduit']);
					$produit = ModelProduit::select($idProduit);

					if($produit != false) {
						$categories = ModelCategorie::selectAll();
						if($categories != false) {
							$displayCategories = '';
							foreach ($categories as $categorie) {
								$idCategorie = $categorie->get('idCategorie');
								$labelCategorie = $categorie->get('label');
								$selected = ($idCategorie == $produit->get('categorieProduit') ? 'selected="selected"' : '');
								$displayCategories .= '<option '.$selected.' value="'.$idCategorie.'">'.$labelCategorie.'</option>';
							}
						}
						$form = '<form method="POST" role="form">
							<div class="form-group">
								<label for="idProduit">ID du produit</label>
								<input type="text" autocomplete="off" id="idProduit" class="form-control" value="'.$produit->get('idProduit').'" placeholder="ID du produit" disabled="yes" />
							</div>

							<div class="form-group">
								<label for="label">Libellé du produit</label>
								<input type="text" name="label" autocomplete="off" id="label" class="form-control" value="'.$produit->get('label').'" placeholder="Libellé du produit" />
							</div>

							<div class="form-group">
								<label for="categorie">Catégorie du produit</label>
								<select id="categorie" name="categorie" class="form-control">
									'.$displayCategories.'
								</select>
							</div>

							<div class="form-group">
								<label for="description">Description du produit</label>
								<textarea class="form-control" name="description" id="description" placeholder="Description du produit">'.$produit->get('description').'</textarea>
							</div>

							<div class="form-group">
								<label for="prix">Prix du produit</label>
								<input type="number" name="prix" min="0" step="any" autocomplete="off" id="prix" class="form-control" value="'.$produit->get('prix').'" placeholder="Prix du produit" />
							</div>

							<input type="hidden" name="idProduit" value="'.$produit->get('idProduit').'">
							<input type="hidden" name="actionP" value="updateProduit">

							<div class="form-group">
								<button type="submit" class="btn btn-success">Modifier</button>
							</div>

							<div class="alert alert-info text-center">
								Pour gérer le fait que ce produit apraisse ou non en "sélection" sur le site, utilisez le bouton <b>Favori (<i class="fa fa-star-o" aria-hidden="true"></i>/<i class="fa fa-star" aria-hidden="true"></i>)</b> dans la liste des produits
							</div>

							<div class="alert alert-info text-center">
								Pour gérer le stock de ce produit, utilisez le bouton <b>Stock (<i class="fa fa-gear" aria-hidden="true"></i>)</b> dans la liste des produits
							</div>
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

	public static function deleteForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idProduit'])) {
					$idProduit = strip_tags($_POST['idProduit']);
					$produit = ModelProduit::select($idProduit);

					if($produit != false) {
						$form = '<form method="POST" role="form">
							<div class="alert alert-info text-center">
								Confirmez vous la suppression du produi <b>'.strip_tags($produit->get('label')).'</b> ?
							</div>

							<input type="hidden" name="idProduit" value="'.$produit->get('idProduit').'">
							<input type="hidden" name="confirm" value="true">
							<input type="hidden" name="actionP" value="deleteProduit">

							<div class="form-group">
								<button type="submit" class="btn btn-success">Confirmer</button>
								<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Annuler">Annuler</button>
							</div>
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

	public static function addProduitForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idProduit'])) {
					$idProduit = strip_tags($_POST['idProduit']);
					if($idProduit == "null") {
						
						if(isset($_POST['dataPosted'])) {
							// Si jamais on récupère de la donnée postée précédemment (donc formulaire précédemment envoyé en erreur)
							// On la décode vu qu'elle était encodée en JSON.
							$dataPosted = json_decode($_POST['dataPosted']);
						}

						// On stocke des variables de value="" (pour l'HTML).
						$labelValue = (isset($dataPosted->label) ? strip_tags($dataPosted->label) : '');
						$descriptionValue = (isset($dataPosted->description) ? strip_tags($dataPosted->description) : '');
						$categorieValue = (isset($dataPosted->categorie) ? strip_tags($dataPosted->categorie) : '');
						$prixValue = (isset($dataPosted->prix) ? strip_tags($dataPosted->prix) : '');
						$stockValue = (isset($dataPosted->stock) ? strip_tags($dataPosted->stock) : '');
						$favoriValue = (isset($dataPosted->favori) ? strip_tags($dataPosted->favori) : '');

						$categories = ModelCategorie::selectAll();
						if($categories != false) {
							$displayCategories = '';
							foreach ($categories as $categorie) {
								$idCategorie = $categorie->get('idCategorie');
								$labelCategorie = $categorie->get('label');
								$selected = ($idCategorie == $categorieValue ? 'selected="selected"' : '');
								$displayCategories .= '<option '.$selected.' value="'.$idCategorie.'">'.$labelCategorie.'</option>';
							}

							$isFavoriYes = ($favoriValue == 1 && is_numeric($favoriValue)) ? 'checked' : '';
							$isFavoriNo = ($favoriValue == 0 && is_numeric($favoriValue)) ? 'checked' : '';

							$form = '<form method="POST" role="form">
								<div class="form-group">
									<label for="label">* Libellé du produit</label>
									<input type="text" required name="label" autocomplete="off" id="label" value="'.$labelValue.'" class="form-control" placeholder="Libellé du produit" />
								</div>

								<div class="form-group">
									<label for="categorie">* Catégorie du produit</label>
									<select id="categorie" required name="categorie" class="form-control">
										'.$displayCategories.'
									</select>
								</div>

								<div class="form-group">
									<label for="description">Description du produit</label>
									<textarea class="form-control" name="description" id="description" placeholder="Description du produit">'.$descriptionValue.'</textarea>
								</div>

								<div class="form-group">
									<label for="prix">* Prix du produit</label>
									<input type="number" required name="prix" min="0" step="any" autocomplete="off" id="prix" value="'.$prixValue.'" class="form-control" placeholder="Prix du produit" />
								</div>

								<div class="form-group">
									<label for="favoriOui">* Produit favori</label>
									<label class="radio-inline" for="favoriOui">
										<input required type="radio" name="favori" '.$isFavoriYes.' id="favoriOui" value="1"> Oui
									</label>
									<label class="radio-inline" for="favoriNon">
										<input required type="radio" name="favori" '.$isFavoriNo.' id="favoriNon" value="0"> Non
									</label>
								</div>

								<div class="form-group">
									<label for="stock">* Stock initial</label>
									<input type="number" required name="stock" min="0" step="1" autocomplete="off" id="stock" value="'.$stockValue.'" class="form-control" placeholder="Stock initial du produit" />
								</div>

								<input type="hidden" required name="idProduit" value="null">
								<input type="hidden" required name="actionP" value="addProduit">

								<div class="form-group">
									<button type="submit" class="btn btn-success">Ajouter</button>
									<button type="reset" class="btn btn-default">Réinitialiser le formulaire</button>
								</div>

								<div class="alert alert-info text-center">
									Tous les champs marqués d\'une * sont obligatoires <br/>
									En cas d\'erreur, le formulaire sera ré-rempli
								</div>
							</form>';
							$retour['result'] = true;
							$retour['message'] = $form;
						} else {
							$retour['result'] = false;
							$retour['message'] = '<div class="alert alert-danger">Aucune catégorie n\'existe pour le moment, veuillez en ajouter avant d\'ajouter des produits</div>';
						}
	 				} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">Ereur de transmission des données !</div>';
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

	public static function manageCategForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idCategorie'])) {
					$idCategorie = strip_tags($_POST['idCategorie']);
					if($idCategorie == "null") {

						$categories = ModelCategorie::selectAll();
						$formAdd = '<form method="POST" role="form">
								<div class="form-group">
									<label for="labelCategorie">Nom de la catégorie</label>
									<input id="labelCategorie" class="form-control" type="text" name="labelCategorie" placeholder="Nom de la catégorie" />
								</div>
								<div class="form-group">
									<input type="hidden" name="actionP" value="addCategorie">
									<button type="submit" class="btn btn-success">Ajouter</button>
								</div>
							</form>';
						if($categories != false) {
							$formTable = '<div class="table-responsive">
								<table class="table table-hover listProduitsTable">
									<thead>
										<tr>
											<th>ID</th>
											<th>Nom</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>';

							foreach ($categories as $categorie) {
								$idCategorie = $categorie->get('idCategorie');
								$labelCategorie = $categorie->get('label');
								$formTable .= '<tr data-categorie="'.$idCategorie.'">
									<td>'.$idCategorie.'</td>
									<td>'.$labelCategorie.'</td>
									<td>
										<btn class="btn btn-xs btn-warning actionBtn" data-action="editCategorieForm"><i class="fa fa-pencil" aria-hidden="true"></i> Editer</btn>
										<btn class="btn btn-xs btn-danger actionBtn" data-action="deleteCategorieForm"><i class="fa fa-trash" aria-hidden="true"></i> Supprimer</btn>
									</td>
								</tr>';
							}

							$formTable .= '</tbody>
								</table>
							</div>
							<script>actionBtn();</script>';

							$retour['result'] = true;
							$retour['message'] = $formAdd."<hr/>".$formTable;
						} else {
							$retour['result'] = true;
							$retour['message'] = $formAdd;
						}
	 				} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">Ereur de transmission des données !</div>';
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

	public static function editCategorieForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idCategorie'])) {
					$idCategorie = strip_tags($_POST['idCategorie']);
					$categorie = ModelCategorie::select($idCategorie);

					if($categorie != false) {
						$form = '<form method="POST" role="form">
							<div class="form-group">
								<label for="idCategorie">ID de la catégorie</label>
								<input type="text" autocomplete="off" id="idCategorie" class="form-control" value="'.$categorie->get('idCategorie').'" placeholder="ID de de la categorie" disabled="yes" />
							</div>

							<div class="form-group">
								<label for="label">Libellé de la catégorie</label>
								<input type="text" name="label" autocomplete="off" id="label" class="form-control" value="'.$categorie->get('label').'" placeholder="Libellé de la catégorie" />
							</div>

							<input type="hidden" name="idCategorie" value="'.$categorie->get('idCategorie').'">
							<input type="hidden" name="actionP" value="updateCategorie">

							<div class="form-group">
								<button type="submit" class="btn btn-success">Modifier</button>
								<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Annuler">Annuler</button>
							</div>
						</form>';
						$retour['result'] = true;
						$retour['message'] = $form;
	 				} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">La catégorie demandée n\'existe pas !</div>';
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

	public static function deleteCategorieForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idCategorie'])) {
					$idCategorie = strip_tags($_POST['idCategorie']);
					$categorie = ModelCategorie::select($idCategorie);

					if($categorie != false) {
						$form = '<form method="POST" role="form">
							<div class="alert alert-info text-center">
								Confirmez vous la suppression de la catégorie <b>'.strip_tags($categorie->get('label')).'</b> ?
							</div>

							<input type="hidden" name="idCategorie" value="'.$categorie->get('idCategorie').'">
							<input type="hidden" name="confirm" value="true">
							<input type="hidden" name="actionP" value="deleteCategorie">

							<div class="form-group">
								<button type="submit" class="btn btn-success">Confirmer</button>
								<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Annuler">Annuler</button>
							</div>
						</form>';
						$retour['result'] = true;
						$retour['message'] = $form;
	 				} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">La catégorie n\'existe pas !</div>';
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

	public static function imagesForm() {
		$retour = array();
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idProduit'])) {
					$idProduit = strip_tags($_POST['idProduit']);
					$produit = ModelProduit::select($idProduit);
					if($produit != false) {
						$images = $produit->getImages();

						$formAdd = '<form enctype="multipart/form-data" method="POST" role="form">
								<div class="form-group">
									<label for="image">Visuel à ajouter</label>
									<input id="image" class="form-control" type="file" name="image" placeholder="Sélectionnez une image" />
								</div>
								<div class="form-group">
									<input type="hidden" name="actionP" value="addImage">
									<input type="hidden" name="idProduit" value="'.$idProduit.'">
									<button type="submit" class="btn btn-success">Ajouter</button>
								</div>
							</form>';

						if($images != false) {
							$formTable = '<div class="table-responsive">
								<table class="table table-hover listProduitsTable">
									<thead>
										<tr>
											<th>ID Visuel</th>
											<th>URL</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>';

							foreach ($images as $image) {
								$idVisuel = $image['idVisuel'];
								$urlImage = $image['nomImage'];
								$formTable .= '<tr data-produit="'.$idProduit.'" data-visuel="'.$idVisuel.'">
									<td>'.$idVisuel.'</td>
									<td><a target="_blank" href="assets/images/produits/'.$urlImage.'">'.$urlImage.'</a></td>
									<td>
										<btn class="btn btn-xs btn-danger actionBtn" data-action="deleteImageForm"><i class="fa fa-trash" aria-hidden="true"></i> Supprimer</btn>
									</td>
								</tr>';
							}

							$formTable .= '</tbody>
								</table>
							</div>
							<script>actionBtn();</script>';

							$retour['result'] = true;
							$retour['message'] = $formAdd."<hr/>".$formTable;
						} else {
							$retour['result'] = true;
							$retour['message'] = $formAdd.'<div class="alert alert-warning text-center">Ce produit ne possède pour le moment aucun visuel</div>';
						}
					} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">Le produit demandé n\'existe pas</div>';
					}
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

	public static function deleteImageForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idProduit'], $_POST['idVisuel'])) {
					$idProduit = strip_tags($_POST['idProduit']);
					$idVisuel = strip_tags($_POST['idVisuel']);
					$produit = ModelProduit::select($idProduit);

					if($produit != false) {
						$visuel = ModelProduit::getImage($idProduit, $idVisuel);
						if($visuel != false) {
							$urlImage = $visuel['nomImage'];
							$form = '<form method="POST" role="form">
								<div class="alert alert-info text-center">
									Confirmez vous la suppression du visuel <b><a target="_blank" href="assets/images/produits/'.$urlImage.'">'.$urlImage.'</a></b> ?
								</div>

								<input type="hidden" name="idProduit" value="'.$idProduit.'">
								<input type="hidden" name="idVisuel" value="'.$idVisuel.'">
								<input type="hidden" name="confirm" value="true">
								<input type="hidden" name="actionP" value="deleteImage">

								<div class="form-group">
									<button type="submit" class="btn btn-success">Confirmer</button>
									<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Annuler">Annuler</button>
								</div>
							</form>';
							$retour['result'] = true;
							$retour['message'] = $form;
						} else {
							$retour['result'] = false;
							$retour['message'] = '<div class="alert alert-danger">Ce visuel n\'existe pas, ou n\'est plus associé à ce produit</div>';
						}
	 				} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">Le produit associé à ce visuel n\'existe pas !</div>';
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

	/* Gestion des utilisateurs */
	public static function users() {
		$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
		$powerNeeded = ($currentUser->getPower() == Conf::$power['admin']);
		
		// Gère les actions 
		if($powerNeeded) {
			if(isset($_POST['actionP']) && !empty($_POST['actionP'])) {
				switch ($_POST['actionP']) {
					case 'addOrEditUser':
						if(isset($_POST['idUser'], $_POST['prenom'], $_POST['nom'], $_POST['email'], $_POST['rang'], $_POST['typeAction'])) {
							$idUser = strip_tags($_POST['idUser']);
							$prenom = strip_tags($_POST['prenom']);
							$nom = strip_tags($_POST['nom']);
							$email = strip_tags($_POST['email']);
							$typeAction = strip_tags($_POST['typeAction']);
							$rang = strip_tags($_POST['rang']);
							if(isset($_POST['password'])) {
								$newPassword = $_POST['password'];
							} else {
								$newPassword = false;
							}
							if($typeAction == "edit" || $typeAction == "add") {
								if(!empty($prenom) && !ctype_space($prenom)) {
									if(!empty($nom) && !ctype_space($nom)) {
										if(!empty($email) && !ctype_space($email)) {
											if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
												$checkRang = ModelRang::select($rang);
												if($checkRang != false) {
													$data = array(
														'email' => $email,
														'prenom' => $prenom,
														'nom' => $nom,
														'rang' => $rang,
														'idUtilisateur' => $idUser
													);

													if($typeAction == "add" && $newPassword != false) {
														if(!empty($newPassword) && !ctype_space($newPassword)) {
															$newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
															$data = array(
																'idUtilisateur' => NULL,
																'email' => $email,
																'password' => $newPassword,
																'prenom' => $prenom,
																'nom' => $nom,
																'rang' => $rang,
																'nonce' => ModelUtilisateur::generateRandomHex()
															);
															$checkManageUser = ModelUtilisateur::save($data);
														} else {
															$notif = '<div class="alert alert-danger">Merci de saisir un mot de passe pour le nouvel utilisateur !</div>';
														}
													} else if($typeAction == "edit") {
														$user = ModelUtilisateur::select($idUser);
														if($user != false) {
															if($newPassword != false) {
																$newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
																$data['password'] = $newPassword;
															}
															$checkManageUser = ModelUtilisateur::update_gen($data, 'idUtilisateur');
														} else {
															$notif = '<div class="alert alert-danger">L\'utilisateur demandé n\'existe pas !</div>';
														}
													}
													if($checkManageUser) {
														if($typeAction == "edit") {
															$notif = '<div class="alert alert-success">Utilisateur modifié avec succès !</div>';
															if($newPassword != false) {
																$notif .= '<div class="alert alert-success">Le mot de passe a bien été changé !</div>';
															}
														} else if($typeAction == "add") {
															$notif .= '<div class="alert alert-success">Utilisateur ajouté avec succès ! !</div>';
														}
													} else {
														$notif = '<div class="alert alert-danger">Merci de remplir correctement le formulaire !</div>';
													}
												} else {
													$notif = '<div class="alert alert-danger">Le rang demandé n\'existe pas !</div>';
												}
											} else {
												$notif = '<div class="alert alert-danger">L\'e-mail doit être dans un format : nom@domain.ext</div>';
											}
										} else {
											$notif = '<div class="alert alert-danger">L\'e-mail ne peut être vide !</div>';
										}
									} else {
										$notif = '<div class="alert alert-danger">Le nom ne peut être vide !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Le prénom ne peut être vide !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">L\'action demandée n\'existe pas !</div>';
							}
						} else {
							$notif = '<div class="alert alert-danger">Merci de remplir correctement le formulaire !</div>';
						}
						if(isset($_POST)) {
							$dataPosted = '<script>var dataPosted = '.json_encode($_POST).'</script>';
						}
						break;
					case 'deleteUser':
						if(isset($_POST['idUser'],$_POST['confirm'])) {
							$idUser = strip_tags($_POST['idUser']);
							$user = ModelUtilisateur::select($idUser);
							if($user != false) {
								if($idUser != $_SESSION['idUser']) {
									$confirm = strip_tags($_POST['confirm']);
									if($confirm == true) {
										$checkDeleteUser = ModelUtilisateur::delete($user->get('idUtilisateur'));
										if($checkDeleteUser) {
											$notif = '<div class="alert alert-success">L\'utilisateur a bien été supprimé !</div>';
										} else {
											$notif = '<div class="alert alert-danger">Impossible de supprimer cet utilisateur !</div>';
										}
									} else {
										$notif = '<div class="alert alert-danger">Vous devez confirmer la suppression !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Vous ne pouvez vous pas vous auto-supprimer !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">L\'utilisateur demandé n\'existe pas !</div>';
							}
						} else {
							$notif = '<div class="alert alert-danger">Merci de remplir correctement le formulaire !</div>';
						}
						break;
					case 'updateRang':
						if(isset($_POST['idRang'], $_POST['label'], $_POST['color'])) {
							$idRang = strip_tags($_POST['idRang']);
							$label = strip_tags($_POST['label']);
							$color = strip_tags($_POST['color']);
							$rang = ModelRang::select($idRang);
							if($rang != false) {
								if(!empty($label) && !ctype_space($label)) {
									if(!empty($color) && !ctype_space($color)) {
										$colorH = str_replace("#", "", $color);
										if(ctype_xdigit($colorH) && strlen($colorH) <= 6) {
											$data = array(
												'label' => $label,
												'color' => $color,
												'idRang' => $idRang
											);
											$checkUpdateRang = ModelRang::update_gen($data, 'idRang');
											if($checkUpdateRang) {
												$notif = '<div class="alert alert-success">Rang modifié avec succès !</div>';
											} else {
												$notif = '<div class="alert alert-danger">Impossible de modifier ce rang, veuillez nous contacter !</div>';
											}
										} else {
											$notif = '<div class="alert alert-danger">Ce code couleur est invalide !</div>';
										}
									} else {
										$notif = '<div class="alert alert-danger">Vous devez saisir un code couleur !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Vous devez saisir un nom pour ce rang</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">Le rang demandé n\'existe pas !</div>';
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

		// Affiche la vue par défaut = lister tous les produtis du site
		$tab_u = ModelUtilisateur::selectAll();
		$view = 'listUsers';
		$pagetitle = 'So\'Cap - Administration - Liste des utilisateurs';
		require File::build_path(array('view', 'view.php'));
	}

	public static function manageUserForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idUser'], $_POST['typeAction'])) {
					$typeAction = strip_tags($_POST['typeAction']);
					if($typeAction == "edit" || $typeAction == "add") {
						$idUser = strip_tags($_POST['idUser']);
						$user = ModelUtilisateur::select($idUser);

						if($typeAction == "edit") {
							$user = ModelUtilisateur::select($idUser);
							if($user == false) {
								$retour['result'] = false;
								$retour['message'] = '<div class="alert alert-danger">L\'utilisateur demandé n\'existe pas !</div>';
							}
						}

						$selected = '';
				
						if(isset($_POST['dataPosted'])) {
							// Si jamais on récupère de la donnée postée précédemment (donc formulaire précédemment envoyé en erreur)
							// On la décode vu qu'elle était encodée en JSON.
							$dataPosted = json_decode($_POST['dataPosted']);
							// On stocke des variables de value="" (pour l'HTML).
							$idUser = (isset($dataPosted->idUser) ? strip_tags($dataPosted->idUser) : '');
							$prenom = (isset($dataPosted->prenom) ? strip_tags($dataPosted->prenom) : '');
							$nom = (isset($dataPosted->nom) ? strip_tags($dataPosted->nom) : '');
							$email = (isset($dataPosted->email) ? strip_tags($dataPosted->email) : '');
							$rangValue = (isset($dataPosted->rang) ? strip_tags($dataPosted->rang) : '');
							$selected = ($idCategorie == $categorieValue ? 'selected="selected"' : '');
						} else {
							$idUser = ($user != false ? $user->get('idUtilisateur'):'');
							$prenom = ($user != false ? $user->get('prenom'):'');
							$nom = ($user != false ? $user->get('nom'):'');
							$email = ($user != false ? $user->get('email'):'');
						}

						$rangs = ModelRang::selectAll();
						if($rangs != false) {
							$displayRangs = '';
							foreach ($rangs as $rang) {
								$idRang = $rang->get('idRang');
								$labelRang = $rang->get('label');
								if($typeAction == "edit") {
									$selected = ($idRang == $user->get('rang') ? 'selected="selected"' : '');
								}
								$displayRangs .= '<option '.$selected.' value="'.$idRang.'">'.$labelRang.'</option>';
							}
						}

						$form = '<form method="POST" role="form">
							<div class="form-group">
								<label for="idUser">ID de l\'utilisateur</label>
								<input type="text" autocomplete="off" id="idUser" name="idUser" class="form-control" value="'.$idUser.'" placeholder="'.($user != false ? 'ID de l\'utilisateur':'ID automatiquement déterminée').'" readonly="yes" />
							</div>

							<div class="form-group">
								<label for="prenom">Prénom de l\'utilisateur</label>
								<input type="text" name="prenom" autocomplete="off" id="prenom" class="form-control" value="'.$prenom.'" placeholder="Prénom de l\'utilisateur" />
							</div>

							<div class="form-group">
								<label for="nom">Nom de l\'utilisateur</label>
								<input type="text" name="nom" autocomplete="off" id="nom" class="form-control" value="'.$nom.'" placeholder="Nom de l\'utilisateur" />
							</div>

							<div class="form-group">
								<label for="email">E-mail de l\'utilisateur</label>
								<input type="mail" name="email" autocomplete="off" id="email" class="form-control" value="'.$email.'" placeholder="Email de l\'utilisateur" />
							</div>

							<div class="form-group">
								<label for="password">Mot de passe de l\'utilisateur <small>'.($user != false ? '(Laissez vide pour ne pas changer)':'').'</small></label>
								<input type="password" name="password" autocomplete="new-password" id="password" class="form-control" value="" placeholder="Saisir le mot de passe" />
							</div>

							<div class="form-group">
								<label for="rang">Rang de l\'utilisateur</label>
								<select id="rang" name="rang" class="form-control">
									'.$displayRangs.'
								</select>
							</div>

							<input type="hidden" name="actionP" value="addOrEditUser">
							<input type="hidden" name="typeAction" value="'.$typeAction.'">

							<div class="form-group">
								<button type="submit" class="btn btn-success">'.($user != false ? 'Modifier':'Ajouter').'</button>
								<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Annuler">Annuler</button>
							</div>
						</form>';
						$retour['result'] = true;
						$retour['message'] = $form;
					}
				} else {
					$retour['result'] = false;
					$retour['message'] = '<div class="alert alert-danger">Vous n\'avez pas envoyé correctement les données pour afficher ce formulaire !</div>';
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

	public static function deleteUserForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idUser'])) {
					$idUser = strip_tags($_POST['idUser']);
					$user = ModelUtilisateur::select($idUser);

					if($user != false) {
						$emailUser = strip_tags($user->get('email'));
						$form = '<form method="POST" role="form">
							<div class="alert alert-info text-center">
								Confirmez vous la suppression de l\'utilisateur <b>'.$emailUser.'</b> ?
							</div>

							<input type="hidden" name="idUser" value="'.$idUser.'">
							<input type="hidden" name="confirm" value="true">
							<input type="hidden" name="actionP" value="deleteUser">

							<div class="form-group">
								<button type="submit" class="btn btn-success">Confirmer</button>
								<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Annuler">Annuler</button>
							</div>
						</form>';
						$retour['result'] = true;
						$retour['message'] = $form;
	 				} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">L\'utilisateur demandé n\'existe pas !</div>';
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

	public static function manageRangsForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idRang'])) {
					$idRang = strip_tags($_POST['idRang']);
					if($idRang == "null") {

						$rangs = ModelRang::selectAll();
						if($rangs != false) {
							$formTable = '<div class="table-responsive">
								<table class="table table-hover listProduitsTable">
									<thead>
										<tr>
											<th>ID</th>
											<th>Nom</th>
											<th>Power</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>';

							foreach ($rangs as $rang) {
								$idRang = $rang->get('idRang');
								$labelRang = $rang->get('label');
								$powerRang = $rang->get('power');
								$colorRang = $rang->get('color');
								$formTable .= '<tr data-rang="'.$idRang.'">
									<td>'.$idRang.'</td>
									<td><span class="label" style="background-color:'.$colorRang.';">'.$labelRang.'</span></td>
									<td>'.$powerRang.'</td>
									<td>
										<btn class="btn btn-xs btn-warning actionBtn" data-action="editRangForm"><i class="fa fa-pencil" aria-hidden="true"></i> Editer</btn>
									</td>
								</tr>';
							}

							$formTable .= '</tbody>
								</table>
							</div>
							<div class="alert alert-info text-center">Il n\'est pas possible de supprimer/ajouter des rangs, ceux-ci ont été définis comme tel lors de la conception du site, veuillez vous rapprocher vers le webmaster pour en changer</div>
							<script>actionBtn();</script>';

							$retour['result'] = true;
							$retour['message'] = $formTable;
						} else {
							$retour['result'] = true;
							$retour['message'] = '<div class="alert alert-danger">Il n\'y a aucun rang, donc qu\'est-ce que vous faîtes là ?!</div>';
						}
	 				} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">Ereur de transmission des données !</div>';
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

	public static function editRangForm() {
		$retour = array(); //Tableau de retour
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			if($currentUser->getPower() == Conf::$power['admin']) {
				if(isset($_POST['idRang'])) {
					$idRang = strip_tags($_POST['idRang']);
					$rang = ModelRang::select($idRang);

					if($rang != false) {
						$form = '<form method="POST" role="form">
							<div class="form-group">
								<label for="idRang">ID du rang</label>
								<input type="text" autocomplete="off" id="idRang" name="idRang" class="form-control" value="'.$rang->get('idRang').'" placeholder="ID du rang" readonly="readonly" />
							</div>

							<div class="form-group">
								<label for="label">Libellé du rang</label>
								<input type="text" name="label" autocomplete="off" id="label" class="form-control" value="'.$rang->get('label').'" placeholder="Libellé du rang" />
							</div>

							<div class="form-group">
								<label for="color">Couleur du rang</label>
								<input type="text" name="color" autocomplete="off" id="color" class="form-control" value="'.$rang->get('color').'" placeholder="Libellé du rang" />
							</div>

							<input type="hidden" name="actionP" value="updateRang">

							<div class="form-group">
								<button type="submit" class="btn btn-success">Modifier</button>
								<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Annuler">Annuler</button>
							</div>
						</form>';
						$retour['result'] = true;
						$retour['message'] = $form;
	 				} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">Le rang demandée n\'existe pas !</div>';
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