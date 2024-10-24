# PHP Mailer Class

A simple and flexible PHP mailer class utilizing PHPMailer for sending emails with support for SMTP, attachments, templates, and fallback to the native `mail()` function. Ideal for developers looking to streamline email functionality in their PHP applications.

## Features
- Send emails via SMTP with TLS encryption
- Attach files and set CC/BCC recipients
- Load HTML templates and replace placeholders
- Add custom headers and set reply-to addresses
- Fallback to native `mail()` function if SMTP fails
- Support for HTML and plain text emails
- Easily extendable with fluent interface for method chaining

## Usage
```php
Mailer::send($to, $subject, $body, $altBody = '')
    ->addAttachment($filePath, $fileName = '')
    ->loadTemplate($templatePath, $data = [])
    ->setPriority($priority = 3)
    ->addCC($cc)
    ->addBCC($bcc)
    ->setReplyTo($replyToEmail, $replyToName)
    ->execute();
```

## Installation

1. Install PHPMailer via Composer:
```terminal
composer require phpmailer/phpmailer
```

2. Include the Mailer class in your project:
```php
require 'Mailer.php';
```
