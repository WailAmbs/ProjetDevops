<?php

namespace App\Validators;

class IpValidator
{
    public static function isValid(string $ip): bool
    {
        // Nettoyer l'IP des zéros non significatifs
        $cleanIP = self::normalizeIP($ip);
        
        // Vérifie si l'IP est au format IPv4 valide
        return filter_var($cleanIP, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }
    
    private static function normalizeIP(string $ip): string
    {
        // Vérifier si l'IP contient exactement 4 octets
        $parts = explode('.', $ip);
        if (count($parts) !== 4) {
            return $ip; // Retourner l'IP inchangée si le format est incorrect
        }
        
        // Convertir chaque octet en nombre et le reformater
        $normalizedParts = array_map(function($part) {
            // Vérifier si la partie est un nombre valide entre 0 et 255
            if (!is_numeric($part) || $part === '' || intval($part) < 0 || intval($part) > 255) {
                return $part; // Retourner la partie inchangée si invalide
            }
            return (string)intval($part); // Convertir en nombre pour enlever les zéros non significatifs
        }, $parts);
        
        return implode('.', $normalizedParts);
    }
} 