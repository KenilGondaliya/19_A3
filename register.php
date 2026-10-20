<?php
session_start();
require "config/db.php";

if (!isset($_SESSION["num1"]) || !isset($_SESSION["num2"])) {
    $_SESSION["num1"] = rand(1, 9);
    $_SESSION["num2"] = rand(1, 9);
}
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $captcha = intval($_POST["captcha"] ?? 0);
    $correct_captcha =
        $_SESSION["num1"] + $_SESSION["num2"];
    if ($captcha != $correct_captcha) {
        $message = "Incorrect CAPTCHA.";
    }
    elseif ($name == "" || $email == "" || $password == "") {
        $message = "All fields are required.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email.";
    }
    else {
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );
        $check->bind_param("s", $email);
        $check->execute();
        $check_result = $check->get_result();
        if ($check_result->num_rows > 0) {
            $message = "Email already registered.";
        }
        else {
            $hashed_password =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );
            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, email, password)
                VALUES (?, ?, ?)"
            );
            $stmt->bind_param(
                "sss",
                $name,
                $email,
                $hashed_password
            );
            if ($stmt->execute()) {
                $message =
                    "Registration successful. You can now login.";

                $_SESSION["num1"] = rand(1, 9);
                $_SESSION["num2"] = rand(1, 9);
            }
            else {
                $message =
                    "Registration failed: " . $stmt->error;
            }
        }
    }
}
?>
<div class="container">
    <h2>User Registration</h2>
    <?php if ($message != ""): ?>
        <p>
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>
    <form method="POST">
        <label>Name</label>
        <br>
        <input
            type="text"
            name="name"
            required
        >
        <br><br>
        <label>Email</label>
        <br>
        <input
            type="email"
            name="email"
            required
        >
        <br><br>
        <label>Password</label>
        <br>
        <input
            type="password"
            name="password"
            required
        >
        <br><br>
        <label>
            CAPTCHA:
            <?= $_SESSION["num1"] ?>
            +
            <?= $_SESSION["num2"] ?>
            =
        </label>
        <br>
        <input
            type="number"
            name="captcha"
            required
        >
        <br><br>
        <button type="submit">
            Register
        </button>
    </form>
    <p>
        Already have an account?
        <a href="login.php">
            Login
        </a>
    </p>
</div>
<?php
?>
