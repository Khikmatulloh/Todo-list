<?php
$router=new Router();
$task=new Task();


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($router->getResourceId()) {
        $router->sendResponse(
            $task->getTask(
                $router->getResourceId()
            )
        );
        return;
    }



    $router->sendResponse($task->getAll());
    return;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($router->getUpdates()->text)) {
        $router->sendResponse([
            'message' => 'text is not found',
            'code' => 403
        ]);
        return;
    }
    
    $newTask = $task->add($router->getUpdates()->text, userId: 35);
    $responseText = $newTask ? 'New task has been added' : 'Something went wrong';
    $router->sendResponse($responseText);
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    if (!isset($router->getUpdates()->todoId)) {
        $router->sendResponse([
            'message' => 'task_id is not found',
            'code' => 403
        ]);
        return;
    }

    $task_id = $router->getUpdates()->todoId;
    $single_task = $task->getTask($task_id);

    if ($single_task->status) {
        $task->uncompleted($task_id);
        $router->sendResponse([
            'message' => 'task is uncompleted',
            'code' => 200
        ]);
        return;
    }

    $task->complete($task_id);
    $router->sendResponse([
        'message' => 'task is completed',
        'code' => 200
    ]);
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
if (!isset($router->getUpdates()->todoId)) {
    $router->sendResponse([
        'message' => 'todoId is not found',
        'code' => 403
    ]);
    return;
}

$task_id = $router->getUpdates()->todoId;
$task->delete($task_id);
$router->sendResponse([
    'message' => 'task is deleted',
    'code' => 200
]);
return;
}
