<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Registration System</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
        // Database configuration
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "user_registration";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        
        
        
        // Display the registration or login form based on the action
        if ($action == 'show_login') {
            displayLoginForm();
        } else {
            displayRegistrationForm();
        }
        // Handle user registration
        if ($action == 'register' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Validate form data
            if (empty($username) || empty($email) || empty($password)) {
                die("Please fill in all fields.");
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the database
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                echo "<p>Registration successful! <a href='index.php?action=show_login'>Login here</a></p>";
            } else {
                echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        }

        // Handle user login
        elseif ($action == 'login' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Validate form data
            if (empty($username) || empty($password)) {
                die("Please fill in all fields.");
            }

            // Retrieve user data from the database
            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Verify password
                if (password_verify($password, $row['password'])) {
                    echo "<p>Login successful!</p>";
                } else {
                    echo "<p>Invalid password.</p>";
                }
            } else {
                echo "<p>No user found with that username.</p>";
            }
        }
        // Function to display the registration form
        function displayRegistrationForm() {
            echo '<header>Register new account</header>
            <form method="post" action="index.php?action=register">
              <fieldset>
                 <br/>
                 <input type="text" name="username" id="username" placeholder="Username" required autofocus>
                 <br/><br/>
                 <input type="email" name="email" id="email" placeholder="E-mail" required>
                 <br/><br/>
                 <input type="password" name="password" id="password" placeholder="Password" required>
                 <br/><br/>
                 <br/> <br/>
                 <label for="submit"></label>
                 <input type="submit" name="submit" id="submit" value="REGISTER">
              </fieldset>
           </form>';
        }

        // Function to display the login form
        function displayLoginForm() {
            echo '<header>Login</header>
            <form method="post" action="index.php?action=login">
              <fieldset>
                 <br/>
                 <input type="text" name="username" id="username" placeholder="Username" required autofocus>
                 <br/><br/>
                 <input type="password" name="password" id="password" placeholder="Password" required>
                 <br/><br/>
                 <input type="submit" name="submit" id="submit" value="LOGIN">
              </fieldset>
           </form>';
        }

        $conn->close();
        ?>
        <!-- <div id="container"></div> -->
    </body>
</html>
