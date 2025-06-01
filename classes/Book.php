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
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->publisher = $publisher;
        $this->year = $year;
        $this->pageCount = $pageCount;
    }

}