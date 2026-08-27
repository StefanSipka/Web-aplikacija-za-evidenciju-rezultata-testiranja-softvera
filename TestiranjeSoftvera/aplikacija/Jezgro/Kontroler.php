<?php
namespace Aplikacija\Jezgro;

class Kontroler {
    protected function prikazi($stranica, $podaci = []) {
        extract($podaci);
        include __DIR__ . "/../prikazi/osnova/zaglavlje.php";
        include __DIR__ . "/../prikazi/$stranica.php";
        include __DIR__ . "/../prikazi/osnova/podnozje.php";
    }

    protected function odgovoriJson($podaci) {
        header('Content-Type: application/json');
        echo json_encode($podaci, JSON_UNESCAPED_UNICODE);
        exit;
    }
}