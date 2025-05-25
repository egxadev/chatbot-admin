<?php

/**
 * Success response
 *
 * @param mixed $data
 * @param string $message
 * @param int $code
 * @return array
 */
 function responseSuccess($data = null, string $message = 'Success', int $code = 200): array
{
    return [
        'success' => true,
        'message' => $message,
        'data' => $data,
        'code' => $code
    ];
}

/**
 * Error response
 *
 * @param string $message
 * @param int $code
 * @param mixed $errors
 * @return array
 */
 function responseError(string $message = 'Error', int $code = 400, $errors = null): array
{
    return [
        'success' => false,
        'message' => $message,
        'errors' => $errors,
        'code' => $code
    ];
}

/**
 * Validation error response
 *
 * @param mixed $errors
 * @param string $message
 * @return array
 */
 function responseValidationError($errors, string $message = 'Validation Error'): array
{
    return error($message, 422, $errors);
}

/**
 * Not found response
 *
 * @param string $message
 * @return array
 */
 function responseNotFound(string $message = 'Resource not found'): array
{
    return error($message, 404);
}

/**
 * Unauthorized response
 *
 * @param string $message
 * @return array
 */
 function responseUnauthorized(string $message = 'Unauthorized'): array
{
    return error($message, 401);
}

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
