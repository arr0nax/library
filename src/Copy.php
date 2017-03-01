<?php
    class Copy
    {
        private $book_id;
        private $id;


    function __construct($book_id, $id = null)
    {
        $this->book_id = $book_id;
        $this->id = $id;
    }

    function getBook_id()
    {
        return $this->book_id;
    }

    function setBook_id($book_id)
    {
        $this->book_id = $book_id;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO copies (book_id) VALUES ({$this->getBook_id()});");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function find($search_id)
    {
        $query = $GLOBALS['DB']->query("SELECT * FROM copies WHERE id = {$search_id};");

        $result = $query->fetch(PDO::FETCH_ASSOC);

        $book_id = $result['book_id'];
        $id = $result['id'];
        $new_result = new Copy($book_id, $id);
        return $new_result;
    }

    static function getAll()
    {
        $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
        $copies =[];
        foreach($returned_copies as $copy) {
            $book_id = $copy['book_id'];
            $id = $copy['id'];
            $new_copy = new Copy($book_id, $id);
            array_push($copies, $new_copy);
        }
        return $copies;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM copies;");
    }
}

 ?>
