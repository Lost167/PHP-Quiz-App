<?php

class User implements JsonSerializable {

    private $username;
    private $password;
    private $permissionLevel;

    public function __construct($username, $password, $permissionLevel) {
        $this->username = $username;
        $this->password = $password;
        $this->permissionLevel = $permissionLevel;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getPermissionLevel() {
        return $this->permissionLevel;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
