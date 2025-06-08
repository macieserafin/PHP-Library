<?php
class Book{
    public $id;
    public $title;
    public $author;
    public $publisher;
    public $year;
    public $pageCount;

    public function __construct($id, $title, $author, $publisher, $year, $pageCount)
    {
        if( !is_numeric($id) || $id < 0){
            throw new Exception("Niepoprawne ID książki. ");
        }
        if(!is_string($title) || trim($title) === ''){
            throw new Exception("Tytuł książki jest wymagany. ");
        }

        if (!is_string($author) || trim($author) === '') {
            throw new Exception("Autor książki jest wymagany.");
        }

        if (!is_string($publisher) || trim($publisher) === '') {
            throw new Exception("Wydawnictwo jest wymagane.");
        }

        if (!is_numeric($year) || $year <= 0 || $year > (int)date("Y") + 1) {
            throw new Exception("Nieprawidłowy rok wydania.");
        }

        if (!is_numeric($pageCount) || $pageCount <= 0) {
            throw new Exception("Liczba stron musi być większa od zera.");
        }
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->publisher = $publisher;
        $this->year = $year;
        $this->pageCount = $pageCount;
    }

}