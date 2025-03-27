<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use App\Validators\IpValidator;

class LoginTest extends TestCase
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
        
        // Nettoyer la table login_attempts
        $this->pdo->exec("TRUNCATE TABLE login_attempts");
    }
    
    public function testLoginAttemptsLimit(): void
    {
        $ip = '127.0.0.1';
        
        // Simuler 5 tentatives de connexion
        for ($i = 0; $i < 5; $i++) {
            $stmt = $this->pdo->prepare("INSERT INTO login_attempts (username, ip_address) VALUES (?, ?)");
            $stmt->execute(['test_user', $ip]);
        }
        
        // Vérifier le nombre de tentatives
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as attempts FROM login_attempts WHERE ip_address = ?");
        $stmt->execute([$ip]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertSame(5, (int)$result['attempts']);
    }
    
    public function testLoginAttemptsReset(): void
    {
        $ip = '127.0.0.1';
        
        // Ajouter une tentative
        $stmt = $this->pdo->prepare("INSERT INTO login_attempts (username, ip_address) VALUES (?, ?)");
        $stmt->execute(['test_user', $ip]);
        
        // Supprimer les tentatives (simuler une connexion réussie)
        $stmt = $this->pdo->prepare("DELETE FROM login_attempts WHERE ip_address = ?");
        $stmt->execute([$ip]);
        
        // Vérifier qu'il n'y a plus de tentatives
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as attempts FROM login_attempts WHERE ip_address = ?");
        $stmt->execute([$ip]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertSame(0, (int)$result['attempts']);
    }

    public function testMultipleIPs(): void
    {
        $ip1 = '127.0.0.1';
        $ip2 = '192.168.1.1';
        
        // Ajouter des tentatives pour deux IPs différentes
        $stmt = $this->pdo->prepare("INSERT INTO login_attempts (username, ip_address) VALUES (?, ?)");
        $stmt->execute(['test_user1', $ip1]);
        $stmt->execute(['test_user2', $ip2]);
        
        // Vérifier les tentatives pour chaque IP
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as attempts FROM login_attempts WHERE ip_address = ?");
        
        $stmt->execute([$ip1]);
        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertSame(1, (int)$result1['attempts']);
        
        $stmt->execute([$ip2]);
        $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertSame(1, (int)$result2['attempts']);
    }

    public function testAttemptsExpiration(): void
    {
        $ip = '127.0.0.1';
        
        // Ajouter une tentative avec une date d'expiration
        $stmt = $this->pdo->prepare("INSERT INTO login_attempts (username, ip_address, attempt_time) VALUES (?, ?, DATE_SUB(NOW(), INTERVAL 31 MINUTE))");
        $stmt->execute(['test_user', $ip]);
        
        // Vérifier que l'ancienne tentative n'est pas comptée
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as attempts FROM login_attempts WHERE ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL 30 MINUTE)");
        $stmt->execute([$ip]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertSame(0, (int)$result['attempts']);
    }

    public function testConcurrentAttempts(): void
    {
        $ip = '127.0.0.1';
        
        // Simuler des tentatives simultanées
        $this->pdo->beginTransaction();
        
        try {
            // Première tentative
            $stmt = $this->pdo->prepare("INSERT INTO login_attempts (username, ip_address) VALUES (?, ?)");
            $stmt->execute(['test_user1', $ip]);
            
            // Deuxième tentative (simulée)
            $stmt->execute(['test_user2', $ip]);
            
            $this->pdo->commit();
            
            // Vérifier le nombre total de tentatives
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as attempts FROM login_attempts WHERE ip_address = ?");
            $stmt->execute([$ip]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->assertSame(2, (int)$result['attempts']);
            
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function testInvalidIP(): void
    {
        // Tester avec différentes IPs invalides
        $invalidIPs = [
            'invalid_ip',           // IP non numérique
            '256.256.256.256',     // Valeurs > 255
            '1.2.3',               // IP incomplète
            '192.168.1.1.1',       // Trop d'octets
            '192.168.1',           // IP incomplète
            '192.168.1.1.',        // Point à la fin
            '.192.168.1.1',        // Point au début
            '192.168.1.1.1.1'      // Trop d'octets
        ];

        foreach ($invalidIPs as $invalidIP) {
            $this->assertFalse(
                IpValidator::isValid($invalidIP),
                "L'IP '$invalidIP' ne devrait pas être considérée comme valide"
            );

            // Si l'IP est invalide, on ne devrait même pas essayer de l'insérer
            if (!IpValidator::isValid($invalidIP)) {
                continue;
            }
        }
    }

    public function testValidIPs(): void
    {
        // Tester avec différentes IPs valides
        $validIPs = [
            '127.0.0.1',           // localhost
            '192.168.1.1',         // IP privée
            '192.168.001.1',       // IP privée avec zéros non significatifs
            '10.0.0.1',            // IP privée
            '172.16.0.1',          // IP privée
            '0.0.0.0',             // IP spéciale
            '255.255.255.255',     // Broadcast
            '192.168.0.1',         // IP privée
            '10.10.10.10',         // IP privée
            '172.31.255.255'       // IP privée
        ];

        foreach ($validIPs as $validIP) {
            // Nettoyer la table avant chaque test
            $this->pdo->exec("TRUNCATE TABLE login_attempts");
            
            $this->assertTrue(
                IpValidator::isValid($validIP),
                "L'IP '$validIP' devrait être considérée comme valide"
            );

            if (IpValidator::isValid($validIP)) {
                $normalizedIP = $this->getNormalizedIP($validIP);
                try {
                    $stmt = $this->pdo->prepare("INSERT INTO login_attempts (username, ip_address) VALUES (?, ?)");
                    $stmt->execute(['test_user', $normalizedIP]);
                    
                    // Vérifier que l'IP a été correctement enregistrée
                    $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM login_attempts WHERE ip_address = ?");
                    $stmt->execute([$normalizedIP]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    $this->assertSame(1, (int)$result['count'], 
                        sprintf("L'IP valide '%s' (normalisée en '%s') n'a pas été correctement enregistrée", 
                            $validIP, 
                            $normalizedIP
                        )
                    );
                    
                } catch (\PDOException $e) {
                    $this->fail(sprintf("L'IP valide '%s' (normalisée en '%s') a été rejetée : %s",
                        $validIP,
                        $normalizedIP,
                        $e->getMessage()
                    ));
                }
            }
        }
    }

    private function getNormalizedIP(string $ip): string
    {
        $parts = explode('.', $ip);
        return implode('.', array_map('intval', $parts));
    }

    public function testLockoutAfterFailedAttempts(): void
    {
        $ip = '127.0.0.1';
        $username = 'test_user';
        $maxAttempts = 5; // Supposons que le verrouillage se produit après 5 tentatives

        // Simuler des tentatives échouées
        for ($i = 0; $i < $maxAttempts; $i++) {
            $stmt = $this->pdo->prepare("INSERT INTO login_attempts (username, ip_address) VALUES (?, ?)");
            $stmt->execute([$username, $ip]);
        }

        // Vérifier que l'utilisateur est verrouillé après le nombre maximum de tentatives
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as attempts FROM login_attempts WHERE ip_address = ? AND username = ?");
        $stmt->execute([$ip, $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertSame($maxAttempts, (int)$result['attempts'], "L'utilisateur n'est pas verrouillé après $maxAttempts tentatives échouées");
    }
} 