<section class="pregled-sekcija">
    <div class="naslov-akcije">
        <h2>Pregled test sesije</h2>
        <div class="akcije-grupa">
            <a href="<?php echo BASE_URL; ?>sesije/<?php echo $sesija->getSesijaId(); ?>/izmeni" class="btn-izmeni">Izmeni</a>
            <a href="<?php echo BASE_URL; ?>sesije/<?php echo $sesija->getSesijaId(); ?>/stampaj" class="btn-stampa" target="_blank">Štampaj</a>
            <a href="<?php echo BASE_URL; ?>sesije" class="btn-nazad">← Nazad</a>
        </div>
    </div>

    <!-- Podaci o sesiji -->
    <div class="kartica-podaci">
        <div class="red-podataka">
            <span class="oznaka">ID sesije:</span>
            <span class="vrednost"><?php echo htmlspecialchars($sesija->getSesijaId()); ?></span>
        </div>
        <div class="red-podataka">
            <span class="oznaka">Naziv projekta:</span>
            <span class="vrednost"><?php echo htmlspecialchars($sesija->getNazivProjekta()); ?></span>
        </div>
        <div class="red-podataka">
            <span class="oznaka">Verzija:</span>
            <span class="vrednost"><?php echo htmlspecialchars($sesija->getVerzija() ?? '-'); ?></span>
        </div>
        <div class="red-podataka">
            <span class="oznaka">Tester:</span>
            <span class="vrednost"><?php echo htmlspecialchars($sesija->getImeTestera()); ?></span>
        </div>
        <div class="red-podataka">
            <span class="oznaka">Okruženje:</span>
            <span class="vrednost"><?php echo htmlspecialchars($sesija->getOkruzenje() ?? '-'); ?></span>
        </div>
        <div class="red-podataka">
            <span class="oznaka">Datum kreiranja:</span>
            <span class="vrednost"><?php echo htmlspecialchars($sesija->getDatumKreiranja()); ?></span>
        </div>
        <div class="red-podataka">
            <span class="oznaka">Komentar:</span>
            <span class="vrednost"><?php echo htmlspecialchars($sesija->getKomentar() ?? '-'); ?></span>
        </div>
    </div>

    <!-- Test slučajevi -->
    <div class="slucajevi-sekcija">
        <h3>Test slučajevi (<?php echo count($slucajevi); ?>)</h3>

        <table class="tabela">
            <thead>
                <tr>
                    <th>Rb.</th>
                    <th>Opis</th>
                    <th>Očekivani rezultat</th>
                    <th>Stvarni rezultat</th>
                    <th>Status</th>
                    <th>Komentar</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($slucajevi) > 0): ?>
                    <?php foreach($slucajevi as $slucaj): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($slucaj->getRedniBroj()); ?></td>
                            <td><?php echo htmlspecialchars($slucaj->getOpis()); ?></td>
                            <td><?php echo htmlspecialchars($slucaj->getOcekivaniRezultat() ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($slucaj->getStvarniRezultat() ?? '-'); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($slucaj->getStatusNaziv() ?? 'nepoznat'); ?>">
                                    <?php echo htmlspecialchars($slucaj->getStatusNaziv() ?? 'Nije testiran'); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($slucaj->getKomentar() ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Nema definisanih test slučajeva.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>