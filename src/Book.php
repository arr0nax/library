<?php
  class Book
  {
      private $title;
      private $id;

      function __construct ($title, $id = null)
      {
            $this->title = $title;
            $this->id = $id;
      }

      function getTitle()
      {
          return $this->title;
      }

      function setTitle($new_title)
      {
          $this->title = $new_title;
      }

      function getId()
      {
          return $this->id;
      }

      function save()
      {

          $query = $GLOBALS['DB']->query("SELECT * FROM books WHERE title = '{$this->getTitle()}';");
          $result = $query->fetch(PDO::FETCH_ASSOC);
          if(empty($result)){
              //Books Table
              $GLOBALS['DB']->exec("INSERT INTO books (title) VALUES ('{$this->getTitle()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
          }
          else {
              $this->id = $result['id'];

          }


      }

      function update($new_title)
      {
          $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->id};");
          $this->title = $new_title;
      }

      function addAuthor($author)
      {

          $query = $GLOBALS['DB']->query("SELECT * FROM books_authors WHERE book_id = {$this->getId()} AND author_id = {$author->getId()};");

          $retrieved = $query->fetchAll(PDO::FETCH_ASSOC);

          if(! $retrieved){
              $GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$this->id}, {$author->getId()});");
          }
      }

      function getAuthors()
      {
          $returned_authors = $GLOBALS['DB']->query("SELECT author.* FROM books JOIN books_authors ON (books.id = books_authors.book_id) JOIN author ON (books_authors.author_id = author.id) WHERE books.id = {$this->getId()};");

          $authors = [];
          foreach($returned_authors as $author){
              $name = $author['name'];
              $id = $author['id'];
              $new_author = new Author($name, $id);
              array_push($authors, $new_author);
          }
          return $authors;
      }

      function getCopies()
      {
          $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies WHERE  book_id = {$this->getId()};");
          $copies = [];
          foreach($returned_copies as $copy){
              $book_id = $copy['book_id'];
              $available = $copy['available'];
              $id = $copy['id'];
              $new_copy = new Copy($book_id, $available, $id);
              array_push($copies, $new_copy);
          }
          return $copies;
      }

      static function find($id)
      {
          $query = $GLOBALS['DB']->query("SELECT * FROM books WHERE id = {$id};");
          $returned_book = $query->fetch(PDO::FETCH_ASSOC);
          $title = $returned_book['title'];
          $id = $returned_book['id'];
          $book = new Book($title, $id);
          return $book;

      }

      static function search($search_title)
      {
          $query = $GLOBALS['DB']->query("SELECT * FROM books WHERE title LIKE '%{$search_title}%';");
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
          $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
          $books =[];
          foreach($returned_books as $book) {
              $title = $book['title'];
              $id = $book['id'];
              $new_book = new Book($title, $id);
              array_push($books, $new_book);
          }
          return $books;
      }

      static function deleteAll()
      {
          $GLOBALS['DB']->exec("DELETE FROM books;");
          $GLOBALS['DB']->exec("DELETE FROM books_authors;");
      }

      function delete()
      {
          $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->id};");
          $GLOBALS['DB']->exec("DELETE FROM books_authors WHERE book_id = {$this->id};");
      }





  }
