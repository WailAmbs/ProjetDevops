<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Validators\EmailValidator;
use PDO;

class EmailValidatorTest extends TestCase
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
     * @dataProvider validEmailProvider
     */
    public function testIsValidWithValidEmails(string $email): void
    {
        $this->assertTrue(
            EmailValidator::isValid($email),
            "L'email '$email' aurait dû être validé"
        );
    }
    
    /**
     * @dataProvider invalidEmailProvider
     */
    public function testIsValidWithInvalidEmails(string $email): void
    {
        $this->assertFalse(
            EmailValidator::isValid($email),
            "L'email '$email' aurait dû être rejeté"
        );
    }
    
    public function testEmailExists(): void
    {
        $email = 'test_exists@example.com';
        
        // Vérifier d'abord que l'email n'existe pas
        $this->assertFalse(
            EmailValidator::exists($email, $this->pdo),
            "L'email ne devrait pas exister avant le test"
        );
        
        // Créer un utilisateur
        $stmt = $this->pdo->prepare("INSERT INTO users (NameUser, UserName, Password) VALUES (?, ?, ?)");
        $stmt->execute(['test_user', $email, 'hashed_password']);
        
        // Vérifier que l'email existe maintenant
        $this->assertTrue(
            EmailValidator::exists($email, $this->pdo),
            "L'email devrait exister après l'insertion"
        );
    }
    
    public function testNormalize(): void
    {
        $this->assertSame(
            'test@example.com',
            EmailValidator::normalize('  Test@Example.COM  '),
            "L'email n'a pas été correctement normalisé"
        );
    }
    
    public function validEmailProvider(): array
    {
        return [
            'Email standard' => ['user@example.com'],
            'Email avec sous-domaine' => ['user@sub.example.com'],
            'Email avec chiffres' => ['user123@example.com'],
            'Email avec tiret' => ['user-name@example.com'],
            'Email avec point' => ['user.name@example.com'],
            'Email avec plus' => ['user+tag@example.com'],
            'Email avec TLD long' => ['user@example.world'],
        ];
    }
    
    public function invalidEmailProvider(): array
    {
        return [
            'Email sans @' => ['userexample.com'],
            'Email sans domaine' => ['user@'],
            'Email sans utilisateur' => ['@example.com'],
            'Email avec caractères invalides' => ['user*name@example.com'],
            'Email avec espaces' => ['user name@example.com'],
            'Email trop long' => [str_repeat('a', 246) . '@example.com'], // 257 caractères au total
            'Email avec TLD trop court' => ['user@example.x'],
            'Email avec multiple @' => ['user@name@example.com'],
        ];
    }
} 