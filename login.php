
<?php

session_start();

require "config/db.php";

$message = "";


/* ==========================================
   LOGIN
========================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";


    if ($email == "" || $password == "") {

        $message = "Email and password are required.";
    }

    else {

        $stmt = $conn->prepare(
            "SELECT id, name, email, password
             FROM users
             WHERE email = ?"
        );

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();


            if (
                password_verify(
                    $password,
                    $user["password"]
                )
            ) {

                $_SESSION["user_id"] =
                    $user["id"];

                $_SESSION["user_name"] =
                    $user["name"];

                $_SESSION["user_email"] =
                    $user["email"];

                header("Location: index.php");
                exit;
            }

            else {
                $message =
                    "Invalid email or password.";
            }
        }

        else {

            $message =
                "Invalid email or password.";
        }
    }
}


?>

<div class="container">

    <h2>User Login</h2>

    <?php if ($message != ""): ?>

        <p>
            <?= htmlspecialchars($message) ?>
        </p>

    <?php endif; ?>


    <form method="POST">

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


        <button type="submit">
            Login
        </button>

    </form>


    <p>
        Don't have an account?

        <a href="register.php">
            Register
        </a>
    </p>

</div>

<?php
?>
