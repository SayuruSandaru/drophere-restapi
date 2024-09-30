<?php

namespace App\Repository;
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailRepository
{
    private $mailer;

    public function __construct()
    {
        // Create a new PHPMailer instance
        $this->mailer = new PHPMailer(true);

        // Set up SMTP configurations (could be moved to config or environment variables)
        $this->mailer->isSMTP();                                             // Set mailer to use SMTP
        $this->mailer->Host = 'smtp.gmail.com';                             // Specify main SMTP server
        $this->mailer->SMTPAuth = true;                                       // Enable SMTP authentication
        $this->mailer->Username = 'drophereservice@gmail.com';                   // SMTP username
        $this->mailer->Password = 'uang ufyb topm yhzy';                      // SMTP password
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           // Enable TLS encryption
        $this->mailer->Port = 587;                                            // TCP port to connect to
        $this->mailer->setFrom('drophereservice@gmail.com', 'drophere-web');      // Sender email and name
    }

    public function sendEmail(string $toEmail, string $subject, string $body, string $altBody = null): bool
    {
        try {
            // Add recipient
            $this->mailer->addAddress($toEmail);

            // Set email format to HTML
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            // Add alternative body (optional, for clients that do not support HTML email)
            if ($altBody) {
                $this->mailer->AltBody = $altBody;
            }

            // Send the email
            $this->mailer->send();
            echo 'Message has been sent';
            return true;  
        } catch (Exception $e) {
            // Handle error, could log the error message
            error_log("Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
            return false;  // Failed to send email
        }
    }
}
