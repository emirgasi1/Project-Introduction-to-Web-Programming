<?php
class RequestValidationMiddleware {

    // Provjeri da li su zadana obavezna polja
    public static function validateRequiredFields(array $data, array $requiredFields) {
        $missing = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $missing[] = $field;
            }
        }
        if ($missing) {
            throw new Exception('Missing required fields: ' . implode(', ', $missing), 400);
        }
    }

}
