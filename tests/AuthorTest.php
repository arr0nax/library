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

class AuthorTest extends PHPUnit_Framework_TestCase{

    protected function teardown()
    {
        Author::deleteAll();
        Book::deleteAll();
    }
    function test_getName()
    {
        $name = 'John';
        $id = 3;
        $test_author = new Author($name, $id);

        $result = $test_author->getName();

        $this->assertEquals($name, $result);
    }

    function test_getId()
    {
        $name = 'John';
        $id = 3;
        $test_author = new Author($name, $id);

        $result = $test_author->getId();

        $this->assertEquals($id, $result);
    }

    function test_save()
    {
        $name = 'John';
        $test_author = new Author($name);
        $test_author->save();

        $result = Author::getAll();

        $this->assertEquals($test_author, $result[0]);
    }

    function test_getAll()
    {
        $name = 'John';
        $test_author = new Author($name);
        $test_author->save();
        $name2 = 'Mark';
        $test_author2 = new Author($name2);
        $test_author2->save();

        $result = Author::getAll();

        $this->assertEquals([$test_author, $test_author2], $result);
    }

    function test_deleteAll()
    {
        $name = 'John';
        $test_author = new Author($name);
        $test_author->save();
        $name2 = 'Mark';
        $test_author2 = new Author($name2);
        $test_author2->save();

        Author::deleteAll();
        $result = Author::getAll();

        $this->assertEquals([], $result);
    }

    function test_delete()
    {
        $name = 'John';
        $test_author = new Author($name);
        $test_author->save();
        $name2 = 'Mark';
        $test_author2 = new Author($name2);
        $test_author2->save();
        $test_author->delete();

        $result = Author::getAll();

        $this->assertEquals([$test_author2], $result);
    }

    function test_update()
    {
        $name = 'John';
        $test_author = new Author($name);
        $test_author->save();
        $name2 = 'Mark';

        $test_author->update($name2);
        $result = Author::getAll();

        $this->assertEquals($name2, $result[0]->getName());
    }

    function test_find()
    {
        $name = 'John';
        $test_author = new Author($name);
        $test_author->save();
        $name2 = 'Mark';
        $test_author2 = new Author($name2);
        $test_author2->save();

        $result = Author::find($test_author2->getId());

        $this->assertEquals($test_author2, $result);
    }

    function test_addBook()
    {
        $title = 'See Spot Run';
        $test_book = new Book($title);
        $test_book->save();
        $name = 'John';
        $test_author = new Author($name);
        $test_author->save();

        $test_author->addBook($test_book);
        $result = $test_author->getBooks();

        $this->assertEquals([$test_book], $result);
    }

    function test_addBook_duplicate()
    {
        $title = 'See Spot Run';
        $test_book = new Book($title);
        $test_book->save();

        $name = 'John';
        $test_author = new Author($name);
        $test_author->save();

        $test_author->addBook($test_book);
        $test_author->addBook($test_book);

        $result = $test_author->getBooks();

        $this->assertEquals([$test_book], $result);
    }

    function test_getBooks()
    {
        $title = 'See Spot Run';
        $test_book = new Book($title);
        $test_book->save();
        $title2 = 'Harry Potter';
        $test_book2 = new Book($title2);
        $test_book2->save();
        $name = 'Mark';
        $test_author = new Author($name);
        $test_author->save();

        $test_author->addBook($test_book);
        $test_author->addBook($test_book2);
        $result = $test_author->getBooks();

        $this->assertEquals([$test_book, $test_book2], $result);
    }






}
