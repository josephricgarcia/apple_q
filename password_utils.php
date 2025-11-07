<?php
/**
 * Secure Password Utilities
 * Provides secure password hashing and verification with salt
 */
class PasswordUtils {
    
    /**
     * Generate a cryptographically secure random salt
     * @param int $length Length of the salt in bytes
     * @return string Base64 encoded salt
     */
    public static function generateSalt($length = 32) {
        return base64_encode(random_bytes($length));
    }
    
    /**
     * Hash a password with a salt using PBKDF2
     * @param string $password The plain text password
     * @param string $salt The salt to use (if null, generates a new one)
     * @return array Array containing 'hash' and 'salt'
     */
    public static function hashPassword($password, $salt = null) {
        if ($salt === null) {
            $salt = self::generateSalt();
        }
        
        // Use PBKDF2 with SHA-256, 100,000 iterations
        $hash = hash_pbkdf2('sha256', $password, $salt, 100000, 64);
        
        return [
            'hash' => $hash,
            'salt' => $salt
        ];
    }
    
    /**
     * Verify a password against a hash and salt
     * @param string $password The plain text password to verify
     * @param string $hash The stored hash
     * @param string $salt The stored salt
     * @return bool True if password is correct, false otherwise
     */
    public static function verifyPassword($password, $hash, $salt) {
        $computedHash = hash_pbkdf2('sha256', $password, $salt, 100000, 64);
        return hash_equals($hash, $computedHash);
    }
    
    /**
     * Create a combined hash string that includes both hash and salt
     * Format: hash:salt
     * @param string $password The plain text password
     * @return string Combined hash and salt
     */
    public static function createPasswordHash($password) {
        $result = self::hashPassword($password);
        return $result['hash'] . ':' . $result['salt'];
    }
    
    /**
     * Verify a password against a combined hash string
     * @param string $password The plain text password to verify
     * @param string $combinedHash The stored combined hash and salt
     * @return bool True if password is correct, false otherwise
     */
    public static function verifyPasswordHash($password, $combinedHash) {
        $parts = explode(':', $combinedHash, 2);
        if (count($parts) !== 2) {
            return false;
        }
        
        $hash = $parts[0];
        $salt = $parts[1];
        
        return self::verifyPassword($password, $hash, $salt);
    }
    
    /**
     * Check if a password meets security requirements
     * @param string $password The password to check
     * @return array Array with 'valid' boolean and 'errors' array
     */
    public static function validatePasswordStrength($password) {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number";
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = "Password must contain at least one special character";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
?>
