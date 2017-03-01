<?php
    class Patron
    {
        private $name;
        private $password;
        private $email;
        private $id;

        function __construct($name, $password, $email, $id = null)
        {
            $this->name = $name;
            $this->password = $password;
            $this->email = $email;
            $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($name)
        {
            $this->name = $name;
        }

        function getPassword()
        {
            return $this->password;
        }

        function setPassword($password)
        {
            $this->password = $password;
        }

        function getEmail()
        {
            return $this->email;
        }

        function setEmail($email)
        {
            $this->email = $email;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO patrons (name, password, email) VALUES ('{$this->getName()}', '{$this->getPassword()}', '{$this->getEmail()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function checkout($book)
        {
            
        }

        static function getAll()
        {
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
            $patrons =[];
            foreach($returned_patrons as $patron) {
                $name = $patron['name'];
                $password = $patron['password'];
                $email = $patron['email'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $password, $email, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons;");
        }
    }
