<?php
namespace App\Utils;
class Security
{
    public static function check()
    {
        session_start();

        // Check CSRF token
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
             Utils::returnJsonResponse([], 403, 'Invalid CSRF token');
        }
    }

    public static function generateCSRFToken(): void
    {
        session_start();

        // Generate a CSRF token if it doesn't exist
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function escapeHtml(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    public static function setSecurityHttpHeaders(): void
    {
        // Set Content-Security-Policy (CSP) header
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");

        // Set X-Content-Type-Options header to prevent content type sniffing
        header("X-Content-Type-Options: nosniff");

        // Set X-Frame-Options header to prevent clickjacking
        header("X-Frame-Options: DENY");

        // Set X-XSS-Protection header to enable XSS filter
        header("X-XSS-Protection: 1; mode=block");
    }

    public static function setCrossOriginResourcePolicy(): void
    {
        // Allow requests only from the same origin
        header("Access-Control-Allow-Origin: same-origin");

        // Set other CORS headers to restrict cross-origin requests
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");

        // Check if it's a preflight request (OPTIONS) and respond accordingly
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            // Return a 204 No Content response for preflight requests
            http_response_code(204);
            exit;
        }
    }
}