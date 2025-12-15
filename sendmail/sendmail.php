#!/usr/bin/env php

<?php
$mail = '';
while (false !== ($line = fgets(STDIN))) {
    $mail .= $line;
}

$mail = preg_replace(['/\=3D/', '/\=0A/', '/\=\s+|\=0D|\=20|\=C2|\=AE|\=E2|\=80|\=AF/', '/\=99/'], ['=', ' ', '', '`'], $mail);
file_put_contents('/Users/rand/Sites/_MAILS/mail.' . time() . '.html', $mail . PHP_EOL, FILE_APPEND);
echo 1;
