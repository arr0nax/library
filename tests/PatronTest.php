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

class PatronTest extends PHPUnit_Framework_TestCase{

    protected function teardown()
    {
        Patron::deleteAll();
        Author::deleteAll();
        Book::deleteAll();
        Copy::deleteAll();
        Checkout::deleteAll();
    }
    function test_getName()
    {
        $name = 'John';
        $password = 'hello';
        $email = 'john@gmail.com';
        $id = 3;
        $test_patron = new Patron($name, $password, $email, $id);

        $result = $test_patron->getName();

        $this->assertEquals($name, $result);
    }
    function test_getPassword()
    {
        $name = 'John';
        $password = 'hello';
        $email = 'john@gmail.com';
        $id = 3;
        $test_patron = new Patron($name, $password, $email, $id);

        $result = $test_patron->getPassword();

        $this->assertEquals($password, $result);
    }
    function test_getEmail()
    {
        $name = 'John';
        $password = 'hello';
        $email = 'john@gmail.com';
        $id = 3;
        $test_patron = new Patron($name, $password, $email, $id);

        $result = $test_patron->getEmail();

        $this->assertEquals($email, $result);
    }
    function test_getId()
    {
        $name = 'John';
        $password = 'hello';
        $email = 'john@gmail.com';
        $id = 3;
        $test_patron = new Patron($name, $password, $email, $id);

        $result = $test_patron->getId();

        $this->assertEquals($id, $result);
    }
    function test_save()
    {
        $name = 'John';
        $password = 'hello';
        $email = 'john@gmail.com';
        $id = 3;
        $test_patron = new Patron($name, $password, $email, $id);
        $test_patron->save();

        $result = Patron::getAll();

        $this->assertEquals([$test_patron], $result);
    }
    function test_getAll()
    {
        $name = 'John';
        $password = 'hello';
        $email = 'john@gmail.com';
        $id = 3;
        $test_patron = new Patron($name, $password, $email, $id);
        $test_patron->save();

        $name2 = 'Mark';
        $password2 = 'goodbye';
        $email2 = 'mark@gmail.com';
        $id2 = 2;
        $test_patron2 = new Patron($name2, $password2, $email2, $id2);
        $test_patron2->save();

        $result = Patron::getAll();

        $this->assertEquals([$test_patron, $test_patron2], $result);
    }
    function test_deleteAll()
    {
        $name = 'John';
        $password = 'hello';
        $email = 'john@gmail.com';
        $id = 3;
        $test_patron = new Patron($name, $password, $email, $id);
        $test_patron->save();

        $name2 = 'Mark';
        $password2 = 'goodbye';
        $email2 = 'mark@gmail.com';
        $id2 = 2;
        $test_patron2 = new Patron($name2, $password2, $email2, $id2);
        $test_patron2->save();

        Patron::deleteAll();
        $result = Patron::getAll();

        $this->assertEquals([], $result);

    }

    function test_checkout()
    {
        $name = 'John';
        $password = 'hello';
        $email = 'john@gmail.com';
        $test_patron = new Patron($name, $password, $email);
        $test_patron->save();

        $title = 'harry potter';
        $test_book = new Book($title);
        $test_book->save();

        $book_id = $test_book->getId();
        $test_copy = new Copy($book_id);
        $test_copy->save();

        $patron_id = $test_patron->getId();
        $copy_id = $test_copy->getId();
        $test_checkout = new Checkout($patron_id, $copy_id);
        $test_checkout->save();
        // var_dump($test_checkout);


        $result = Checkout::getAll();
        // var_dump($result);


        $this->assertEquals([$test_checkout], $result);
    }

    function test_find()
    {
        $name = 'John';
        $password = 'hello';
        $email = 'john@gmail.com';
        $test_patron = new Patron($name, $password, $email);
        $test_patron->save();

        $name2 = 'Mike';
        $password2 = 'goodbye';
        $email2 = 'mike@gmail.com';
        $test_patron2 = new Patron($name2, $password2, $email2);
        $test_patron2->save();


        $result = Patron::find($test_patron2->getId());

        $this->assertEquals($test_patron2, $result);

    }
}
