<?php

require_once __DIR__ . '/../config.php';

function compareByTitle($a, $b) {
    return strcmp($a->title, $b->title);
}

function normalizeBookIDs(&$books) {

    if (!is_array($books)) {
        $books = [];
        return;
    }

    // Posortuj książki według tytułu
    usort($books, 'compareByTitle');

    // Przypisz nowe ID w kolejności po sortowaniu
    $newId = 1;
    foreach ($books as $book) {
        $book->id = $newId++;
    }
}

?>
