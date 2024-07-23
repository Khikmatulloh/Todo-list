<?php

declare(strict_types=1);

use GuzzleHttp\Client;

class Bot
{
    const TOKEN = "7300404549:AAHNwPph6Zse8wyXhxHjFQ88C8HYe_J7yvA";
    const API = "https://api.telegram.org/bot" . self::TOKEN . "/";
    public Client $http;
    private PDO $pdo;

    public function __construct()
    {
        $this->http = new Client(['base_uri' => self::API]);
        $this->pdo = DB::connect();
    }

    public function handleStartCommand(int $chatId): void
    {
        $this->http->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => 'Welcome to The Best TODO App ever in entire Universe!',
            ]
        ]);
    }

    public function handleAddCommand(int $chatId): void
    {
        $status = 'add';
        $query = "INSERT INTO tasks (chat_id, status, created_at)
                  VALUES (:chat_id, :status, NOW())
                  ON DUPLICATE KEY UPDATE status = :status, created_at = NOW()";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':chat_id', $chatId);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        $this->http->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => 'Please, enter your text',
            ]
        ]);
    }

    public function addTask(int $chatId, string $text): void
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE chat_id = :chat_id LIMIT 1");
        $stmt->execute(['chat_id' => $chatId]);
        $userId = $stmt->fetchObject()->id;

        $task = new Task();
        $task->add($text, $userId);

        $status = null;
        $stmt = $this->pdo->prepare("UPDATE users SET status = :status WHERE chat_id = :chatId");
        $stmt->bindParam(':chatId', $chatId);
        $stmt->bindParam(':status', $status, PDO::PARAM_NULL);
        $stmt->execute();

        $this->http->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => 'Task added successfully',
            ]
        ]);
    }

    public function getAllTasks(int $chatId): void
    {
        $query = "SELECT * FROM todos WHERE user_id = (SELECT id FROM users WHERE chat_id = :chatId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':chatId', $chatId);
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tasks = $this->prepareTasks($tasks);

        $this->http->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $this->prepareTexts($tasks),
                'reply_markup' => $this->prepareButtons($tasks)
            ]
        ]);
    }

    private function prepareTasks(array $tasks): array
    {
        $result = [];
        foreach ($tasks as $task) {
            $result[] = [
                'task_id' => $task['id'],
                'text' => $task['text'],
                'status' => $task['status']
            ];
        }

        return $result;
    }

    private function prepareTexts(array $tasks): string
    {
        $text = '';
        $counter = 1;
        foreach ($tasks as $task) {
            $status = $task['status'] === 0 ? '🟩' : '✅';
            $text .= $status . " " . $counter . ". " . $task['text'] . "\n";
            $counter++;
        }

        return $text;
    }

    private function prepareButtons(array $tasks): string
    {
        $buttons = ['inline_keyboard' => []];
        foreach ($tasks as $task) {
            $buttons['inline_keyboard'][] = [
                ['text' => 'Complete', 'callback_data' => 'complete:' . $task['task_id']],
                ['text' => 'Delete', 'callback_data' => 'delete:' . $task['task_id']]
            ];
        }

        return json_encode($buttons);
    }

    public function handleInlineButton(int $chatId, string $data): void
    {
        $task = new Task();

        list($action, $taskId) = explode(':', $data);

        if ($action === 'complete') {
            $currentTask = $task->getTask((int)$taskId);

            if ($currentTask->status === 0) {
                $task->complete((int)$taskId);
                $text = 'Task completed';
            } else {
                $task->uncompleted((int)$taskId);
                $text = 'Task uncompleted';
            }
        } elseif ($action === 'delete') {
            $task->delete((int)$taskId);
            $text = 'Task deleted';
        } else {
            $text = 'Unknown action';
        }

        $this->http->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $text,
            ]
        ]);

        $this->getAllTasks($chatId);
    }
}
