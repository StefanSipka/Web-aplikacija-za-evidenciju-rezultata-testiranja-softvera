<?php
return [
    // Web
    ['GET', '/', 'SesijaKontroler@spisak'],
    ['GET', '/prijava', 'AuthKontroler@prijava'],
    ['POST', '/prijava', 'AuthKontroler@loguj'],
    ['GET', '/registracija', 'AuthKontroler@registracija'],
    ['POST', '/registracija', 'AuthKontroler@registruj'],
    ['GET', '/odjava', 'AuthKontroler@odjava'],

    ['GET', '/sesije', 'SesijaKontroler@spisak'],
    ['GET', '/sesije/kreiraj', 'SesijaKontroler@kreiraj'],
    ['POST', '/sesije', 'SesijaKontroler@snimi'],
    ['GET', '/sesije/{id}', 'SesijaKontroler@pregled'],
    ['GET', '/sesije/{id}/izmeni', 'SesijaKontroler@izmeni'],
    ['POST', '/sesije/{id}', 'SesijaKontroler@azuriraj'],
    ['DELETE', '/sesije/{id}', 'SesijaKontroler@obrisi'],
    ['GET', '/sesije/{id}/stampaj', 'SesijaKontroler@stampaj'],

    // REST API
    ['GET', '/api/sesije', 'ApiKontroler@sve'],
    ['GET', '/api/sesije/{id}', 'ApiKontroler@jedna'],
    ['POST', '/api/sesije', 'ApiKontroler@dodaj'],
    ['PUT', '/api/sesije/{id}', 'ApiKontroler@izmeni'],
    ['DELETE', '/api/sesije/{id}', 'ApiKontroler@ukloni'],
    ['GET', '/api/sesije/{id}/slucajevi', 'ApiKontroler@slucajevi'],
];