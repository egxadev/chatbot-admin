<?php

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
