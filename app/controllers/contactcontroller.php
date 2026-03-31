<?php

class ContactController
{
    public function index(): void
    {
        $pageTitle       = 'Contact — Web4All';
        $metaDescription = 'Contactez l\'équipe Web4All.';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom     = trim($_POST['nom']     ?? '');
            $email   = trim($_POST['email']   ?? '');
            $message = trim($_POST['message'] ?? '');

            $errors = [];

            if (empty($nom))                                        $errors[] = 'Le nom est obligatoire.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))         $errors[] = 'Adresse e-mail invalide.';
            if (strlen($message) < 10)                              $errors[] = 'Le message doit faire au moins 10 caractères.';

            if (empty($errors)) {
                // Tentative d'envoi par mail()
                $destinataire = 'contact@web4all.fr';
                $sujet        = '[Web4All] Message de ' . $nom;
                $corps        = "Nom : $nom\nEmail : $email\n\nMessage :\n$message";
                $headers      = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";

                $envoye = mail($destinataire, $sujet, $corps, $headers);

                // En local, mail() échoue souvent — on log dans un fichier
                if (!$envoye) {
                    $logDir  = __DIR__ . '/../../public/uploads/storage/';
                    if (!is_dir($logDir)) mkdir($logDir, 0755, true);
                    $logFile = $logDir . 'contact_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.log';
                    $ligne   = '[' . date('Y-m-d H:i:s') . '] ' . $nom . ' <' . $email . '> : ' . $message . PHP_EOL;
                    file_put_contents($logFile, $ligne, FILE_APPEND);
                }

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.'];
                header('Location: /contact');
                exit;
            }

            $error = implode('<br>', array_map('htmlspecialchars', $errors));
        }

        require __DIR__ . '/../views/contact/index.php';
    }
}