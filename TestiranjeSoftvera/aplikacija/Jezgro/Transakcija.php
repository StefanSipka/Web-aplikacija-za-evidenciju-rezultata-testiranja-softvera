<?php
namespace Aplikacija\Jezgro;

class Transakcija {
    private $konekcija;

    public function __construct(Konekcija $konekcija) {
        $this->konekcija = $konekcija;
    }

    public function zapocni() {
        mysqli_autocommit($this->konekcija->veza, false);
        mysqli_query($this->konekcija->veza, "START TRANSACTION");
    }

    public function zavrsi($greska) {
        if (empty($greska)) {
            mysqli_commit($this->konekcija->veza);
        } else {
            mysqli_rollback($this->konekcija->veza);
        }
        mysqli_autocommit($this->konekcija->veza, true);
    }
}