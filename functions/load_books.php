<?php

require_once __DIR__ . '/../config.php';


function loadBooksFromCSV($filePath) {
    $books = [];

    // Sprawdź, czy plik istnieje
    if (!file_exists($filePath)) {
        throw new Exception("Plik CSV nie istnieje: $filePath");
    }

    // Otwórz plik
    if (($handle = fopen($filePath, "r")) === false) {
        throw new Exception("Nie udało się otworzyć pliku: $filePath");
    }

    // Pomiń nagłówki
    fgetcsv($handle, 1000, ",", '"', '\\');

    // Wczytuj wiersze
    while (($data = fgetcsv($handle, 1000, ",", '"', '\\')) !== false)
    {
        // Sprawdź, czy jest co najmniej 6 kolumn
        if (count($data) < 6) {
            continue; // pomiń błędny wiersz
        }

        // Sprawdź, czy żadne pole nie jest puste
        if (in_array('', $data)) {
            continue; // pomiń wiersz z pustym polem
        }

        // Utwórz obiekt i dodaj do tablicy
        try {
            $books[] = new Book(
                trim($data[0]),
                trim($data[1]),
                trim($data[2]),
                trim($data[3]),
                trim($data[4]),
                trim($data[5])
            );
        } catch (Exception $e) {
            //$e przechowuje Exception ktore ustawilem w kasie
            continue;
        }
    }

    fclose($handle);

    // Zapisz do sesji
    $_SESSION['books'] = $books;

    return $books;
}