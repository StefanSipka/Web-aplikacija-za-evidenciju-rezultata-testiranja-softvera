<?php
session_start();

define('BASE_URL', '/TestiranjeSoftvera/');

spl_autoload_register(function ($imeKlase) {
    $prefiks = 'Aplikacija\\';
    $osnovniFolder = __DIR__ . '/../aplikacija/';
    if (strpos($imeKlase, $prefiks) === 0) {
        $relativnaPutanja = substr($imeKlase, strlen($prefiks));
        $putanja = $osnovniFolder . str_replace('\\', '/', $relativnaPutanja) . '.php';
        if (file_exists($putanja)) require $putanja;
    }
});

$rute = require __DIR__ . '/../rute/web.php';
$ruter = new Aplikacija\Jezgro\Ruter($rute);

$punUri = $_SERVER['REQUEST_URI']; 
$osnova = rtrim(BASE_URL, '/'); 
if (strpos($punUri, $osnova) === 0) { 
    $punUri = substr($punUri, strlen($osnova)); 
} 

$putanja = strtok($punUri, '?') ?: '/';

// Ako je korisnik na početnoj stranici ('/') i nije prijavljen, preusmeri ga na prijavu
if (($putanja === '/' || $putanja === '') && !isset($_SESSION['korisnikId'])) {
    header('Location: ' . BASE_URL . 'prijava');
    exit;
}

$ruter->usmeri($putanja, $_SERVER['REQUEST_METHOD']);