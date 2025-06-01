<?php
require_once __DIR__ . '/../classes/Book.php';
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Walidacja ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die('ID książki nie zostało przesłane lub jest nieprawidłowe.');
}
$bookIdToDelete = (int)$_POST['id'];

// Pobierz książki z sesji
$books = isset($_SESSION['books']) ? $_SESSION['books'] : [];

// Przefiltruj książki
$books = array_filter($books, function($book) use ($bookIdToDelete) {
    return (int)$book->id !== $bookIdToDelete;
});
$books = array_values($books); // Przepisz indeksy

// Zapisz do sesji
$_SESSION['books'] = $books;

// Nadpisz CSV
$csvPath = __DIR__ . '/../books.csv';
$fp = fopen($csvPath, 'w');
if (!$fp) {
    die("Nie można zapisać pliku CSV.");
}

// Zapisz nagłówki
fputcsv($fp, ['ID', 'Tytuł', 'Autor', 'Wydawnictwo', 'Rok', 'Stron'], ',', '"', '\\');

// Zapisz książki
foreach ($books as $book) {
    fputcsv($fp, [
        $book->id,
        $book->title,
        $book->author,
        $book->publisher,
        $book->year,
        $book->pageCount
    ], ',', '"', '\\');
}
fclose($fp);

// Przekierowanie
if (!file_exists(__DIR__ . '/../pages/list_books.php')) {
    die("Plik '/pages/list_books.php' nie istnieje!");
}
header('Location: ' . BASE_URL . '/pages/list_books.php');

exit;


?>
