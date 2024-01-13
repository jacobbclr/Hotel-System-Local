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
                    <a href="add-todo.php"
                        class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        type="button">
                        <span class="sr-only">To Do hinzufügen</span>
                        To Do hinzufügen

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
                                Details
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
                        $query = "SELECT * FROM tl_todos ORDER BY date";
                        $result = mysqli_query($connect, $query);

                        while ($todos = mysqli_fetch_assoc($result)) {
                            $name = $todos['name'];
                            $importance = $todos['importance'];
                            $date = date('d.m.Y', strtotime($todos['date']));
                            $details = $todos['details'];
                            $color = $todos['color'];
                            $state = $todos['state'];
                            $id = $todos['id'];
                            ?>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                                <th scope="row"
                                    class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    
                                    <div class="ps-3">
                                        <div class="text-base font-semibold">
                                            <?php echo $name; ?>
                                        </div>
                                        <div class="font-normal text-gray-500">
                                            <?php echo $importance; ?>
                                        </div>
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    <?php echo $details; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo $date; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <?php if ($state == 1) { ?>
                                            <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> Aktiv
                                        <?php } else { ?>
                                            <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div> Inaktiv
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="edit-todo.php?id=<?php echo $id; ?>"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
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