<?php

$OTP_DURATION = 60; // 60 seconds

function generateSecret($length = 16)
{
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $secret = '';
    for ($i = 0; $i < $length; $i++) {
        $secret .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $secret;
}

function base32Decode($secret)
{
    if (empty($secret)) return '';

    $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $base32charsFlipped = array_flip(str_split($base32chars));

    $paddingCharCount = substr_count($secret, '=');
    $allowedValues = [6, 4, 3, 1, 0];
    if (!in_array($paddingCharCount, $allowedValues)) return false;

    $secret = str_replace('=', '', $secret);
    $binaryString = '';

    for ($i = 0; $i < strlen($secret); $i++) {
        $binaryString .= str_pad(
            base_convert($base32charsFlipped[$secret[$i]], 10, 2),
            5,
            '0',
            STR_PAD_LEFT
        );
    }

    $eightBits = str_split($binaryString, 8);
    $decoded = '';

    foreach ($eightBits as $bits) {
        if (strlen($bits) == 8) {
            $decoded .= chr(base_convert($bits, 2, 10));
        }
    }

    return $decoded;
}

function getTOTP($secret, $timeSlice = null)
{
    global $OTP_DURATION;
    if ($timeSlice === null) {
        $timeSlice = floor(time() / $OTP_DURATION);
    }

    $secretKey = base32Decode($secret);

    $time = pack('N*', 0) . pack('N*', $timeSlice);

    $hash = hash_hmac('sha1', $time, $secretKey, true);

    $offset = ord(substr($hash, -1)) & 0x0F;

    $truncatedHash =
        ((ord($hash[$offset]) & 0x7F) << 24) |
        ((ord($hash[$offset + 1]) & 0xFF) << 16) |
        ((ord($hash[$offset + 2]) & 0xFF) << 8) |
        (ord($hash[$offset + 3]) & 0xFF);

    return str_pad($truncatedHash % 1000000, 6, '0', STR_PAD_LEFT);
}

function verifyTOTP($secret, $inputOTP, $discrepancy = 1)
{
    global $OTP_DURATION;
    if (empty($secret) || !is_string($inputOTP)) return false;

    $currentTimeSlice = floor(time() / $OTP_DURATION);

    for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
        $calculated = getTOTP($secret, $currentTimeSlice + $i);
        if (hash_equals($calculated, str_pad((string)$inputOTP, 6, '0', STR_PAD_LEFT))) {
            return true;
        }
    }

    return false;
}

function verifyTOTPAtTime($secret, $inputOTP, $time, $discrepancy = 1)
{
    global $OTP_DURATION;
    if (empty($secret) || !is_string($inputOTP)) return false;

    $timeSlice = floor($time / $OTP_DURATION);

    for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
        $calculated = getTOTP($secret, $timeSlice + $i);
        if (hash_equals($calculated, str_pad((string)$inputOTP, 6, '0', STR_PAD_LEFT))) {
            return true;
        }
    }

    return false;
}