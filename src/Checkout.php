<?php
    class Checkout
    {
        private $patron_id;
        private $copy_id;
        private $due_date;
        private $checkout_date;
        private $returned;
        private $id;

    function __construct($patron_id, $copy_id, $due_date = null, $checkout_date = null,  $returned = 0, $id = null)
    {
        $this->patron_id = $patron_id;
        $this->copy_id = $copy_id;

        $this->due_date = is_null($due_date) ? date('Y-m-d',(mktime(0, 0, 0, date("m"), date("d")+14, date("Y")))) : $due_date;

        $this->checkout_date = is_null($checkout_date) ? date("Y-m-d"): $checkout_date;

        $this->returned = $returned;
        $this->id = $id;
    }

    function getPatron_id()
    {
        return $this->patron_id;
    }
    function setPatron_id($patron_id)
    {
        $this->patron_id = $patron_id;
    }

    function getCopy_id()
    {
        return $this->copy_id;
    }
    function setCopy_id($copy_id)
    {
        $this->copy_id = $copy_id;
    }


    function getDueDate()
    {
        return $this->due_date;
    }
    function setDueDate($due_date)
    {
        $this->due_date = $due_date;
    }

    function getCheckoutDate()
    {
        return $this->checkout_date;
    }
    function setCheckoutDate($checkout_date)
    {
        $this->checkout_date = $checkout_date;
    }


    function getReturned()
    {
        return $this->returned;
    }
    function setReturned($returned)
    {
        $this->returned = $returned;
        $GLOBALS['DB']->exec("UPDATE checkout SET returned = {$returned} WHERE id = {$this->getId()};");
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO checkout (patron_id, copy_id, due_date, checkout_date, returned) VALUES ({$this->patron_id}, {$this->copy_id}, '{$this->due_date}', '{$this->checkout_date}', {$this->returned});");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function getBook()
    {
        $query = $GLOBALS['DB']->query("SELECT books.* FROM checkout JOIN copies ON (checkout.copy_id = copies.id) JOIN books ON (copies.book_id = books.id) WHERE checkout.id = {$this->getId()};");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $title = $result['title'];
        $id = $result['id'];
        $new_book = new Book($title, $id);
        return $new_book;

    }

    static function find($search_id)
    {
        $query = $GLOBALS['DB']->query("SELECT * FROM checkout WHERE id = {$search_id};");

        $result = $query->fetch(PDO::FETCH_ASSOC);

        $patron_id = $result['patron_id'];
        $copy_id = $result['copy_id'];
        $due_date = $result['due_date'];
        $checkout_date = $result['checkout_date'];
        $returned = $result['returned'];
        $id = $result['id'];
        $new_result = new Checkout($patron_id, $copy_id, $due_date, $checkout_date, $returned, $id);
        return $new_result;
    }

    static function getByPatron($patron_id)
    {
        $returned_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkout WHERE patron_id = {$patron_id};");
        $checkouts =[];
        foreach($returned_checkouts as $checkout) {
            $patron_id = $checkout['patron_id'];
            $copy_id = $checkout['copy_id'];
            $due_date = $checkout['due_date'];
            $checkout_date = $checkout['checkout_date'];
            $returned = $checkout['returned'];
            $id = $checkout['id'];
            $new_checkout = new Checkout($patron_id, $copy_id, $due_date, $checkout_date, $returned, $id);
            array_push($checkouts, $new_checkout);

        }
        return $checkouts;
    }

    static function getAll()
    {
        $returned_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkout;");
        $checkouts =[];
        foreach($returned_checkouts as $checkout) {
            $patron_id = $checkout['patron_id'];
            $copy_id = $checkout['copy_id'];
            $due_date = $checkout['due_date'];
            $checkout_date = $checkout['checkout_date'];
            $returned = $checkout['returned'];
            $id = $checkout['id'];
            $new_checkout = new Checkout($patron_id, $copy_id, $due_date, $checkout_date, $returned, $id);
            array_push($checkouts, $new_checkout);

        }
        return $checkouts;

    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM checkout;");
    }

}

 ?>
