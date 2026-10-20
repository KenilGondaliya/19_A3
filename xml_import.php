<?php

require "config/db.php";

$file = "xml/students.xml";


if (!file_exists($file)) {

    die("XML file not found.");

}


$xml =
    simplexml_load_file($file);


$stmt = $conn->prepare(
    "INSERT INTO students
    (roll_no,name,email,course,age)
    VALUES(?,?,?,?,?)"
);


$count = 0;


foreach ($xml->student as $student) {

    $roll_no =
        (string) $student->roll_no;

    $name =
        (string) $student->name;

    $email =
        (string) $student->email;

    $course =
        (string) $student->course;

    $age =
        (int) $student->age;


    $stmt->bind_param(
        "ssssi",
        $roll_no,
        $name,
        $email,
        $course,
        $age
    );


    if ($stmt->execute()) {

        $count++;

    }

}


echo "XML data imported successfully.";

echo "<br>";

echo "Total records: " . $count;

?>