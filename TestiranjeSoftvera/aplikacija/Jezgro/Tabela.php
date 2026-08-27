<?php
namespace Aplikacija\Jezgro;

class Tabela {
    public $konekcija;
    public $nazivTabele;
    public $skup;
    public $brojRedova;

    public function __construct(Konekcija $konekcija, $nazivTabele) {
        $this->konekcija = $konekcija;
        $this->nazivTabele = $nazivTabele;
    }

    public function ucitajSve($upit) {
        $rez = mysqli_query($this->konekcija->veza, $upit);
        if (!$rez) {
            throw new \Exception("Greška u upitu: " . mysqli_error($this->konekcija->veza));
        }
        $this->skup = $rez;
        $this->brojRedova = mysqli_num_rows($rez);
    }

    public function izvrsiUpit($upit) {
        $rez = mysqli_query($this->konekcija->veza, $upit);
        if (!$rez) {
            return mysqli_error($this->konekcija->veza);
        }
        return '';
    }

    public function vrednostPoRedu($skup, $redniBroj, $brojPolja) {
        mysqli_data_seek($skup, $redniBroj);
        $red = mysqli_fetch_row($skup);
        return $red[$brojPolja] ?? null;
    }

    public function sviKaoNiz() {
        $niz = [];
        if ($this->brojRedova > 0) {
            mysqli_data_seek($this->skup, 0);
            while ($red = mysqli_fetch_assoc($this->skup)) {
                $niz[] = $red;
            }
            mysqli_data_seek($this->skup, 0);
        }
        return $niz;
    }
}