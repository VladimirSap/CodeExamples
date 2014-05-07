<?php

class UsersStorageModel {
    // Сгруппированы методы, работающие
    // с записями пользователей в файле htpasswd

    CONST FILE_NAME = 'htpasswd';
    CONST HASH_ITERATIONS = 10;

    private static $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance() {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    static function getHash($string, $salt) {
        for ($i = 0; $i < self::HASH_ITERATIONS; $i++) {
            $string = sha1($string . $salt);
        }
        return $string;
    }

    public static function saveUser($user, $hash, $salt) {
        $iterations = self::HASH_ITERATIONS;
        $str = "$user:$hash:$salt:$iterations\n";
        if(file_put_contents(self::FILE_NAME, $str, FILE_APPEND)) {
            return true;
        } else {
            return false;
        }
    }

    public static function userExists($login) {
        if(!is_file(self::FILE_NAME)) {
            return false;
        }

        $users = file(self::FILE_NAME);

        foreach($users as $user) {
            if(strpos($user, $login) !== false) {
                return $user;
            }
        }

        return false;
    }
}