<?php

class User {

    private $id;
    private $name;
    private $surname;
    private $email;
    private $password;

    public function __construct($id = null, $name = null, $surname = null, $email = null, $password = null) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->setHashedPassword($password);
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = trim(filter_var($name, FILTER_SANITIZE_STRING));
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setSurname($surname) {
        $this->surname = trim(filter_var($surname, FILTER_SANITIZE_STRING));
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new InvalidArgumentException("Invalid email format");
        }
    }

    public function getHashedPassword() {
        return $this->password;
    }

    private function setHashedPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
}