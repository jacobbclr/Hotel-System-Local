<?php

include("includes/database.php");
include("includes/config.php");
include("includes/functions.php");
checkAuthorization();
include("includes/header.php");

?>
<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
        <span class="ms-1 dark:text-white text-gray-900 font-medium">Letzte Buchungen</span>
        <div class="relative overflow-x-auto shadow-md rounded-lg mb-4">

            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Zimmer
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Datum
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Personen
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Details</span>
                        </th>
                    </tr>
                </thead>
                <?php
                // Assuming you have established a MySQLi connection
                
                // Fetch the bookings data from the tl_bookings table
                $query = "SELECT * FROM tl_bookings WHERE startDate >= NOW()";
                $result = mysqli_query($connect, $query);

                while ($booking = mysqli_fetch_assoc($result)) {
                    $name = $booking['name'];
                    $location = $booking['room'];
                    $startDate = date('d.m.Y', strtotime($booking['startDate']));
                    $endDate = date('d.m.Y', strtotime($booking['endDate']));
                    $personCount = $booking['personCount'];
                    ?>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?php echo $name; ?>
                        </th>
                        <td class="px-6 py-4">
                            <?php echo $location; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php echo $startDate . ' - ' . $endDate; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php echo $personCount; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Details</a>
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
        <span class="ms-1 dark:text-white text-gray-900 font-medium ">Kallender</span>
        <!-- component -->
        <div class="flex items-center justify-center border-none">

            <div class="w-full mx-auto mb-4 border-none">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden border-none">
                    <div
                        class="flex font-medium items-center justify-between px-6 py-3 text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <button id="prevMonth" class="">Previous</button>
                        <h2 id="currentMonth" class=""></h2>
                        <button id="nextMonth" class="">Next</button>
                    </div>
                    <div class=" dark:text-white font-medium border-none text-gray-900 dark:bg-gray-800 grid grid-cols-7 gap-2 p-4"
                        id="calendar">
                        <!-- Calendar Days Go Here -->
                    </div>
                    <div class="flex justify-evenly border-none dark:bg-gray-800 dark:text-white pb-1">

                        <span class="flex items-center text-sm font-medium text-gray-900 dark:text-white me-3"><span
                                class="flex w-2.5 h-2.5 bg-blue-400 rounded-full me-1.5 flex-shrink-0"></span>Heute
                            In</span>
                        <span class="flex items-center text-sm font-medium text-gray-900 dark:text-white me-3"><span
                                class="flex w-2.5 h-2.5 bg-green-500 rounded-full me-1.5 flex-shrink-0"></span>Check
                            In</span>
                        <span class="flex items-center text-sm font-medium text-gray-900 dark:text-white me-3"><span
                                class="flex w-2.5 h-2.5 bg-red-500 rounded-full me-1.5 flex-shrink-0"></span>Check
                            Out</span>
                        <span class="flex items-center text-sm font-medium text-gray-900 dark:text-white me-3"><span
                                class="flex w-2.5 h-2.5 bg-teal-500 rounded-full me-1.5 flex-shrink-0"></span>To
                            Do</span>
                        <span class="flex items-center text-sm font-medium text-gray-900 dark:text-white me-3"><span
                                class="flex w-2.5 h-2.5 bg-yellow-300 rounded-full me-1.5 flex-shrink-0"></span>Wichtig</span>

                    </div>
                    <div id="myModal" class="modal hidden fixed inset-0 flex items-center justify-center z-50">
                        <div class="modal-overlay absolute inset-0 bg-black opacity-50"></div>

                        <div
                            class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
                            <div class="modal-content py-4 text-left px-6">
                                <div class="flex justify-between items-center pb-3">
                                    <p class="text-2xl font-bold">Selected Date</p>
                                    <button id="closeModal"
                                        class="modal-close px-3 py-1 rounded-full bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring">✕</button>
                                </div>
                                <div id="modalDate" class="text-xl font-semibold"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>


            // Function to generate the calendar for a specific month and year
            function generateCalendar(year, month) {
                const calendarElement = document.getElementById('calendar');
                const currentMonthElement = document.getElementById('currentMonth');

                // Create a date object for the first day of the specified month
                const firstDayOfMonth = new Date(year, month, 1);
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                // Clear the calendar
                calendarElement.innerHTML = '';

                // Set the current month text
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                currentMonthElement.innerText = `${monthNames[month]} ${year}`;

                // Calculate the day of the week for the first day of the month (0 - Sunday, 1 - Monday, ..., 6 - Saturday)
                const firstDayOfWeek = firstDayOfMonth.getDay();

                // Create headers for the days of the week
                const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                daysOfWeek.forEach(day => {
                    const dayElement = document.createElement('div');
                    dayElement.className = 'text-center font-semibold';
                    dayElement.innerText = day;
                    calendarElement.appendChild(dayElement);
                });

                // Create empty boxes for days before the first day of the month
                for (let i = 0; i < firstDayOfWeek; i++) {
                    const emptyDayElement = document.createElement('div');
                    calendarElement.appendChild(emptyDayElement);
                }

                // Create boxes for each day of the month
                var bookings = [];
                var todos = [];

                $(document).ready(function () {
                    // AJAX request to retrieve bookings and todos data
                    $.ajax({
                        url: 'home.php?action=getBookings',
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            
                            bookings = data.bookings;
                            todos = data.todos;
                            console.log(todos);
                            // Create boxes for each day of the month
                            for (let day = 1; day <= daysInMonth; day++) {
                                const dayElement = document.createElement('div');
                                dayElement.className = 'text-center py-2 border dark:border-gray-700 cursor-pointer';
                                dayElement.innerText = day;

                                // Check if this date is the current date
                                const currentDate = new Date();
                                if (year === currentDate.getFullYear() && month === currentDate.getMonth() && day === currentDate.getDate()) {
                                    dayElement.classList.add('bg-blue-500', 'text-white');
                                }

                                // Check if this date is a booking start or end date
                                bookings.forEach(booking => {
                                    const startDate = new Date(booking.startDate);
                                    const endDate = new Date(booking.endDate);

                                    if (year === startDate.getFullYear() && month === startDate.getMonth() && day === startDate.getDate()) {
                                        dayElement.classList.add('checkIn_' + booking.id);
                                        dayElement.classList.add('bg-green-500', 'text-white');
                                    }

                                    if (year === endDate.getFullYear() && month === endDate.getMonth() && day === endDate.getDate()) {
                                        dayElement.classList.add('checkOut_' + booking.id);
                                        dayElement.classList.add('bg-red-500', 'text-white');
                                    }
                                });

                                // Check if this date has a todo
                                todos.forEach(todo => {
                                    const todoDate = new Date(todo.date_todo);

                                    if (year === todoDate.getFullYear() && month === todoDate.getMonth() && day === todoDate.getDate()) {
                                        dayElement.classList.add('todo_' + todo.id_todo);
                                        dayElement.classList.add('bg-teal-600', 'text-white');
                                    }
                                });

                                calendarElement.appendChild(dayElement);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log('Error:', error);
                        }
                    });
                });
            }

            // Initialize the calendar with the current month and year
            const currentDate = new Date();
            let currentYear = currentDate.getFullYear();
            let currentMonth = currentDate.getMonth();
            generateCalendar(currentYear, currentMonth);

            // Event listeners for previous and next month buttons
            document.getElementById('prevMonth').addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                generateCalendar(currentYear, currentMonth);
            });

            document.getElementById('nextMonth').addEventListener('click', () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                generateCalendar(currentYear, currentMonth);
            });

            // Function to show the modal with the selected date
            function showModal(selectedDate) {
                const modal = document.getElementById('myModal');
                const modalDateElement = document.getElementById('modalDate');
                modalDateElement.innerText = selectedDate;
                modal.classList.remove('hidden');
            }

            // Function to hide the modal
            function hideModal() {
                const modal = document.getElementById('myModal');
                modal.classList.add('hidden');
            }


            document.addEventListener('DOMContentLoaded', () => {
                const calendarElement = document.getElementById('calendar');
                calendarElement.addEventListener('click', (event) => {
                    if (event.target.classList.contains('cursor-pointer')) {
                        const day = parseInt(event.target.innerText);
                        const selectedDate = new Date(currentYear, currentMonth, day);
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        const formattedDate = selectedDate.toLocaleDateString(undefined, options);
                        showModal(formattedDate);
                    }
                });
            });

            // Event listener for closing the modal
            document.getElementById('closeModal').addEventListener('click', () => {
                hideModal();
            });



        </script>

        <!-- Modal toggle -->
        <div class="flex justify-center m-5">
            <button id="readProductButton" data-modal-target="readProductModal" data-modal-toggle="readProductModal"
                class="block text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                type="button">
                Show read modal
            </button>
        </div>

        <!-- Main modal -->
        <div id="readProductModal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
            <div class="relative p-4 w-full max-w-xl h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                    <!-- Modal header -->
                    <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                        <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                            <h3 class="font-semibold ">
                                Freitag 12.01.2024
                            </h3>
                        </div>
                        <div>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-toggle="readProductModal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                    </div>
                    <dl>
                        <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Informationen</dt>
                        <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">Es stehen keine Termine an
                        </dd>
                        <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Termin typen</dt>
                        <dd class="mb-4 font-light text-gray-500 sm:mb-5 dark:text-gray-400">keine</dd>
                    </dl>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <button type="button"
                                class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                <svg aria-hidden="true" class="mr-1 -ml-1 w-5 h-5" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                    </path>
                                    <path fill-rule="evenodd"
                                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Edit
                            </button>
                            <button type="button"
                                class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                Preview
                            </button>
                        </div>
                        <button type="button"
                            class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                            <svg aria-hidden="true" class="w-5 h-5 mr-1.5 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

include("includes/footer.php")
    ?>