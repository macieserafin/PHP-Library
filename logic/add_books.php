<?php
require_once __DIR__ . '/../classes/Book.php';
require_once __DIR__ . '/../config.php';


// Uruchom sesję, jeśli nie jest jeszcze aktywna
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
normalizeBookIDs($books);

// Inicjalizacja tablicy błędów
$errors = [];

// Walidacja pól formularza
if (isset($_POST['title'])) {
    $title = trim($_POST['title']);
} else {
    $title = '';
}

if (isset($_POST['author'])) {
    $author = trim($_POST['author']);
} else {
    $author = '';
}

if (isset($_POST['publisher'])) {
    $publisher = trim($_POST['publisher']);
} else {
    $publisher = '';
}

if (isset($_POST['year'])) {
    $year = (int) $_POST['year'];
} else {
    $year = 0;
}

if (isset($_POST['pageCount'])) {
    $pageCount = (int) $_POST['pageCount'];
} else {
    $pageCount = 0;
}

if ($title === '') $errors[] = 'Tytuł jest wymagany.';
if ($author === '') $errors[] = 'Autor jest wymagany.';
if ($publisher === '') $errors[] = 'Wydawnictwo jest wymagane.';
if ($year <= 0) $errors[] = 'Rok wydania musi być liczbą większą od zera.';
if ($pageCount <= 0) $errors[] = 'Liczba stron musi być większa od zera.';

// Jeśli są błędy, zapisz je w sesji i wróć
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    header('Location: /pages/add_book.php');
    exit;
}

// Wczytaj aktualną listę książek z sesji
$books = isset($_SESSION['books']) ? $_SESSION['books'] : [];

normalizeBookIDs($books);

// Zapisz znormalizowaną listę do sesji, żeby mieć pewność
$_SESSION['books'] = $books;

// Wygeneruj nowe ID
$maxId = 0;
foreach ($books as $book) {
    $maxId = max($maxId, (int)$book->id);
}
$newId = $maxId + 1;

try {
    $newBook = new Book($newId, $title, $author, $publisher, $year, $pageCount);
    $books[] = $newBook;
    $_SESSION['books'] = $books;
} catch (Exception $e) {
    $_SESSION['form_errors'] = [$e->getMessage()];
    header('Location: /pages/add_book.php');
    exit;
}

// Zapisz do CSV
$csvPath = __DIR__ . '/../books.csv';
$fp = fopen($csvPath, 'w');

// Zapisz nagłówki
fputcsv($fp, ['ID', 'Tytuł', 'Autor', 'Wydawnictwo', 'Rok', 'Stron'], ',', '"', '\\');


// Zapisz wszystkie książki
foreach ($books as $book) {
    fputcsv($fp, [
        $book->id,
        $book->title,
        $book->author,
        $book->publisher,
        $book->year,
        $book->pageCount
    ], ",", '"', "\\");

}

fclose($fp);



// Przekieruj z powrotem do listy
header('Location: ' . BASE_URL . '/pages/list_books.php');
exit;
?>
