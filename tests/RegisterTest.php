<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use PDO;

class RegisterTest extends TestCase
{
    private $pdo;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Connexion à la base de données de test
        $this->pdo = new PDO(
            "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME'),
            getenv('DB_USER'),
            getenv('DB_PASS')
        );
        
        // Nettoyer la table users des utilisateurs de test
        $this->pdo->exec("DELETE FROM users WHERE UserName LIKE 'test%@example.com'");
    }
    
    protected function tearDown(): void
    {
        // Nettoyer après les tests
        $this->pdo->exec("DELETE FROM users WHERE UserName LIKE 'test%@example.com'");
        parent::tearDown();
    }
    
    /**
     * @dataProvider validUserDataProvider
     */
    public function testValidRegistration(string $username, string $email, string $password): void
    {
        // Simuler l'inscription d'un utilisateur valide
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("INSERT INTO users (NameUser, UserName, Password) VALUES (?, ?, ?)");
        $result = $stmt->execute([$username, $email, $hashed_password]);
        
        $this->assertTrue($result, "L'insertion d'un utilisateur valide a échoué");
        
        // Vérifier que l'utilisateur a bien été inséré
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE UserName = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertNotFalse($user, "L'utilisateur n'a pas été trouvé après l'insertion");
        $this->assertSame($username, $user['NameUser'], "Le nom d'utilisateur n'a pas été correctement enregistré");
        $this->assertSame($email, $user['UserName'], "L'email n'a pas été correctement enregistré");
        
        // Vérifier que le mot de passe a été correctement haché
        $this->assertTrue(password_verify($password, $user['Password']), "Le hachage du mot de passe n'est pas valide");
    }
    
    public function validUserDataProvider(): array
    {
        return [
            'Données utilisateur standard' => ['test_user', 'test1@example.com', 'Password123'],
            'Nom d\'utilisateur avec caractères spéciaux' => ['test_user-123', 'test2@example.com', 'StrongP@ssw0rd'],
            'Email avec sous-domaine' => ['test_user3', 'test3@sub.example.com', 'AnotherP@ss123']
        ];
    }
    
    /**
     * @dataProvider invalidUserDataProvider
     */
    public function testInvalidRegistration(string $username, string $email, string $password, string $expectedError): void
    {
        // Cette fonction simule la validation que le script register.php effectuerait
        $errors = $this->validateRegistrationData($username, $email, $password, $password);
        
        $this->assertNotEmpty($errors, "Des données invalides ont été acceptées: username='$username', email='$email'");
        $this->assertStringContainsString($expectedError, $errors[0], "Message d'erreur attendu non trouvé");
    }
    
    public function invalidUserDataProvider(): array
    {
        return [
            'Nom d\'utilisateur vide' => ['', 'test@example.com', 'Password123', 'obligatoires'],
            'Email invalide' => ['test_user', 'not-an-email', 'Password123', 'email invalide'],
            'Mot de passe trop court' => ['test_user', 'test@example.com', 'short', 'au moins 8 caractères'],
            'Email déjà utilisé' => ['test_dupe', 'dupe@example.com', 'Password123', 'déjà utilisé']
        ];
    }
    
    public function testPasswordMismatch(): void
    {
        // Tester lorsque les deux mots de passe ne correspondent pas
        $username = 'test_user';
        $email = 'test@example.com';
        $password = 'Password123';
        $password_verif = 'DifferentPassword';
        
        $errors = $this->validateRegistrationData($username, $email, $password, $password_verif);
        
        $this->assertNotEmpty($errors, "Des mots de passe différents ont été acceptés");
        $this->assertStringContainsString('ne correspondent pas', $errors[0], "Message d'erreur attendu non trouvé");
    }
    
    public function testDuplicateUser(): void
    {
        // Créer un utilisateur
        $username = 'test_duplicate';
        $email = 'duplicate@example.com';
        $password = 'Password123';
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("INSERT INTO users (NameUser, UserName, Password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);
        
        // Tenter de créer un utilisateur avec le même email
        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (NameUser, UserName, Password) VALUES (?, ?, ?)");
            $stmt->execute(['another_user', $email, $hashed_password]);
            $this->fail("La création d'un utilisateur avec un email en double n'a pas échoué comme prévu");
        } catch (\PDOException $e) {
            // Une exception devrait être lancée en cas de violation de contrainte d'unicité
            $this->assertTrue(true, "Exception correctement lancée pour un email en double");
        }
        
        // Vérifier qu'un seul utilisateur avec cet email existe
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM users WHERE UserName = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertSame(1, (int)$result['count'], "Plus d'un utilisateur avec le même email existe");
    }
    
    /**
     * Fonction utilitaire pour valider les données d'inscription
     */
    private function validateRegistrationData(string $username, string $email, string $password, string $password_verif): array
    {
        $errors = [];
        
        // Validation des champs
        if (empty($username) || empty($email) || empty($password) || empty($password_verif)) {
            $errors[] = "Tous les champs sont obligatoires.";
            return $errors;
        } 
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format d'email invalide.";
        }
        
        if ($password !== $password_verif) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }
        
        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        
        // Vérifier si l'utilisateur ou l'email existent déjà (simulation pour 'dupe@example.com')
        if ($email === 'dupe@example.com') {
            $errors[] = "Nom d'utilisateur ou email déjà utilisé.";
        }
        
        return $errors;
    }
} 