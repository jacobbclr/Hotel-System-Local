<?php

include("includes/database.php");
include("includes/config.php");
include("includes/functions.php");
checkAuthorization();
include("includes/header.php");

?>
<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">



        <div class="relative overflow-x-auto sm:rounded-lg mb-4">
            <div
                class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 mb-3 md:space-y-0 bg-white dark:bg-gray-900">
                <div class="">
                    <a  href="add-user.php"
                        class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        type="button">
                        <span class="sr-only">Benutzer hinzufügen</span>
                        Benutzer hinzufügen

                    </a>
                </div>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-md">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 ">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Position
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Datum
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Aktion
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Assuming you have established a MySQLi connection
                        
                        // Fetch the bookings data from the tl_bookings table
                        $query = "SELECT * FROM tl_users ORDER BY id ASC LIMIT 6";
                        $result = mysqli_query($connect, $query);

                        while ($users = mysqli_fetch_assoc($result)) {
                            $name = $users['name'];
                            $email = $users['username'];
                            $date = date('d.m.Y', strtotime($users['date']));
                            $rechte = $users['rechte'];
                            $state = $users['state'];
                            $id = $users['id'];
                            ?>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                                <th scope="row"
                                    class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    <svg class="w-10 h-10 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                                    </svg>                        
                                    <div class="ps-3">
                                        <div class="text-base font-semibold">
                                            <?php echo $name; ?>
                                        </div>
                                        <div class="font-normal text-gray-500">
                                            <?php echo $email; ?>
                                        </div>
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    <?php echo $rechte; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo $date; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <?php if ($state == 1) { ?>
                                            <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> Active
                                        <?php } else { ?>
                                            <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div> Inactive
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                <a href="edit-user.php?id=<?php echo $id; ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit user</a>
                                </td>
                            </tr>
                            <?php
                        }
                        // Close the MySQLi connection and free the result set
                        mysqli_free_result($result);
                        mysqli_close($connect);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php

include("includes/footer.php")
    ?>