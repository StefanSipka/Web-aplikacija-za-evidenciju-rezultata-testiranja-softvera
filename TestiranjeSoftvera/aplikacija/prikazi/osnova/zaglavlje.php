<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testiranje softvera - Evidentiranje rezultata</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>javno/css/stil.css">
    
</head>
<body>
    <header class="zaglavlje">
        <div class="kontejner">
            <div class="logo">
                <h1> Testiranje softvera</h1>
            </div>
            <nav class="navigacija">
                <?php if(isset($_SESSION['korisnikId'])): ?>
                    <span class="korisnik-info">
                         <?php echo htmlspecialchars($_SESSION['ime'] ?? ''); ?> 
                        <?php echo htmlspecialchars($_SESSION['prezime'] ?? ''); ?>
                    </span>
                    <a href="<?php echo BASE_URL; ?>sesije" class="nav-link">Sesije</a>
                    <a href="<?php echo BASE_URL; ?>odjava" class="nav-link">Odjava</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>prijava" class="nav-link">Prijava</a>
                    <a href="<?php echo BASE_URL; ?>registracija" class="nav-link">Registracija</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="kontejner">
        <?php if(isset($_SESSION['poruka'])): ?>
            <div class="poruka-uspeh">
                <?php 
                    echo htmlspecialchars($_SESSION['poruka']); 
                    unset($_SESSION['poruka']);
                ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['greska'])): ?>
            <div class="poruka-greska">
                <?php 
                    echo htmlspecialchars($_SESSION['greska']); 
                    unset($_SESSION['greska']);
                ?>
            </div>
        <?php endif; ?>