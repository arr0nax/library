<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "src/Patron.php";
require_once "src/Book.php";
require_once "src/Author.php";
require_once "src/Copy.php";
require_once "src/Checkout.php";

$server = 'mysql:host=localhost:8889;dbname=library_test';
$username = 'root';
$password = 'root';
$DB = new PDO($server, $username, $password);

class CopyTest extends PHPUnit_Framework_TestCase{

    protected function teardown()
    {
        Patron::deleteAll();
        Author::deleteAll();
        Book::deleteAll();
        Copy::deleteAll();
        Checkout::deleteAll();
    }
    function test_getBook_id()
    {
        $book_id = 3;
        $test_copy = new Copy($book_id);

        $result = $test_copy->getBook_id();

        $this->assertEquals($book_id, $result);
    }
    function test_save()
    {
        $book_id = 3;
        $test_copy = new Copy($book_id);
        $test_copy->save();

        $result = Copy::getAll();

        $this->assertEquals([$test_copy], $result);
    }

    function test_getAll()
    {
        $book_id = 3;
        $test_copy = new Copy($book_id);
        $test_copy->save();

        $book_id2 = 4;
        $test_copy2 = new Copy($book_id2);
        $test_copy2->save();

        $result = Copy::getAll();

        $this->assertEquals([$test_copy, $test_copy2], $result);

    }
    function test_deleteAll()
    {
        $book_id = 3;
        $test_copy = new Copy($book_id);
        $test_copy->save();

        $book_id2 = 4;
        $test_copy2 = new Copy($book_id2);
        $test_copy2->save();

        Copy::deleteAll();
        $result = Copy::getAll();

        $this->assertEquals([], $result);

    }

    function test_find()
    {

        $book_id = 3;
        $test_copy = new Copy($book_id);
        $test_copy->save();

        $book_id2 = 4;
        $test_copy2 = new Copy($book_id2);
        $test_copy2->save();

        $result = Copy::find($test_copy2->getId());

        $this->assertEquals($test_copy2, $result);
    }
}
