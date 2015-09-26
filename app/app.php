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

//Do I need this "$app->get("/tasks", func... blahblah blaah" as written below? still? what is this tasks.twig being used for? display? nah? then what? isn't it what i needed to have prior to the get written further down? the "$app->get("/categories/{id}", function ($id) use ($app)"?
    $app->get("/tasks", function() use ($app)
    {
        return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll()));
    });

//this "$app->get("/categories", function() use($app)" below is also obsolete/unneeded, as it was the one directly above here, right?
    $app->get("/categories", function() use ($app)
    {
        return $app['twig']->render('category.html.twig', array('categories' => Category::getAll()));
    });

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
        return $app['twig']->render('tasks.html.twig');
    });

    $app->post("/delete_category", function() use ($app)
    {
        Category::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    return $app;
 ?>
