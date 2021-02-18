<?php

function debug($var)
{
	echo '<pre style="height:150px;overflow-y: scroll;font-size:.8em;padding: 10px; font-family: Consolas, Monospace; background-color: #000; color: #fff;">';
	print_r($var);
	echo '</pre>';
}

function clean($string)
{
    return trim(strip_tags($string));
}

function textValid($err,$value,$key,$min,$max,$empty = true)
{
    if(!empty($value)) {
        if(mb_strlen($value) < $min) {
            $err[$key] = 'Min '.$min.' caracteres';
        } elseif (mb_strlen($value) > $max) {
            $err[$key] = 'Max '.$max.' caracteres';
        }
    } else {
        if($empty) {
            $err[$key] = 'Veuillez renseigner ce champ';
        }
    }
    return $err;
}

function emailValidation($err,$mail,$key)
{
    if(!empty($mail)) {
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $err[$key] = 'Email non valide';
        }
    } else {
        $err[$key] = 'Veuillez renseigner ce champ';
    }
    return $err;
}

// Met le premier caractère en majuscule (même les accents)
function mb_ucfirst($string, $encoding = 'utf-8') {
    if (empty($string) || !is_string($string)) {
        return null;
    }
    
    $strLen = mb_strlen($string, $encoding);
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, $strLen - 1, $encoding);
    
    return mb_strtoupper($firstChar, $encoding) . $then;
}

// DATE TO AGE
function ageCalculator($dob) {
    if(!empty($dob)) {
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        return $age;
    } else {
        return 0;
    }
}