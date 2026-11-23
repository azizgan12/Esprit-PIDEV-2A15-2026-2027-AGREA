
<?php

class User
{
    private $user_id;
    private $name;
    private $birth_date;
    private $country;
    private $phone_number;
    private $mail;
    private $password;
    private $profile_picture;
    private $role;
    private $created_at;

    public function __construct(
        $name = "",
        $birth_date = null,
        $country = "",
        $phone_number = "",
        $mail = "",
        $password = "",
        $profile_picture = null,
        $role = "farmer"
    ) {
        $this->name = $name;
        $this->birth_date = $birth_date;
        $this->country = $country;
        $this->phone_number = $phone_number;
        $this->mail = $mail;
        $this->password = $password;
        $this->profile_picture = $profile_picture;
        $this->role = $role;
    }

    // Getters

    public function getUserId() {
        return $this->user_id;
    }

    public function getName() {
        return $this->name;
    }

    public function getBirthDate() {
        return $this->birth_date;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getPhoneNumber() {
        return $this->phone_number;
    }

    public function getMail() {
        return $this->mail;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getProfilePicture() {
        return $this->profile_picture;
    }

    public function getRole() {
        return $this->role;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    // Setters

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setBirthDate($birth_date) {
        $this->birth_date = $birth_date;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function setPhoneNumber($phone_number) {
        $this->phone_number = $phone_number;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setProfilePicture($profile_picture) {
        $this->profile_picture = $profile_picture;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
}
