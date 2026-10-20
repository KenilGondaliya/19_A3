<?php

require "config/db.php";


$result =
    $conn->query(
        "SELECT
            roll_no,
            name,
            email,
            course,
            age
         FROM students"
    );


$xml =
    new SimpleXMLElement(
        '<?xml version="1.0" encoding="UTF-8"?>
        <students/>'
    );


while (
    $student =
    $result->fetch_assoc()
) {

    $node =
        $xml->addChild("student");


    $node->addChild(
        "roll_no",
        htmlspecialchars($student['roll_no'])
    );

    $node->addChild(
        "name",
        htmlspecialchars($student['name'])
    );

    $node->addChild(
        "email",
        htmlspecialchars($student['email'])
    );

    $node->addChild(
        "course",
        htmlspecialchars($student['course'])
    );

    $node->addChild(
        "age",
        $student['age']
    );

}


$file =
    "xml/students_export.xml";


$xml->asXML($file);


echo "MySQL data exported successfully.";

echo "<br>";

echo "<a href='$file'>Open XML File</a>";

?>