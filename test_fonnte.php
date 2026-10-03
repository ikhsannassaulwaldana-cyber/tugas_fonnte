<?php

require_once "config/config.php";
require_once "config/fonnte.php";

$target = "6289524959949";

$message = "Tes WhatsApp dari project Tugas Fonnte.";

$result = sendWhatsApp($target, $message);

echo "<pre>";
print_r($result);
echo "</pre>";