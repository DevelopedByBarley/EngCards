<?php
    class EmailVerification{
        private $pdo;
        private $mailer;

        public function __construct()
        {
            $db = new Database();
            $this->pdo = $db->getConnect();
            $this->mailer = new Mailer();
        }


        public function verificationEmail($email)
    {

        session_start();
        $verificationCode = uniqid(1000, 9999);
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $subject = "Email cím megerősítése!";
        $emailBody = "";

        $_SESSION["emailVerificationCode"] = $verificationCode;

        if (!$user) {
            $emailBody .= "
            <h1>A te email hitelesítő kódód:</h1>
            <h3>$verificationCode</h3> 
        ";
        } else {
            $emailBody .= "
            <h1>Ezen az email címen már egyszer regisztráltál , vagy valaki megpróbált a te adataiddal regisztrálni!</h1>
            <p>Kérlek ezen a linken <a href=\"#\">link</a> próbálj meg belépni!</p> 
        ";
        }

        $this->mailer->send($email, $emailBody, $subject);
    }

    public function sendVerification($verificationCode)
    {
        session_start();
        $isVerified = $verificationCode === (int)$_SESSION["emailVerificationCode"];
        if (!$isVerified) {
            echo json_encode(
                ["state" => false]
            );
            return;
        };

        $_SESSION["isEmailVerified"] = true;

        echo json_encode(
            ["state" => true]
        );
    }


    }