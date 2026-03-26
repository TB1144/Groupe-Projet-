<?php
class ContactController {
    public function index(): void {
        $pageTitle = 'Contact — Web4All';
        $metaDescription = 'Contactez l\'équipe Web4All.';
        require __DIR__ . '/../views/contact/index.php';
    }
}
