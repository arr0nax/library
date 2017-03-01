<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "src/Author.php";
require_once "src/Book.php";

$server = 'mysql:host=localhost:8889;dbname=library_test';
$username = 'root';
$password = 'root';
$DB = new PDO($server, $username, $password);

class BookTest extends PHPUnit_Framework_TestCase{

    protected function teardown()
    {
        Author::deleteAll();
        Book::deleteAll();
    }
    function test_getTitle()
    {
        $title = 'John';
        $id = 3;
        $test_book = new Book($title, $id);

        $result = $test_book->getTitle();

        $this->assertEquals($title, $result);
    }

    function test_getId()
    {
        $title = 'John';
        $id = 3;
        $test_book = new Book($title, $id);

        $result = $test_book->getId();

        $this->assertEquals($id, $result);
    }

    function test_save()
    {
        $title = 'John';
        $test_book = new Book($title);
        $test_book->save();

        $result = Book::getAll();

        $this->assertEquals($test_book, $result[0]);
    }

    function test_getAll()
    {
        $title = 'John';
        $test_book = new Book($title);
        $test_book->save();
        $title2 = 'Mark';
        $test_book2 = new Book($title2);
        $test_book2->save();

        $result = Book::getAll();

        $this->assertEquals([$test_book, $test_book2], $result);
    }

    function test_deleteAll()
    {
        $title = 'John';
        $test_book = new Book($title);
        $test_book->save();
        $title2 = 'Mark';
        $test_book2 = new Book($title2);
        $test_book2->save();

        Book::deleteAll();
        $result = Book::getAll();

        $this->assertEquals([], $result);
    }

    function test_delete()
    {
        $title = 'John';
        $test_book = new Book($title);
        $test_book->save();
        $title2 = 'Mark';
        $test_book2 = new Book($title2);
        $test_book2->save();
        $test_book->delete();

        $result = Book::getAll();

        $this->assertEquals([$test_book2], $result);
    }

    function test_update()
    {
        $title = 'John';
        $test_book = new Book($title);
        $test_book->save();
        $title2 = 'Mark';

        $test_book->update($title2);
        $result = Book::getAll();

        $this->assertEquals($title2, $result[0]->getTitle());
    }

    function test_find()
    {
        $title = 'John';
        $test_book = new Book($title);
        $test_book->save();
        $title2 = 'Mark';
        $test_book2 = new Book($title2);
        $test_book2->save();

        $result = Book::find($test_book2->getId());

        $this->assertEquals($test_book2, $result);
    }

    function test_addAuthor()
    {
        $title = 'See Spot Run';
        $test_book = new Book($title);
        $test_book->save();
        $name = 'John';
        $test_author = new Author($name);
        $test_author->save();

        $test_book->addAuthor($test_author);
        $result = $test_book->getAuthors();

        $this->assertEquals([$test_author], $result);
    }

    function test_addAuthor_duplicate()
    {
        $title = 'See Spot Run';
        $test_book = new Book($title);
        $test_book->save();

        $name = 'John';
        $test_author = new Author($name);
        $test_author->save();

        $test_book->addAuthor($test_author);
        $test_book->addAuthor($test_author);

        $result = $test_book->getAuthors();

        $this->assertEquals([$test_author], $result);
    }


    function test_getAuthors()
    {
        $title = 'See Spot Run';
        $test_book = new Book($title);
        $test_book->save();
        $name = 'John';
        $test_author = new Author($name);
        $test_author->save();
        $name2 = 'Mark';
        $test_author2 = new Author($name2);
        $test_author2->save();

        $test_book->addAuthor($test_author);
        $test_book->addAuthor($test_author2);
        $result = $test_book->getAuthors();

        $this->assertEquals([$test_author, $test_author2], $result);
    }


}
