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
          $GLOBALS['DB']->exec("INSERT INTO books (title) VALUES ('{$this->getTitle()}');");
          $this->id = $GLOBALS['DB']->lastInsertId();
      }

      function update($new_title)
      {
          $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->id};");
          $this->title = $new_title;
      }

      function addAuthor($author)
      {
          $GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$this->id}, {$author->getId()});");
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

      static function find($id)
      {
          $query = $GLOBALS['DB']->query("SELECT * FROM books WHERE id = {$id};");
          $returned_book = $query->fetch(PDO::FETCH_ASSOC);
          $title = $returned_book['title'];
          $id = $returned_book['id'];
          $book = new Book($title, $id);
          return $book;

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
