<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mail;

    // Static method to send an email
    public static function send($to, $subject, $body, $altBody = '') {
        $mailer = new self();
        $mailer->setupSMTP();

        $mailer->mail->addAddress($to); 
        $mailer->mail->Subject = $subject; 
        $mailer->mail->Body = $body; 
        $mailer->mail->AltBody = $altBody ?: strip_tags($body); 

        return $mailer; // Return class instance for further settings
    }

    // Set up SMTP configuration
    private function setupSMTP() {
        $this->mail = new PHPMailer(true);
        try {
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.example.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'your_email@example.com';
            $this->mail->Password = 'your_password';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;
            $this->mail->setFrom('your_email@example.com', 'Your Name');
            $this->mail->isHTML(true);
        } catch (Exception $e) {
            throw new Exception("SMTP Setup Error: {$this->mail->ErrorInfo}");
        }
    }

    // Add attachment to the email
    public function addAttachment($filePath, $fileName = '') {
        $this->mail->addAttachment($filePath, $fileName ?: ''); // Attach file
        return $this;
    }

    // Load HTML template for the email
    public function loadTemplate($templatePath, $data = []) {
        $template = file_get_contents($templatePath); 
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template); // Replace placeholders
        }
        $this->mail->Body = $template; 
        return $this;
    }

    // Add CC recipient
    public function addCC($cc) {
        $this->mail->addCC($cc);
        return $this;
    }

    // Add BCC recipient
    public function addBCC($bcc) {
        $this->mail->addBCC($bcc);
        return $this;
    }

    // Set email priority
    public function setPriority($priority = 3) {
        $this->mail->Priority = $priority;
        return $this;
    }

    // Execute sending the email with fallback to mail()
    public function execute() {
        try {
            $this->mail->send(); // Attempt to send email
        } catch (Exception $e) {
            if (!$this->fallbackToMail()) {
                throw new Exception("Message could not be sent. Error: {$this->mail->ErrorInfo}"); // Handle sending errors
            }
        }
    }

    // Fallback method to send email using mail()
    private function fallbackToMail() {
        $to = $this->mail->getToAddresses()[0][0];
        $subject = $this->mail->Subject;
        $body = $this->mail->Body;
        $headers = "From: {$this->mail->From}\r\n";
        $headers .= "Reply-To: {$this->mail->ReplyTo[0][0] ?? $this->mail->From}\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";

        return mail($to, $subject, $body, $headers); // Attempt to send via mail()
    }

    // Add tracking pixel to the email body
    public function addTrackingPixel($trackingUrl) {
        $this->mail->Body .= '<img src="' . $trackingUrl . '" style="display:none;">';
        return $this;
    }

    // Set Reply-To address
    public function setReplyTo($email, $name = '') {
        $this->mail->addReplyTo($email, $name);
        return $this;
    }

    // Add custom header to the email
    public function addHeader($header, $value) {
        $this->mail->addCustomHeader($header, $value);
        return $this;
    }
}
