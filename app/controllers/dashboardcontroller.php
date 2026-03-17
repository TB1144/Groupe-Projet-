<?php
class DashboardController {
    public function index(): void {
        // On récupère les stats (SFx 11)
        $offreModel = new Offre();
        $stats = [
            'total_offres' => $offreModel->countAll(),
            'top_wishlist' => $offreModel->getTopWishlisted(),
            'moyenne_candidatures' => $offreModel->getAverageApplications()
        ];

        require 'app/views/dashboard/index.php';
    }
}
?>