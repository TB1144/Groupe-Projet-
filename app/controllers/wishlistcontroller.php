<?php
class WishlistController {
    public function index(): void {
        $model = new Wishlist();
        $items = $model->getForUser($_SESSION['user_id']);
        require 'app/views/wishlist/index.php';
    }

    public function ajouter(int $offreId): void {
        $model = new Wishlist();
        $model->add($_SESSION['user_id'], $offreId);
        header("Location: /offres"); // Redirige vers la liste des offres
    }

    public function supprimer(int $wishlistId): void {
        $model = new Wishlist();
        $model->delete($wishlistId);
        header("Location: /wishlist");
    }
}
?>