#!/usr/bin/env php

<?php
$mail = '';
while (false !== ($line = fgets(STDIN))) {
    $mail .= $line;
}

$mail = preg_replace(['/\=3D/', '/\=0A/', '/\=\s+/'], ['=', ' ', ''], $mail);
file_put_contents('/Users/rand/Sites/_MAILS/mail.' . time() . '.html', $mail . PHP_EOL, FILE_APPEND);
echo 1;
