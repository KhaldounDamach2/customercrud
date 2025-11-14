<?php
require_once __DIR__ . '/../vendor/autoload.php';  // Fixed path

use SendGrid\Mail\Mail;
use SendGrid;

class SimpleEmailService {
    private $sendGrid;

    public function __construct() {
        $apiKey = $_ENV['SENDGRID_API_KEY'] ?? '';
        if (empty($apiKey)) {
            throw new Exception('SendGrid API key not found. Please set SENDGRID_API_KEY environment variable.');
        }
        $this->sendGrid = new SendGrid($apiKey);
    }

    public function sendWelcomeEmail($toEmail, $customerName) {
        $email = new Mail();
        $email->setFrom("damach.k@gmx.de", "Mario's Pizza Palace");
        $email->setSubject("Welcome to Our Service!");
        $email->addTo($toEmail, $customerName);
        
        $content = "<h1>Welcome, {$customerName}!</h1><p>Thank you for registering!</p>";
        $email->addContent("text/html", $content);

        try {
            $response = $this->sendGrid->send($email);
            return $response->statusCode() === 202;
        } catch (Exception $e) {
            error_log('SendGrid error: ' . $e->getMessage());
            return false;
        }
    }
}
?>