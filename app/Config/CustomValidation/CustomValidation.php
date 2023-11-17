<?php
namespace Config\CustomValidation;
class CustomValidation {
    public function account_check(string $str, string $error = null) {
        if (strlen($str) > 20){
            return false;
        }
        return true;
    }
    public function password_check(string $str, string $error = null) {
        if (strlen($str) < 8 || strlen($str) >20) return false;
        return true;
    }
}