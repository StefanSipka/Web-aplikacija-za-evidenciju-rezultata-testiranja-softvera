<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zapisnik o izvršenom testiranju - Sesija #<?php echo htmlspecialchars($sesija->getSesijaId()); ?></title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>javno/css/stilStampa.css">
    
  
</head>
<body>

    <div class="stranica-papir">
        <!-- Fakultetsko zaglavlje -->
        <div class="faks-zaglavlje">
            <h3>Univerzitet u Novom Sadu</h3>
            <h3>Tehnički fakultet „Mihajlo Pupin“</h3>
            <p>Zrenjanin, Đure Đakovića bb</p>
            <p>Šk. 2025/26 - Veb programiranje</p>
        </div>

        <div class="oznaka-obrazac">Obrazac TR-01</div>
        <div class="naslov-obrasca">ZAPISNIK O IZVRŠENOM TESTIRANJU</div>

        <!-- Podaci o test sesiji -->
        <div class="sekcija-naslov">PODACI O TEST SESIJI (master)</div>
        <table class="tabela-detalji">
            <tr>
                <td style="width: 20%;"><strong>ID Test sesije:</strong></td>
                <td style="width: 30%;" class="linija-polje"><?php echo htmlspecialchars($sesija->getSesijaId()); ?></td>
                <td style="width: 20%;"><strong>Datum testiranja:</strong></td>
                <td style="width: 30%;" class="linija-polje"><?php echo htmlspecialchars($sesija->getDatumKreiranja()); ?></td>
            </tr>
            <tr>
                <td><strong>Naziv projekta:</strong></td>
                <td colspan="3" class="linija-polje"><?php echo htmlspecialchars($sesija->getNazivProjekta()); ?> (v. <?php echo htmlspecialchars($sesija->getVerzija() ?? '1.0'); ?>)</td>
            </tr>
            <tr>
                <td><strong>Ime testera:</strong></td>
                <td class="linija-polje"><?php echo htmlspecialchars($sesija->getImeTestera()); ?></td>
                <td><strong>Okruženje:</strong></td>
                <td class="linija-polje"><?php echo htmlspecialchars($sesija->getOkruzenje() ?? '-'); ?></td>
            </tr>
            <tr>
                <td><strong>Opšti komentar:</strong></td>
                <td colspan="3" class="linija-polje" style="height: 35px;"><?php echo htmlspecialchars($sesija->getKomentar() ?? '-'); ?></td>
            </tr>
        </table>

        <!-- Test slučajevi -->
        <div class="sekcija-naslov">REZULTATI TEST SLUČAJEVA (detail - <?php echo count($slucajevi); ?> stavki)</div>
        <table class="tabela-slucajevi">
            <thead>
                <tr>
                    <th style="width: 5%;">Rb.</th>
                    <th style="width: 25%;">Opis testa</th>
                    <th style="width: 20%;">Očekivani rezultat</th>
                    <th style="width: 20%;">Stvarni rezultat</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 18%;">Komentar</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($slucajevi) > 0): ?>
                    <?php foreach($slucajevi as $slucaj): ?>
                        <tr>
                            <td class="text-center"><?php echo htmlspecialchars($slucaj->getRedniBroj()); ?></td>
                            <td><?php echo htmlspecialchars($slucaj->getOpis()); ?></td>
                            <td><?php echo htmlspecialchars($slucaj->getOcekivaniRezultat() ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($slucaj->getStvarniRezultat() ?? '-'); ?></td>
                            <td class="text-center">
                                <strong><?php echo htmlspecialchars($slucaj->getStatusNaziv() ?? 'Nije testiran'); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($slucaj->getKomentar() ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Nema unetih test slučajeva.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Potpisi i overe prema obrascu -->
        <div class="potpisi-sekcija">
            <div class="red-potpisa">
                <div>
                    <strong>Zapisnik sačinio (tester):</strong><br>
                    <span style="display:inline-block; margin-top: 15px; border-bottom: 1px dotted #000; width: 220px;"><?php echo htmlspecialchars($sesija->getImeTestera()); ?></span>
                </div>
                <div>
                    <strong>Datum overe:</strong><br>
                    <span style="display:inline-block; margin-top: 15px; border-bottom: 1px dotted #000; width: 150px;"><?php echo date('d.m.Y.'); ?></span>
                </div>
            </div>

            <div class="red-potpisa" style="margin-top: 30px;">
                <div class="potpis-box">
                    <strong>Odobrio vođa tima:</strong>
                    <div class="linija-potpis"></div>
                    <span style="font-size: 10px; color: #555;">(potpis)</span>
                </div>
                <div class="potpis-box">
                    <strong>QA menadžer:</strong>
                    <div class="linija-potpis"></div>
                    <span style="font-size: 10px; color: #555;">(potpis)</span>
                </div>
            </div>
        </div>

        <!-- Dugmići za interakciju na sajtu (ne vide se pri štampanju) -->
        <div class="ekran-akcije">
            <button onclick="window.print()" class="btn btn-print">Štampaj ponovo</button>
            <a href="<?php echo BASE_URL; ?>sesije/<?php echo $sesija->getSesijaId(); ?>" class="btn btn-nazad">← Nazad na pregled sesije</a>
        </div>
    </div>

</body>
</html>