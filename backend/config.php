<?php
// Konfiguracija za bazu podataka
define('DB_HOST', '127.0.0.1');   // Host baze podataka
define('DB_NAME', 'sandwich_shop'); // Ime baze podataka
define('DB_PORT', '3306');         // Port baze podataka
define('DB_USER', 'root');         // Korisničko ime za bazu podataka
define('DB_PASS', '');             // Lozinka za bazu podataka (ako nema lozinke, ostavi prazno)

// Pokušaj konekciju sa bazom
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    Flight::set('db', $db);  // Postavi bazu podataka u Flight
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());  // Ako konekcija ne uspe, ispiši grešku
}

// JWT Secret Key (koristi jak, jedinstven ključ)
define('JWT_SECRET', 'your_very_secret_key');  // Koristi jak, jedinstven ključ za JWT token

// Klasu za pristupanje JWT ključu
class Config {
    public static function JWT_SECRET() {
        return JWT_SECRET;  // Vraća definisani tajni ključ za JWT
    }
}

?>
