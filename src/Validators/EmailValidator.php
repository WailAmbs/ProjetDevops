<?php

namespace App\Validators;

class EmailValidator 
{
    /**
     * Valide une adresse email
     * 
     * @param string $email L'adresse email à valider
     * @return bool True si l'email est valide, false sinon
     */
    public static function isValid(string $email): bool 
    {
        // Vérification de base avec filter_var
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        // Vérifications supplémentaires
        
        // Vérifier la longueur de l'email
        if (strlen($email) > 254) {
            return false;
        }
        
        // Vérifier que le domaine a un TLD valide (au moins 2 caractères)
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return false;
        }
        
        $domain = $parts[1];
        $domainParts = explode('.', $domain);
        $tld = end($domainParts);
        
        if (strlen($tld) < 2) {
            return false;
        }
        
        // Vérifier si le domaine existe via DNS (optionnel, pourrait ralentir l'application)
        // if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
        //     return false;
        // }
        
        return true;
    }
    
    /**
     * Vérifie si un email existe déjà dans la base de données
     * 
     * @param string $email L'adresse email à vérifier
     * @param \PDO $pdo Connexion PDO à la base de données
     * @return bool True si l'email existe déjà, false sinon
     */
    public static function exists(string $email, \PDO $pdo): bool 
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE UserName = ?");
        $stmt->execute([$email]);
        return (int) $stmt->fetchColumn() > 0;
    }
    
    /**
     * Normalise une adresse email (convertit en minuscules)
     * 
     * @param string $email L'adresse email à normaliser
     * @return string L'adresse email normalisée
     */
    public static function normalize(string $email): string 
    {
        return strtolower(trim($email));
    }
} 