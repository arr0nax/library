<?php
    class Author
    {
        private $name;
        private $id;

        function __construct ($name, $id = null)
        {
              $this->name = $name;
              $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $query = $GLOBALS['DB']->query("SELECT * FROM author WHERE name = '{$this->getName()}';");
            $result = $query->fetch(PDO::FETCH_ASSOC);
            if(empty($result)){
                $GLOBALS['DB']->exec("INSERT INTO author (name) VALUES ('{$this->getName()}');");
                $this->id = $GLOBALS['DB']->lastInsertId();
            } else {
                $this->id = $result['id'];
            }
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE author SET name = '{$new_name}' WHERE id = {$this->id};");
            $this->name = $new_name;
        }

        function addBook($book)
        {
            $query = $GLOBALS['DB']->query("SELECT * FROM books_authors WHERE book_id = {$book->getId()} AND author_id = {$this->getId()};");

            $retrieved = $query->fetchAll(PDO::FETCH_ASSOC);

            if(!$retrieved){
                $GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$book->getId()}, {$this->id});");
            }
        }

        function getBooks()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT books.* FROM author JOIN books_authors ON (author.id = books_authors.author_id) JOIN books ON (books_authors.book_id = books.id) WHERE author.id = {$this->getId()};");

            $books = [];
            foreach($returned_books as $book){
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function find($id)
        {
            $query = $GLOBALS['DB']->query("SELECT * FROM author WHERE id = {$id};");
            $returned_author = $query->fetch(PDO::FETCH_ASSOC);
            $name = $returned_author['name'];
            $id = $returned_author['id'];
            $author = new Author($name, $id);
            return $author;

        }

        static function search($search_author)
        {
            $query = $GLOBALS['DB']->query("SELECT books.* FROM author JOIN books_authors ON (author.id = books_authors.author_id) JOIN books ON (books_authors.book_id = books.id) WHERE name LIKE '%{$search_author}%';");
            $returned_books = $query->fetchAll(PDO::FETCH_ASSOC);
            $books =[];
            foreach($returned_books as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM author;");
            $authors =[];
            foreach($returned_authors as $author) {
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM author;");
            $GLOBALS['DB']->exec("DELETE FROM books_authors;");
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM author WHERE id = {$this->id};");
            $GLOBALS['DB']->exec("DELETE FROM books_authors WHERE author_id = {$this->id};");
        }

    }
