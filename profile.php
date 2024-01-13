<?php

include("includes/database.php");
include("includes/config.php");
include("includes/functions.php");
checkAuthorization();
$id = $_SESSION["user_id"]; // Get the ID from the query parameters or other source

// Fetch the user data from the tl_users table
$query = "SELECT * FROM tl_users WHERE id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if the user exists and retrieve the data
if ($row = mysqli_fetch_assoc($result)) {
    $name = $row['name'];
    $email = $row['username'];
    $password = $row['password'];
    $position = $row['rechte'];
    $status = $row['state'];
} else {
    // User does not exist, handle the error
    echo "User not found.";
    exit();
}

// Free the result set
mysqli_free_result($result);

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve form data
    $nameNew = $_POST['name'];
    $emailNew = $_POST['username'];
    if($_POST['password'] === "")
    {
        $passwordNew = $password;
    } else {
        $passwordNew = sha1($_POST['password']);
    }
    
    // Hash the password using SHA-1
    $positionNew = $_POST['position'];
    $statusNew = $_POST['status'];

    // Update data in the tl_users table
    $query = "UPDATE tl_users SET name = ?, username = ?, password = ?, rechte = ?, state = ? WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "sssssi", $nameNew, $emailNew, $passwordNew, $positionNew, $statusNew, $id);
    mysqli_stmt_execute($stmt);

    // Check if the insertion was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Insertion successful
        echo "User edited successfully!";
        header("Location: users.php");
    } else {
        // Insertion failed
        echo "Error editing user.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Close the MySQLi connection
    mysqli_close($connect);

    // Assuming you have established a MySQLi connection

}
include("includes/header.php");
?>
<div class="p-4 sm:ml-64">
            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
            <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Neuen Benutzer hinzufügen</h2>
        <form method="post">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input type="text" name="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Name" required="" value="<?php echo $name; ?>" autocomplete="false">
                </div>
                <div class="w-full">
                    <label for="username"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" name="username" id="username"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Email" required autocomplete="false" value="<?php echo $email; ?>">
                </div>
                <div class="w-full">
                    <label for="password"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Passwort</label>
                    <input type="password" name="password" id="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Passwort"  autocomplete="none" value="">
                </div>
                <div class="w-full">
                    <label for="position"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position</label>
                    <input type="text" name="position" id="position"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Position" required="" autocomplete="none" value="<?php echo $position; ?>">
                </div>

                <div>
                    <label for="passwordRepeat"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Passwort
                        wiederholen</label>
                    <input type="password" name="passwordRepeat" id="passwordRepeat"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Passwort wiederholen"  value="">
                </div>
                <div>
                    <label for="status"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                    <select id="status" name="status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option>Status auswälen</option>
                        <option <?php if($status === 1){echo "selected"; }?> value="1">Aktiv</option>
                        <option <?php if($status === 2){echo "selected"; }?> value="2">Inaktiv</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="description"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea id="description" rows="8"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Your description here"></textarea>
                </div>
            </div>
            <button type="submit"
                class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Absenden</button>
        </form>
    </div>
            </div>
        </div>

<?php

include("includes/footer.php") 
?>