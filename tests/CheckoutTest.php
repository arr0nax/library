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

class CheckoutTest extends PHPUnit_Framework_TestCase{

    protected function teardown()
    {
        Patron::deleteAll();
        Author::deleteAll();
        Book::deleteAll();
        Copy::deleteAll();
        Checkout::deleteAll();
    }
    function test_getPatron_id()
    {
        $patron_id = 4;
        $copy_id = 3;
        $test_checkout = new Checkout($patron_id, $copy_id);

        $result = $test_checkout->getPatron_id();

        $this->assertEquals($patron_id, $result);
    }
    function test_getCopy_id()
    {
        $patron_id = 4;
        $copy_id = 3;
        $test_checkout = new Checkout($patron_id, $copy_id);

        $result = $test_checkout->getCopy_id();

        $this->assertEquals($copy_id, $result);
    }
    function test_save()
    {
        $patron_id = 4;
        $copy_id = 3;
        $test_checkout = new Checkout($patron_id, $copy_id);
        $test_checkout->save();

        $result = Checkout::getAll();

        $this->assertEquals([$test_checkout], $result);
    }

    function test_getAll()
    {
        $patron_id = 4;
        $copy_id = 3;
        $test_checkout = new Checkout($patron_id, $copy_id);
        $test_checkout->save();

        $patron_id2 = 5;
        $copy_id2 = 6;
        $test_checkout2 = new Checkout($patron_id2, $copy_id2);
        $test_checkout2->save();

        $result = Checkout::getAll();

        $this->assertEquals([$test_checkout, $test_checkout2], $result);

    }
    function test_deleteAll()
    {
        $patron_id = 4;
        $copy_id = 3;
        $test_checkout = new Checkout($patron_id, $copy_id);
        $test_checkout->save();

        $patron_id2 = 5;
        $copy_id2 = 6;
        $test_checkout2 = new Checkout($patron_id2, $copy_id2);
        $test_checkout2->save();


        Checkout::deleteAll();
        $result = Checkout::getAll();

        $this->assertEquals([], $result);

    }
}
