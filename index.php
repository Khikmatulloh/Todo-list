<?php

require_once "vendor/autoload.php";
require_once "bootstrap.php";
require_once "routes.php";
// date_default_timezone_set('Asia/Tashkent');

// $update = json_decode(file_get_contents('php://input'));

// if (isset($update)) {
//     require 'bot/bot.php';
//     return;
// }
// $tasks = new Task();
// if ($_SERVER['REQUEST_URI']=='/tasks'){
//     echo json_encode($tasks->getAll());
//     return;
// }
// $path=parse_url($_SERVER['REQUEST_URI'])['path'];
// if($path=='/add'){
//     $tasks->add($update->$text,$update->userId);
//     return;
// }
// if ($path == '/complete') {
//     $tasks->complete($update->id);
//     return;
// }
// if ($path == '/uncomplete') {
//     $tasks->uncompleted($update->id);
//     return;
// }

// if ($path == '/delete') {
//    $tasks->delete($update->id);
//    return;
// }








// if (count($_GET) > 0 || count($_POST) > 0) {
//     $task = new Task();

//     if (isset($_POST['text'])) {
//         $task->add($_POST['text']);
//     }

//     if (isset($_GET['complete'])) {
//         $task->complete($_GET['complete']);
//     }

//     if (isset($_GET['uncompleted'])) {
//         $task->uncompleted($_GET['uncompleted']);
//     }

//     if (isset($_GET['delete'])) {
//         $task->delete($_GET['delete']);
//     }
// }

// require 'view/home.php';
