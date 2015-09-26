<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    $app = new Silex\Application();

    $server = 'mysql:host=localhost:8889;dbname=to_do';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app)
    {
        return $app['twig']->render('index.html.twig', array('categories' => Category::getAll()));
    });



//this "$app->get("/categories", function() use($app)" below is also obsolete/unneeded, as it was the one directly above here, right?
    $app->get("/categories", function() use ($app)
    {
        return $app['twig']->render('category.html.twig', array('categories' => Category::getAll()));
    });
//this below function "broke" earlier, because i altered the index page to list by name... then app was calling it and disaplying an error for a non-object, because it was searching to display it by id type
    $app->get("/categories/{id}", function ($id) use ($app)
    {
        $category = Category::find($id);
        return $app['twig']->render('category.html.twig', array('category' => $category, 'tasks' => $category->getTasks()));
    });

    $app->post("/tasks", function() use ($app)
    {
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $task = new Task($description, $id = null, $category_id);
        $task->save();
        $category = Category::find($category_id);
        return $app['twig']->render('category.html.twig', array('category' => $category, 'tasks' => $category->getTasks()));
    });

    $app->post("/categories", function() use ($app)
    {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('index.html.twig', array('categories' => Category::getAll()));
    });

    $app->post("/delete_tasks", function() use ($app)
    {
        Task::deleteAll();
        return $app['twig']->render('delete_tasks.html.twig');
    });

    $app->post("/delete_category", function() use ($app)
    {
        Category::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    return $app;
 ?>
