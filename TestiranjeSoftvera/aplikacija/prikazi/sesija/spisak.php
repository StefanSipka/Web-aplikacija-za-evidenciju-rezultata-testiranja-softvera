<section class="tabela-sekcija">
    <div class="naslov-akcije">
        <h2>Lista test sesija</h2>
        <a href="<?php echo BASE_URL; ?>sesije/kreiraj" class="btn-dodaj">+ Nova sesija</a>
    </div>

    <!-- Filter forma -->
    <form method="GET" class="filter-forma">
        <div class="filter-grupa">
            <input type="text" name="filter" class="filter-input" 
                   placeholder="Filter po nazivu projekta..." 
                   value="<?php echo htmlspecialchars($filter ?? ''); ?>">
            <button type="submit" class="btn-filter">Filtriraj</button>
            <a href="<?php echo BASE_URL; ?>sesije" class="btn-sve">Prikaži sve</a>

            <!-- Novo dugme za štampu trenutnog spiska/tabele -->
            <button type="button" onclick="window.print();" class="btn-stampa">Štampaj spisak</button>

        </div>
    </form>

    <!-- Tabela -->
    <table class="tabela">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naziv projekta</th>
                <th>Verzija</th>
                <th>Tester</th>
                <th>Okruženje</th>
                <th>Broj slučajeva</th>
                <th>Datum</th>
                <th class="akcije1">Akcije</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($repo) && $repo->brojRedova > 0): 
                $podaci = $repo->sviKaoNiz();
                foreach($podaci as $red): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($red['sesijaId']); ?></td>
                        <td><?php echo htmlspecialchars($red['nazivProjekta']); ?></td>
                        <td><?php echo htmlspecialchars($red['verzija'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($red['imeTestera']); ?></td>
                        <td><?php echo htmlspecialchars($red['okruzenje'] ?? '-'); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($red['brojSlucajeva'] ?? 0); ?></td>
                        <td><?php echo htmlspecialchars($red['datumKreiranja']); ?></td>
                        <td class="akcije">
                            <a href="<?php echo BASE_URL; ?>sesije/<?php echo $red['sesijaId']; ?>" class="btn-pregled">Pregled</a>
                            <a href="<?php echo BASE_URL; ?>sesije/<?php echo $red['sesijaId']; ?>/izmeni" class="btn-izmeni">Izmeni</a>
                            <a href="<?php echo BASE_URL; ?>sesije/<?php echo $red['sesijaId']; ?>/stampaj" class="btn-stampa" target="_blank">Štampaj</a>
                            <a href="#" class="btn-obrisi" data-id="<?php echo $red['sesijaId']; ?>" 
                               onclick="return potvrdiBrisanje('<?php echo $red['nazivProjekta']; ?>', this);">
                                Obriši
                            </a>
                        </td>
                    </tr>
                <?php endforeach; 
            else: ?>
                <tr>
                    <td colspan="8" class="text-center">Nema registrovanih test sesija.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php 
$potrebneSkripte = ['sesijaAjax.js', 'validacija.js']; 
?>