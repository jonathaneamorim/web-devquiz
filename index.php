<?php
// A diretiva display_errors simplesmente é um liga-desliga da exibição dos erros na saída do script.
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/app/router.php';