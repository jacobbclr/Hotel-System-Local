<?php

include("includes/database.php");
include("includes/config.php");
include("includes/functions.php");
if (isset($_SESSION["user_id"])){
    header("Location: home.php");
}
include("includes/header.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted email and password
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    // Proceed only if email and password are not empty
    if (!empty($email) && !empty($password)) {
        // Hash the password using SHA1 (not recommended, consider using stronger algorithms)
        $hashedPassword = sha1($password);

        // Prepare and execute the SQL query to fetch user details
        if ($stmt = $connect->prepare("SELECT * FROM tl_users WHERE username = ? AND password = ?")) {
            $stmt->bind_param('ss', $email, $hashedPassword);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
        }

        // If a user with the given email and password exists
        if ($user) {
            // Store user data in the session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["email"] = $user["email"];

            // Redirect to the home page or any other authenticated page
            header("Location: home.php");
            exit();
        } else {
            // Invalid login credentials
            $loginError = "Invalid email or password.";
        }
    }
}
?>


<div class="flex items-center h-full">
    <form method="post" class="w-[300px] max-w-[300px] mx-auto">
        <div class="mb-5">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
            <input type="text" id="email" name="email"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="name@flowbite.com" required>
        </div>
        <div class="mb-5">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Passwort</label>
            <input type="password" id="password" name="password"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required>
        </div>
        <div class="flex items-start mb-5">
            <div class="flex items-center h-5">
                <input id="remember" type="checkbox" value=""
                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800">
            </div>
            <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Login merken</label>
        </div>
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Anmelden</button>
    </form>
    <?php if (isset($loginError)) { ?>
        <div>
            <?php echo $loginError; ?>
        </div>
    <?php } ?>
</div>

<?

include("includes/footer.php") ?>