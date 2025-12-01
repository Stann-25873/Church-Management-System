<?php

namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        // Server settings
        $this->mailer->isSMTP();
        $this->mailer->Host = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $_ENV['SMTP_USER'] ?? '';
        $this->mailer->Password = $_ENV['SMTP_PASS'] ?? '';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = $_ENV['SMTP_PORT'] ?? 587;

        // Recipients
        $this->mailer->setFrom($_ENV['SMTP_FROM'] ?? '', $_ENV['SMTP_FROM_NAME'] ?? 'Church Management System');
    }

    public function sendWelcomeEmail($to, $name)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to, $name);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Welcome to Our Church!';
            $this->mailer->Body = "
                <h2>Welcome to Our Church, {$name}!</h2>
                <p>Thank you for joining our church community. We're excited to have you as part of our family.</p>
                <p>You can now log in to your account to access member features, view events, and more.</p>
                <p>God bless,<br>The Church Management Team</p>
            ";
            $this->mailer->AltBody = "Welcome to Our Church, {$name}! Thank you for joining our church community. You can now log in to your account.";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Email sending failed: " . $this->mailer->ErrorInfo);
            return false;
        }
    }

    public function sendPasswordResetEmail($to, $name, $resetToken)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to, $name);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Password Reset Request';
            $this->mailer->Body = "
                <h2>Password Reset Request</h2>
                <p>Hello {$name},</p>
                <p>You have requested to reset your password. Click the link below to reset it:</p>
                <p><a href='http://localhost:8000/reset-password?token={$resetToken}'>Reset Password</a></p>
                <p>This link will expire in 1 hour.</p>
                <p>If you didn't request this, please ignore this email.</p>
            ";
            $this->mailer->AltBody = "Hello {$name}, You have requested to reset your password. Visit: http://localhost:8000/reset-password?token={$resetToken}";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Email sending failed: " . $this->mailer->ErrorInfo);
            return false;
        }
    }

    public function sendEventNotification($to, $name, $eventTitle, $eventDate)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to, $name);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Upcoming Church Event';
            $this->mailer->Body = "
                <h2>Upcoming Event: {$eventTitle}</h2>
                <p>Hello {$name},</p>
                <p>We have an upcoming event that you might be interested in:</p>
                <p><strong>Event:</strong> {$eventTitle}</p>
                <p><strong>Date:</strong> {$eventDate}</p>
                <p>We hope to see you there!</p>
            ";
            $this->mailer->AltBody = "Hello {$name}, Upcoming Event: {$eventTitle} on {$eventDate}. We hope to see you there!";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Email sending failed: " . $this->mailer->ErrorInfo);
            return false;
        }
    }
}
