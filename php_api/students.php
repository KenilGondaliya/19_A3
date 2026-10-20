<?php

header(
    "Content-Type: application/json"
);

require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] != "GET") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Only GET method allowed"
    ]);

    exit;
}

$id =
    intval($_GET['id'] ?? 0);

if ($id > 0) {

    $stmt =
        $conn->prepare(
            "SELECT *
             FROM students
             WHERE id = ?"
        );

    $stmt->bind_param(
        "i",
        $id
    );

    $stmt->execute();

    $student =
        $stmt
            ->get_result()
            ->fetch_assoc();

    echo json_encode([
        "success" => true,
        "student" => $student
    ]);

} else {

    $result =
        $conn->query(
            "SELECT *
             FROM students"
        );

    $students = [];

    while (
        $row =
        $result->fetch_assoc()
    ) {
        $students[] = $row;
    }

    echo json_encode([
        "success" => true,
        "students" => $students
    ]);
}
?>