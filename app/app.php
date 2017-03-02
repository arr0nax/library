<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Patron.php";
    require_once __DIR__."/../src/Checkout.php";

    $app = new Silex\Application();
    $app['debug']=true;


    $server = 'mysql:host=localhost:8889;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    session_start();
    if(empty($GLOBALS['DB']->query("SELECT * FROM patrons"))){
        $_SESSION['patron'] = new Patron('anonymous', 'anonymous', 'anonymous@anonymous.com');
        $_SESSION['patron']->save();
    }
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

    $app->post("/sign_in", function() use($app) {
        if('anonymous@anonymous.com' == $_POST['email'] && 'anonymous' ==  $_POST['password']){
            $_SESSION['patron'] = Patron::login($_POST['email'], $_POST['password']);
            return $app['twig']->render('patron.html.twig');
        }
        return $app->redirect('/');
    });

    $app->get('/sign_in', function() use($app) {
        return $app['twig']->render('patron.html.twig');
    });

    return $app;
 ?>
