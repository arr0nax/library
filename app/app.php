<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Patron.php";
    require_once __DIR__."/../src/Checkout.php";

    session_start();

    $app = new Silex\Application();
    $app['debug']=true;


    $server = 'mysql:host=localhost:8889;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });


    //Login
    $app->post("/sign_in", function() use($app) {
    $patron = Patron::login($_POST['email'], $_POST['password']);
    $admin = Patron::find(4);
        if($patron) {
            if($patron->getEmail() == $admin->getEmail())
            {
                $_SESSION['patron'] = $patron;
                return $app->redirect('/admin');
            }else{
                $_SESSION['patron'] = $patron;
                return $app->redirect('/patron/'.$patron->getId());
            }
        }
        else {
            return $app->redirect('/');
        }
    });

    $app->get('/admin', function() use($app) {
        return $app['twig']->render('admin.html.twig', array("books"=>Book::getAll()));
    });


    $app->get('/patron/{id}', function($id) use($app) {
        $books = [];
        $patron = Patron::find($id);
        $checkouts = Checkout::getByPatron($id);
        $checked_books = 
        return $app['twig']->render('patron.html.twig', ['patron'=>$patron,'books'=>$books, 'checkouts'=>$checkouts]);
    });

    $app->post("/sign_up", function() use($app) {
        $new_patron = new Patron($_POST['name'], $_POST['password'], $_POST['email']);
        $new_patron->encrypt_password();
        $new_patron->save();
        return $app->redirect('/patron/'.$new_patron->getId());
    });

    $app->post('/search', function() use($app) {
        $result = null;
        if($_POST['search_type'] == 'title') {
            $result = Book::search($_POST['search']);
        } elseif ($_POST['search_type'] == 'author') {
            $result = Author::search($_POST['search']);
        }
        $patron = Patron::find(intVal($_POST['patron_id']));
        return $app['twig']->render('patron.html.twig', ['patron'=>$patron, 'books'=>$result]);
    });

    $app->get('/copy/{id}', function($id) use($app) {
        $copy = Copy::find($id);
        $book = Book::find($copy->getBook_id());
        return $app['twig']->render('book.html.twig',['book'=>$book]);
    });

    $app->post('/checkout/{id}', function($id) use($app) {
        $copy = Copy::find($id);
        $patron_id = $_SESSION['patron']->getId();
        $checkout = new Checkout($patron_id, $id);
        $checkout->save();
        return $app->redirect('/patron/'.$patron_id);
    });

    $app->post('/add_book', function() use($app) {
        $title = $_POST['title'];
        $authors = $_POST['authors'];
        $copies = $_POST['copies'];
        $new_book = new Book($title);
        $new_book->save();
        $author_array = explode(',', $authors);
        foreach($author_array as $author){
            $author = trim($author);
            $new_author = new Author($author);
            $new_author->save();
            $new_author->addBook($new_book);
        }

        for($i = 0; $i<intVal($copies); $i++)
        {
            $copy = new Copy($new_book->getId());
            $copy->save();
        }

        return $app->redirect('/admin');
    });

    return $app;
 ?>
