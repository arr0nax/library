<?php
    class Copy
    {
        private $book_id;
        private $id;
        private $available;


    function __construct($book_id, $available = true, $id = null)
    {
        $this->book_id = $book_id;
        $this->id = $id;
        $this->available = $available;
    }

    function getBook_id()
    {
        return $this->book_id;
    }

    function setBook_id($book_id)
    {
        $this->book_id = $book_id;
    }

    function getAvailable()
    {
        return $this->available;
    }

    function setAvailable($available)
    {
        $this->available = $available;
        $GLOBALS['DB']->exec("UPDATE copies SET available = {$this->available} WHERE id = {$this->getId()};");
    }

    function getId()
    {
        return $this->id;
    }

    function getPatron()
    {
        $query = $GLOBALS['DB']->query("SELECT patrons.* FROM copies JOIN checkout ON (copies.id = checkout.copy_id) JOIN patrons ON (checkout.patron_id = patrons.id) WHERE copies.id = {$this->getId()};");
        $patron = $query->fetch(PDO::FETCH_ASSOC);
        $name = $patron['name'];
        $email = $patron['email'];
        $password = $patron['password'];
        $new_patron = new Patron($name, $password, $email);
        return $new_patron;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO copies (book_id, available) VALUES ({$this->getBook_id()}, {$this->getAvailable()});");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function find($search_id)
    {
        $query = $GLOBALS['DB']->query("SELECT * FROM copies WHERE id = {$search_id};");

        $result = $query->fetch(PDO::FETCH_ASSOC);

        $book_id = $result['book_id'];
        $available = $result['available'];
        $id = $result['id'];
        $new_result = new Copy($book_id, $available, $id);
        return $new_result;
    }

    static function getAll()
    {
        $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
        $copies =[];
        foreach($returned_copies as $copy) {
            $book_id = $copy['book_id'];
            $available = $result['available'];
            $id = $copy['id'];
            $new_copy = new Copy($book_id, $available, $id);
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
