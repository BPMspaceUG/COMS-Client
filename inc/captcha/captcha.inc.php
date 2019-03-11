<?php

/**
 * Delete images older than 30 minutes
 */
foreach (glob($_SERVER['DOCUMENT_ROOT'] . "/inc/captcha/*.png") as $filename) {
    if (time()- filectime($filename) > 1800) {
        if (file_exists($filename)) unlink($filename);
    }
}

$message = '';
// generating expression
$expression = (object) array(
    "n1" => rand(0, 9),
    "n2" => rand(0, 9)
);
function generateImage($text, $file) {
    $im = @imagecreate(74, 25) or die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 200, 200, 200);
    $text_color = imagecolorallocate($im, 0, 0, 0);
    imagestring($im, 5, 5, 5,  $text, $text_color);
    imagepng($im, $file);
    imagedestroy($im);
}
$captchaImage = 'inc/captcha/captcha'.time().'.png';
//generateImage($expression->n1.' + '.$expression->n2.' =', $captchaImage);
// masking with alphabets
$alphabet = array('K', 'g', 'A', 'D', 'R', 'V', 's', 'L', 'Q', 'w');
$alphabetsForNumbers = array(
    array('K', 'g', 'A', 'D', 'R', 'V', 's', 'L', 'Q', 'w'),
    array('M', 'R', 'o', 'F', 'd', 'X', 'z', 'a', 'K', 'L'),
    array('H', 'Q', 'O', 'T', 'A', 'B', 'C', 'D', 'e', 'F'),
    array('T', 'A', 'p', 'H', 'j', 'k', 'l', 'z', 'x', 'v'),
    array('f', 'b', 'P', 'q', 'w', 'e', 'K', 'N', 'M', 'V'),
    array('i', 'c', 'Z', 'x', 'W', 'E', 'g', 'h', 'n', 'm'),
    array('O', 'd', 'q', 'a', 'Z', 'X', 'C', 'b', 't', 'g'),
    array('p', 'E', 'J', 'k', 'L', 'A', 'S', 'Q', 'W', 'T'),
    array('f', 'W', 'C', 'G', 'j', 'I', 'O', 'P', 'Q', 'D'),
    array('A', 'g', 'n', 'm', 'd', 'w', 'u', 'y', 'x', 'r')
);
$usedAlphabet = rand(0, 9);
$code = $alphabet[$usedAlphabet].
    $alphabetsForNumbers[$usedAlphabet][$expression->n1].
    $alphabetsForNumbers[$usedAlphabet][$expression->n2];
// process form submitting
function getIndex($alphabet, $letter) {
    for($i=0; $i<count($alphabet); $i++) {
        $l = $alphabet[$i];
        if($l === $letter) return $i;
    }
}
function getExpressionResult($code) {
    global $alphabet, $alphabetsForNumbers;
    $userAlphabetIndex = getIndex($alphabet, substr($code, 0, 1));
    $number1 = (int) getIndex($alphabetsForNumbers[$userAlphabetIndex], substr($code, 1, 1));
    $number2 = (int) getIndex($alphabetsForNumbers[$userAlphabetIndex], substr($code, 2, 1));
    return $number1 + $number2;
}