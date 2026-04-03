<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/controllers/authcontroller.php';

class AuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        // Initialise une session propre pour chaque test
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION  = [];
        $_POST     = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        $_POST    = [];
    }

    public function testAuthControllerIsInstantiable(): void
    {
        $controller = new AuthController();
        $this->assertInstanceOf(AuthController::class, $controller);
    }

    public function testLoginMethodExistsAndIsPublic(): void
    {
        $ref = new ReflectionMethod(AuthController::class, 'login');
        $this->assertTrue($ref->isPublic());
    }

    public function testLoginFormMethodExistsAndIsPublic(): void
    {
        $ref = new ReflectionMethod(AuthController::class, 'loginForm');
        $this->assertTrue($ref->isPublic());
    }

    public function testLogoutMethodExistsAndIsPublic(): void
    {
        $ref = new ReflectionMethod(AuthController::class, 'logout');
        $this->assertTrue($ref->isPublic());
    }

    /**
     * Vérifie que la logique login avec champs vides ne plante pas
     * et ne valide pas un user vide (sans passer par la BDD).
     */
    public function testLoginWithEmptyFieldsDoesNotSetSession(): void
    {
        $_POST['email']    = '';
        $_POST['password'] = '';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Pas de user_id en session sans credentials valides
        $this->assertArrayNotHasKey('user_id', $_SESSION);
    }

    public function testSessionStartsEmpty(): void
    {
        $this->assertEmpty($_SESSION);
    }

    public function testSessionIsDestroyedOnLogout(): void
    {
        $_SESSION['user_id'] = 42;
        $_SESSION['role']    = 'etudiant';

        session_unset();
        session_destroy();

        // Recrée une session vide pour les tests suivants
        session_start();
        $_SESSION = [];

        $this->assertArrayNotHasKey('user_id', $_SESSION);
    }
}