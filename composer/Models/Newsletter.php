<?php

namespace Titus\Dolmen\Models;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//require 'vendor/autoload.php';

class Newsletter
{
    private ?int $id = null;
    private string $title;
    private string $content;
    private string $status;
    private \DateTime $createdAt;
    private ?\DateTime $sentAt = null;
    private int $createdBy;

    public static function create(array $data): bool
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
                INSERT INTO newsletters (title, content, status, created_by)
                VALUES (?, ?, ?, ?)
            ");
            return $stmt->execute([
                $data['title'],
                $data['content'],
                'draft',
                $data['created_by']
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la création de la newsletter : " . $e->getMessage());
            return false;
        }
    }

    public function send(): bool
    {
        try {
            $subscribers = NewsletterSubscription::getAllActiveSubscribers();

            foreach ($subscribers as $subscriber) {
                $this->sendEmail(
                    $subscriber['email'],
                    $subscriber['firstname_user'],
                    $subscriber['name_user']
                );
            }

            // Mettre à jour le statut de la newsletter
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->prepare("
                UPDATE newsletters 
                SET status = 'sent', sent_at = NOW() 
                WHERE id_newsletter = ?
            ");
            return $stmt->execute([$this->id]);

        } catch (\Exception $e) {
            error_log("Erreur lors de l'envoi de la newsletter : " . $e->getMessage());
            return false;
        }
    }

    private function sendEmail(string $email, string $firstname, string $lastname): void
    {
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP (Gmail)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['ADMIN_MAIL']; // Ton adresse Gmail
            $mail->Password = $_ENV['ADMIN_MAIL_PASSWORD']; // Mot de passe d'application généré
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Sécurisation TLS (ou SSL)
            $mail->Port = 587; // Port SMTP (587 pour TLS, 465 pour SSL)

            // Expéditeur
            $mail->setFrom($_ENV['ADMIN_MAIL'], 'Ton Nom');

            // Destinataire
            $mail->addAddress($email, "$firstname $lastname");

            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = 'Newsletter';
            $mail->Body    = "<p>Bonjour <strong>$firstname $lastname</strong>,</p>
                          <p>Voici notre dernière newsletter...</p>";

            // Envoi de l'email
            $mail->send();
            error_log("✅ Email envoyé avec succès à $firstname $lastname ($email)");
        } catch (Exception $e) {
            error_log("❌ Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}");
        }
    }

    public static function getAllNewsletters(): array
    {
        try {
            $conn = Connexion::getInstance()->getConn();
            $stmt = $conn->query("
                SELECT n.*, u.firstname_user, u.name_user 
                FROM newsletters n
                JOIN users u ON n.created_by = u.id_user
                ORDER BY n.created_at DESC
            ");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des newsletters : " . $e->getMessage());
            return [];
        }
    }
}