<?php
namespace Aplikacija\Jezgro;

class Konekcija {
    public $veza;
    public $nazivBaze;
    private $host;
    private $korisnik;
    private $sifra;
    private $prefiks;

    public function __construct($putanjaXml) {
        $xml = simplexml_load_file($putanjaXml) or die("Greška: XML fajl nije pronađen.");
        $this->host = (string)$xml->host;
        $this->korisnik = (string)$xml->korisnik;
        $this->sifra = (string)$xml->sifra;
        $this->prefiks = (string)$xml->prefiks_baze_podataka;
        $this->nazivBaze = $this->prefiks . (string)$xml->naziv_baze_podataka;
    }

    public function otvori() {
        $this->veza = mysqli_connect($this->host, $this->korisnik, $this->sifra, $this->nazivBaze);
        if (!$this->veza) {
            die("Greška pri povezivanju: " . mysqli_connect_error());
        }
        mysqli_set_charset($this->veza, "utf8mb4");
    }

    public function zatvori() {
        mysqli_close($this->veza);
    }

    public function poslednjiId() {
        return mysqli_insert_id($this->veza);
    }

    public function ocisti($string) {
        return mysqli_real_escape_string($this->veza, $string);
    }
}