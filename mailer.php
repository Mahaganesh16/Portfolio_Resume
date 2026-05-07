<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';

require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST["phone"]));
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Robust dynamic recipient search from content configuration
    $p_content = get_portfolio_content();
    $recipient_email = 'srinithiperiyasamy007@gmail.com'; // Default fallback
    
    // 1. Check contact section first
    if (!empty($p_content['contact']['email']) && filter_var($p_content['contact']['email'], FILTER_VALIDATE_EMAIL) && $p_content['contact']['email'] !== 'example@email.com') {
        $recipient_email = trim($p_content['contact']['email']);
    } 
    // 2. Fallback to customization sidebar email
    elseif (!empty($p_content['customization']['sidebar_email']) && filter_var($p_content['customization']['sidebar_email'], FILTER_VALIDATE_EMAIL) && $p_content['customization']['sidebar_email'] !== 'example@email.com') {
        $recipient_email = trim($p_content['customization']['sidebar_email']);
    }
    // 3. Fallback to personal details Email
    elseif (!empty($p_content['about']['details']) && is_array($p_content['about']['details'])) {
        foreach ($p_content['about']['details'] as $detail) {
            if (isset($detail['key']) && strtolower(trim($detail['key'])) === 'email' && !empty($detail['value']) && filter_var($detail['value'], FILTER_VALIDATE_EMAIL) && $detail['value'] !== 'example@email.com') {
                $recipient_email = trim($detail['value']);
                break;
            }
        }
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'st.srinithi@gmail.com'; // Your Gmail address
        $mail->Password   = 'apzv elcz gggq qvbd';   // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Bypass SSL Certificate Verification on local environments (like XAMPP)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Recipients
        $mail->setFrom('st.srinithi@gmail.com', 'Portfolio Contact');
        $mail->addAddress($recipient_email);     // Add a recipient
        $mail->addReplyTo($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Message: $subject";
        
        $body = "<h2>New Message from Portfolio</h2>";
        $body .= "<p><strong>Name:</strong> $name</p>";
        $body .= "<p><strong>Email:</strong> $email</p>";
        $body .= "<p><strong>Phone:</strong> $phone</p>";
        $body .= "<p><strong>Subject:</strong> $subject</p>";
        $body .= "<p><strong>Message:</strong><br>" . nl2br($message) . "</p>";
        
        $mail->Body = $body;

        $mail->send();
        http_response_code(200);
        echo "Thank You! Your message has been sent successfully.";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Oops! Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>
