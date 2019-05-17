<?php


namespace app\model;


use PDO;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class UsersModel extends Model {

    public function encodePassword($password){
        return password_hash($password,PASSWORD_BCRYPT);
    }

    private function verifyPassword($password, $hash){
        return password_verify($password, $hash);
    }

    public function isValidPassword($password, $userId){
        $sql = <<<SQL
SELECT 
E.PASSWORD
FROM EMPLOYEES E
WHERE E.ID = :id;
SQL;
        $query=self::$pdo->prepare($sql);
        $query->bindValue(':id', $userId, PDO::PARAM_STR);
        $query->execute();
        $hash = $query->fetch(PDO::FETCH_ASSOC)['PASSWORD'];
        return $this->verifyPassword($password, $hash);
    }

    public function findUser($email) {
        $sql = <<<SQL
SELECT 
* 
FROM EMPLOYEES
WHERE PASSWORD IS NOT NULL 
AND EMAIL = :email LIMIT 1;
SQL;
        $query=self::$pdo->prepare($sql);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function existsUser($email) {
        $sql = <<<SQL
SELECT 
    ID
FROM EMPLOYEES
WHERE EMAIL = :email
AND PASSWORD IS NOT NULL
AND GONE IS NULL;    
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        return !empty($query->fetch(PDO::FETCH_ASSOC));
    }

    public function existsEmployee($email) {
        $sql = <<<SQL
SELECT 
    ID
FROM EMPLOYEES
WHERE EMAIL = :email
AND PASSWORD IS NULL
AND GONE IS NULL;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        return !empty($query->fetch(PDO::FETCH_ASSOC));
    }

    public function setPassword($email, $password) {
        $hash = $this->encodePassword($password);
            $sql = <<<SQL
UPDATE EMPLOYEES E SET 
E.PASSWORD = :hash
WHERE
E.EMAIL = :email;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':hash', $hash, PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        return $this->findUser($email)['ID'];
    }

    public function sendMail($email, $subject, $content){
        $mailer = new PHPMailer();
        $mailer->isSendmail();
        $mailer->addAddress($email);
        try {
            $mailer->setFrom("novp19@eso.vse.cz");
            $mailer->CharSet = 'utf-8';
            $mailer->Subject = $subject;
            $mailer->msgHTML($content);
            $mailer->send();
            $mailer->ErrorInfo;
        } catch (Exception $e) {

        }
    }

    public function registerNew(array $user) {
        $emloyeesModel = new EmployeesModel();
        $id = $emloyeesModel->saveEmployee($user);
        $this->setPassword($user['EMAIL'], $user['password1']);
        return $id;
    }

    public function sendForgottenPasswordEmail($email){
        $hash = $this->encodePassword($email.date('Y-m-d'));
        $this->insertFPHash($email, $hash);
        $subject = 'Zapomenuté heslo';
        $link = 'http://eso.vse.cz/~novp19/login/change/';
        $link .= $email.'/';
        $link .= $hash.'/';
        $content = '<p><a href="'.$link.'">Odkaz</a> na změnu hesla...</p>';
        $this->sendMail($email, $subject, $content);
    }

    private function insertFPHash($email, $hash) {
        $sql = <<<SQL
UPDATE EMPLOYEES E
SET E.FPHASH = :hash
WHERE
E.EMAIL = :email;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':hash', $hash, PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
    }

    public function isValidFPHash($email, $hash) {
        $sql = <<<SQL
SELECT 
    ID
FROM EMPLOYEES E
WHERE E.EMAIL = :email
AND E.FPHASH = :hash
AND PASSWORD IS NOT NULL
AND GONE IS NULL;    
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':hash', $hash, PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        return !empty($query->fetch(PDO::FETCH_ASSOC));
    }

    private function deleteHash($email) {
        $sql = <<<SQL
UPDATE EMPLOYEES E
SET E.FPHASH = NULL 
WHERE
E.EMAIL = :email;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
    }
}