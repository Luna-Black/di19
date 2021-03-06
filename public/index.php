<?php
session_start();
require '../vendor/autoload.php';

function chargerClasse($classe){
    $ds = DIRECTORY_SEPARATOR;
    $dir = __DIR__."{$ds}.."; //Remonte d'un cran par rapport à index.php
    $classeName = str_replace('\\', $ds,$classe);

    $file = "{$dir}{$ds}{$classeName}.php";
    if(is_readable($file)){
        require_once $file;
    }
}

spl_autoload_register('chargerClasse');

$router = new \src\Router\Router($_GET['url']);
$router->get('/', "Article#ListAll");
$router->get('/Article', "Article#ListAll");
$router->get('/Article/Show/:id', 'Article#show');
$router->get('/Article/Update/:id', "Article#Update#id");
$router->post('/Article/Update/:id', "Article#Update#id");
$router->get('/Article/Add', "Article#add");
$router->post('/Article/Add', "Article#add");
$router->get('/Article/Delete/:id', "Article#Delete#id");
$router->get('/Article/Fixtures', "Article#Fixtures");
$router->get('/Article/Write', "Article#Write");
$router->get('/Article/Read', "Article#Read");
$router->get('/Article/WriteOne/:id', "Article#Read#id");
$router->get('/Api/Article/:token', "Api#ArticleGet#token");
$router->get('Api/Token/:id', 'Api#generateToken#id');
$router->post('/Api/Article', "Api#ArticlePost");
$router->put('/Api/Article/:id/:json', "Api#ArticlePut#id#json");
$router->get('/Article/ListAll','Article#listAll');
$router->get('/Contact', 'Contact#showForm');
$router->post('/Contact/sendMail', 'Contact#sendMail');
$router->post('/ContactArticle/:id', 'Contact#sendMailByArticle#id');
$router->get('/Login', 'User#loginForm');
$router->post('/Login', 'User#loginCheck');
$router->get('/Logout', 'User#logout');
$router->get('/Article/Search/:keyword', 'Article#search#keyword');
$router->get('/Admin/Articles/:id', 'Article#listByStatus#id');
$router->get('Admin/Article/Status/:id/:idstatus','Article#updateStatus#id#idstatus');
$router->get('/Admin/Categories/Delete/:id',"Category#delete#id");
$router->get('/Admin/Categories/Update/:id','Category#update#id');
$router->post('/Admin/Categories/Update/:id','Category#update#id');
$router->post('/Admin/Categories/Add',"Category#add");
$router->get('/Admin/Categories',"Category#listAll");
$router->post('/Admin/Categories',"Category#listAll");
$router->get('/Admin/Users', "User#listAll");
$router->get('Userpage/:username', "User#showUserPage#username");
$router->get('/Admin/Users/Permissions/:username', 'User#updatePermissions#username');
$router->post('/Admin/Users/Permissions/:username', 'User#updatePermissions#username');
$router->post('/Admin/Users/UpdateRole/:id', 'User#updateRole#id');
$router->get('/SignUp', 'User#showSignUp');
$router->post('/SignUp', 'User#signup');


echo $router->run();



