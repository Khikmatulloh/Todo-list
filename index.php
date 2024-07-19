<?php

require_once "vendor/autoload.php";
date_default_timezone_set('Asia/Tashkent');

$update = json_decode(file_get_contents('php://input'));

if (isset($update)) {
    require 'bot/bot.php';
    return;
}

$taskObj = new Task();

if (count($_GET) > 0 || count($_POST) > 0) {

    if (isset($_POST['text'])) {
        if (isset($_POST['update_id'])) {
           
            $taskObj->update((int)$_POST['update_id'], $_POST['text']);
        } else {
            
            $taskObj->add($_POST['text']);
        }
    }

    if (isset($_GET['complete'])) {
        $taskObj->complete((int)$_GET['complete']);
    }

    if (isset($_GET['uncompleted'])) {
        $taskObj->uncompleted((int)$_GET['uncompleted']);
    }

    if (isset($_GET['delete'])) {
        $taskObj->delete((int)$_GET['delete']);
    }

    if (isset($_GET['update'])) {
        // Taskni tahrirlash uchun olish
        $task = $taskObj->get((int)$_GET['update']);
        $updateText = $task['task'];
        $updateId = $task['id'];
    } else {
        $updateText = '';
        $updateId = '';
    }
}

require 'view/home.php';
