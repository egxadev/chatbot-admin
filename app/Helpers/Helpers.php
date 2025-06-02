<?php

/**
 * Decrypts encrypted data using AES-256-CBC encryption with HMAC verification.
 * 
 * This function performs the following steps:
 * 1. Splits the encrypted data into IV, encrypted content, and HMAC
 * 2. Verifies the HMAC to ensure data integrity
 * 3. Decrypts the data using AES-256-CBC
 * 4. Validates the message timestamp to prevent replay attacks
 * 
 * @param string $encryptedData The encrypted data in format: "iv:encrypted:hmac"
 *        where:
 *        - iv: Base64 encoded initialization vector
 *        - encrypted: Base64 encoded encrypted data
 *        - hmac: Base64 encoded HMAC for verification
 * 
 * @return string The decrypted plain text message
 * 
 * @throws \Exception When:
 *        - HMAC verification fails
 *        - Decryption fails
 *        - Message is older than 5 minutes
 *        - Invalid data format
 */
function decryptData($encryptedData)
{
    try {
        $algorithm = 'aes-256-cbc';
        $key = hash('sha256', env('ENCRYPTION_KEY'), true);
        
        // Split IV, encrypted data, and HMAC
        list($ivBase64, $encrypted, $hmac) = explode(':', $encryptedData);
        $iv = base64_decode($ivBase64);
        
        // Verify HMAC
        $calculatedHmac = base64_encode(hash_hmac('sha256', $encrypted, $key, true));
        if (!hash_equals($calculatedHmac, $hmac)) {
            throw new \Exception('HMAC verification failed');
        }
        
        // Decrypt
        $decrypted = openssl_decrypt(
            base64_decode($encrypted),
            $algorithm,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        if ($decrypted === false) {
            throw new \Exception('Decryption failed: ' . openssl_error_string());
        }
        
        // Extract timestamp and verify it's not too old
        list($timestamp, $text) = explode(':', $decrypted);
        $messageAge = time() - (int)($timestamp / 1000); // Convert from milliseconds to seconds
        if ($messageAge > 300) { // 5 minutes
            throw new \Exception('Message too old');
        }
        
        return $text;
    } catch (\Exception $e) {
        \Log::error('Decryption error', [
            'message' => $e->getMessage(),
            'encrypted_data' => $encryptedData
        ]);
        throw $e;
    }
}
