<div class="forma-sekcija">
    <h2>Izmena test sesije #<?php echo htmlspecialchars($sesija->getSesijaId()); ?></h2>
    
    <form action="<?php echo BASE_URL; ?>sesije/<?php echo $sesija->getSesijaId(); ?>" method="POST" class="forma" id="sesijaForma">
        

        <!-- Podaci o sesiji -->
        <div class="grupa-polja">
            <h3>Podaci o sesiji</h3>
            
            <div class="polje">
                <label for="nazivProjekta">Naziv projekta <span class="obavezno">*</span></label>
                <input type="text" id="nazivProjekta" name="nazivProjekta" 
                       value="<?php echo htmlspecialchars($sesija->getNazivProjekta()); ?>" required>
            </div>

            <div class="polje">
                <label for="verzija">Verzija softvera</label>
                <input type="text" id="verzija" name="verzija" 
                       value="<?php echo htmlspecialchars($sesija->getVerzija()); ?>">
            </div>

            <div class="polje">
                <label for="imeTestera">Ime testera <span class="obavezno">*</span></label>
                <input type="text" id="imeTestera" name="imeTestera" 
                       value="<?php echo htmlspecialchars($sesija->getImeTestera()); ?>" required>
            </div>

            <div class="polje">
                <label for="okruzenje">Okruženje (browser/OS)</label>
                <input type="text" id="okruzenje" name="okruzenje" 
                       value="<?php echo htmlspecialchars($sesija->getOkruzenje()); ?>">
            </div>

            <div class="polje">
                <label for="komentar">Komentar</label>
                <textarea id="komentar" name="komentar" rows="3"><?php echo htmlspecialchars($sesija->getKomentar()); ?></textarea>
            </div>
        </div>

        <!-- Stavke - test slučajevi -->
        <div class="grupa-polja">
            <h3>Test slučajevi</h3>
            
            <table class="stavke-tabela" id="tabelaSlucajeva">
                <thead>
                    <tr>
                        <th>Rb.</th>
                        <th>Opis <span class="obavezno">*</span></th>
                        <th>Očekivani rezultat</th>
                        <th>Stvarni rezultat</th>
                        <th>Status</th>
                        <th>Komentar</th>
                        <th>Akcija</th>
                    </tr>
                </thead>
                <tbody id="teloSlucajeva">
                    <?php 
                    $rb = 1;
                    foreach($slucajevi as $slucaj): ?>
                        <tr class="red-slucaja">
                            <td class="rb"><?php echo $rb; ?></td>
                            <td>
                                <input type="hidden" name="slucajId[]" value="<?php echo $slucaj->getSlucajId(); ?>">
                                <input type="text" name="opis[]" value="<?php echo htmlspecialchars($slucaj->getOpis()); ?>" required>
                            </td>
                            <td>
                                <input type="text" name="ocekivani[]" value="<?php echo htmlspecialchars($slucaj->getOcekivaniRezultat()); ?>">
                            </td>
                            <td>
                                <input type="text" name="stvarni[]" value="<?php echo htmlspecialchars($slucaj->getStvarniRezultat()); ?>">
                            </td>
                            <td>
                                <select name="statusId[]">
                                    <?php foreach($statusi as $status): ?>
                                        <option value="<?php echo $status->getStatusId(); ?>" 
                                            <?php echo ($status->getStatusId() == $slucaj->getStatusId()) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($status->getNaziv()); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="komentarSlucaja[]" value="<?php echo htmlspecialchars($slucaj->getKomentar()); ?>">
                            </td>
                            <td>
                                <button type="button" class="btn-obrisi-stavku" onclick="obrisiRed(this)">Obriši</button>
                            </td>
                        </tr>
                    <?php 
                    $rb++;
                    endforeach; 
                    ?>
                </tbody>
            </table>

            <button type="button" class="btn-dodaj-stavku" id="dodajSlucaj">+ Dodaj slučaj</button>
        </div>

        <div class="dugmad-akcije">
            <button type="submit" class="btn-primary">Sačuvaj izmene</button>
            <a href="<?php echo BASE_URL; ?>sesije" class="btn-otkazi">Otkaži</a>
        </div>
    </form>
</div>

<?php 
$potrebneSkripte = ['slucajAjax.js', 'validacija.js']; 
?>