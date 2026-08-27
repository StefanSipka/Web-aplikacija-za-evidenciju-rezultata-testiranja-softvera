<?php
namespace Aplikacija\Jezgro;

class Ruter {
    private $rute;

    public function __construct($rute) {
        $this->rute = $rute;
    }

    public function usmeri($uri, $metod) {
        $uri = strtok($uri, '?');
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->rute as $ruta) {
            list($metodRute, $putanja, $kontrolerAkcija) = $ruta;
            if ($metodRute !== $metod) continue;
            $pattern = preg_replace('/\{[a-z]+\}/', '([^/]+)', $putanja);
            if (preg_match("#^$pattern$#", $uri, $poklapanja)) {
                array_shift($poklapanja);
                list($imeKontrolera, $akcija) = explode('@', $kontrolerAkcija);
                $klasa = "Aplikacija\\Kontroleri\\$imeKontrolera";
                if (class_exists($klasa)) {
                    $kontroler = new $klasa();
                    call_user_func_array([$kontroler, $akcija], $poklapanja);
                    return;
                }
            }
        }
        http_response_code(404);
        echo "404 - Stranica nije pronađena";
    }
}