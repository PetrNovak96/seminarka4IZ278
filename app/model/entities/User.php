<?php


namespace app\model\entities;

/**
 * Class Employee
 * @package app\model\entities
 * @property string $password
 */
class User extends Employee {

    public static function encodePassword($password){
        return password_hash($password,PASSWORD_BCRYPT);
    }

    public static function verifyPassword($password, $passwordHash){
        return password_verify($password,$passwordHash);
    }

    public function isValidPassword($password){
        return self::verifyPassword($password,$this->password);
    }
}