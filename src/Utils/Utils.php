<?php
namespace App\Utils;

use JetBrains\PhpStorm\NoReturn;

class Utils {
    public static function normalizePhoneNumber(string $phoneNumber): string|null {
        // Trim phone number
        $phoneNumber = trim($phoneNumber);

        // Special presets
        $presets = [
            '*73' => 'Activation du renvoi d’appel',
            's' => 'Répondeur',
            'hangup' => 'Raccroché',
        ];

        if ( in_array($phoneNumber, array_keys($presets), true) ) {
            return $presets[$phoneNumber];
        }

        // Remove (0)
        $phoneNumber = preg_replace('/\(\d\)/', '', $phoneNumber);

        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // Remove leading 0
        $phoneNumber = preg_replace('/^0/', '', $phoneNumber);

        // If phone number is empty or consists entirely of non-numeric characters, return null
        if (empty($phoneNumber)) {
            return null;
        }

        // Check if the phone number starts with '0' (indicating French format)
        $phoneNumberLength = strlen($phoneNumber);
        if ($phoneNumberLength === 9) {
            $phoneNumber = '33' . $phoneNumber;
        } else if (strlen($phoneNumber) < 9) {
            return $phoneNumber;
        }

        // If the phone number doesn't start with '+', add it
        if (!str_starts_with($phoneNumber, '+')) {
            $phoneNumber = '+' . $phoneNumber;
        }

        return $phoneNumber;
    }

    public static function convertBytesToHumanReadable(int $bytes): string {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public static function secondsToHoursMinutes(int $seconds): string {
        // Calculate hours and minutes
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        // Construct the human-readable format
        $output = '';
        if ($hours > 0) {
            $output .= $hours . ' hour' . ($hours > 1 ? 's' : '');
        }
        if ($minutes > 0) {
            $output .= ($output == '' ? '' : ' ') . $minutes . ' minute' . ($minutes > 1 ? 's' : '');
        }

        return $output;
    }


    #[NoReturn]
    public static function returnJsonResponse(array $data, int $statusCode = 200, string $message = 'Success'): void
    {
        header('Content-Type: application/json');
        echo json_encode([
            'message' => $message,
            'data' => $data,
        ]);
        http_response_code($statusCode);
        exit;
    }
}