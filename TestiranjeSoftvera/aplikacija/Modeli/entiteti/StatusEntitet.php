<?php
namespace Aplikacija\Modeli\Entiteti;

class StatusEntitet {
    private $statusId;
    private $naziv;

    public function __construct($statusId = null, $naziv = null) {
        $this->statusId = $statusId;
        $this->naziv = $naziv;
    }

    public function getStatusId() { return $this->statusId; }
    public function getNaziv() { return $this->naziv; }

    public function setStatusId($statusId) { $this->statusId = $statusId; }
    public function setNaziv($naziv) { $this->naziv = $naziv; }
}