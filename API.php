<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $conn->prepare("SELECT * FROM tasks");
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO tasks (title, description) VALUES (:title, :description)");
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Task added successfully"]);
        } else {
            echo json_encode(["error" => "Failed to add task"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE tasks SET title = :title, description = :description, status = :status WHERE id = :id");
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':status', $data['status']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Task updated successfully"]);
        } else {
            echo json_encode(["error" => "Failed to update task"]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $data['id']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Task deleted successfully"]);
        } else {
            echo json_encode(["error" => "Failed to delete task"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}
?>
