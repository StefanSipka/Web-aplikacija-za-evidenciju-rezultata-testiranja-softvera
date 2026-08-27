<div class="forma-sekcija">
    <h2>Prijava korisnika</h2>
    <form action="<?php echo BASE_URL; ?>prijava" method="POST" class="forma">
        <div class="polje">
            <label for="email">Email adresa:</label>
            <input type="email" id="email" name="email" required placeholder="unestite@email.com">
        </div>

        <div class="polje">
            <label for="lozinka">Lozinka:</label>
            <input type="password" id="lozinka" name="lozinka" required minlength="6">
        </div>

        <button type="submit" class="btn-primary">Prijavi se</button>
        
        <div class="registracija-link">
            Nemate nalog? <a href="<?php echo BASE_URL; ?>registracija">Registrujte se</a>
        </div>
    </form>
</div>

<?php 
// Dodajemo skriptu za validaciju na ovoj stranici
$potrebneSkripte = ['validacija.js']; 
?>