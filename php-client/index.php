<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Client Panel</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="../assets/img/ClinicaLog.ico" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: {
        families: ["Public Sans:300,400,500,600,700"]
      },
      custom: {
        families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
        urls: ["../css/fonts.min.css"],
      },   
      active: function() {
        sessionStorage.fonts = true;
      },
    });
  </script>
 
  <!-- CSS Files -->
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/plugins.min.css" />
  <link rel="stylesheet" href="../css/kaiadmin.min.css" />
  <link rel="stylesheet" href="../css/client.css">

  <!-- ICONS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pQnI6Z1ypA1QPTDdTnYkkpN0sE+0ZK3SAs+69IXS7SgSR/RG6upgjB8cSBaHh0FYv3cwUqq3Kv1BrV3iwGsnZw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="wrapper">
    <div class="main-panel" id="clientpanel">
      <!-- Header -->
      <div class="main-header" id="client_header"></div>
      <!-- Main Content -->
      <div class="container" id="content">
        <div class="page-inner">
        <div class="page-inner">
          <!-- Modal Structure -->
          <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="appointmentModalLabel">Appointment Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p id="appointmentDetails"></p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Appointment and Calendar Integration Start -->
          <div class="row">
            <div class="col-md-5 mb-4 d-flex">
              <div class="appointment-form">
                <h3>Set an Appointment</h3>
                <form id="appointmentForm">
                  <div class="mb-3">
                    <label for="appointmentDate" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="appointmentDate" required>
                  </div>
                  <div class="mb-3">
                    <label for="appointmentTime" class="form-label">Appointment Time <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" id="appointmentTime" required>
                  </div>
                  <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" rows="3"></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Set Appointment</button>
                </form>
              </div>
            </div>
            <div class="col-md-7 d-flex">
              <div class="calendar">
                <div class="calendar-header">
                  <div class="navigation">
                    <button id="prevMonth" aria-label="Previous Month"><i class="fas fa-chevron-left"></i></button>
                  </div>
                  <div class="month-year" id="monthYear"></div>
                  <div class="navigation">
                    <button id="nextMonth" aria-label="Next Month"><i class="fas fa-chevron-right"></i></button>
                  </div>
                </div>
                <div class="calendar-days">
                  <div>Sun</div>
                  <div>Mon</div>
                  <div>Tue</div>
                  <div>Wed</div>
                  <div>Thu</div>
                  <div>Fri</div>
                  <div>Sat</div>
                </div>
                <div class="calendar-dates" id="calendarDates"></div>
              </div>
            </div>
          </div>
          <!-- Appointment and Calendar Integration End -->
        </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Core JS Files -->
  <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>

  <!-- Core JS Files -->
  <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>

  <!-- jQuery Scrollbar -->
  <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

  <!-- Chart JS -->
  <script src="../assets/js/plugin/chart.js/chart.min.js"></script>

  <!-- jQuery Sparkline -->
  <script src="../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

  <!-- Chart Circle -->
  <script src="../assets/js/plugin/chart-circle/circles.min.js"></script>

  <!-- Datatables -->
  <script src="../assets/js/plugin/datatables/datatables.min.js"></script>

  <!-- Bootstrap Notify -->
  <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

  <!-- jQuery Vector Maps -->
  <script src="../assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
  <script src="../assets/js/plugin/jsvectormap/world.js"></script>

  <!-- Sweet Alert -->
  <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>

  <!-- Kaiadmin JS -->
  <script src="../assets/js/kaiadmin.min.js"></script>

  <!-- Appointment and Calendar Functionality Script -->
  <script>
    $(document).ready(function() {
      // Load Header
      $("#client_header").load("clientheader.php", function(response, status, xhr) {
        if (status == "error") {
          console.log("Error loading header: " + xhr.status + " " + xhr.statusText);
        }
      });
      // Calendar Functionality
      const monthYear = document.getElementById('monthYear');
      const calendarDates = document.getElementById('calendarDates');
      const prevMonthBtn = document.getElementById('prevMonth');
      const nextMonthBtn = document.getElementById('nextMonth');

      let currentDate = new Date();

      // Example appointment date
      const appointmentDate = new Date(2024, 9, 25); // October is month 9 in JavaScript (0-indexed)
      const appointmentMessage = "You have an appointment scheduled for this date at 9:30 AM";

      function renderCalendar(date) {
        // Clear previous dates
        calendarDates.innerHTML = '';

        const year = date.getFullYear();
        const month = date.getMonth();

        // Set month and year in header
        const options = {
          month: 'long',
          year: 'numeric'
        };
        monthYear.textContent = date.toLocaleDateString(undefined, options);

        // First day of the month
        const firstDay = new Date(year, month, 1).getDay();

        // Number of days in the month
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Today's date
        const today = new Date();
        const currentDay = today.getDate();
        const currentMonth = today.getMonth();
        const currentYear = today.getFullYear();

        // Fill in the blanks for the first week
        for (let i = 0; i < firstDay; i++) {
          const blankCell = document.createElement('div');
          blankCell.classList.add('bg-light');
          calendarDates.appendChild(blankCell);
        }

        // Populate the days of the current month
        for (let day = 1; day <= daysInMonth; day++) {
          const dateCell = document.createElement('div');

          // Create a span to hold the day number
          const daySpan = document.createElement('span');
          daySpan.textContent = day;

          // Highlight today
          if (day === currentDay && month === currentMonth && year === currentYear) {
            daySpan.classList.add('today');
          }

          // Check if the current day is the appointment date
          const selectedDate = new Date(year, month, day);
          if (selectedDate.toDateString() === appointmentDate.toDateString()) {
            daySpan.classList.add('appointment-day'); // Highlight the appointment date
          }

          // Add event listeners for date selection
          daySpan.addEventListener('click', function() {
            // Check if the selected date matches the appointment date
            if (selectedDate.toDateString() === appointmentDate.toDateString()) {
              // Set the appointment details and show the modal
              $('#appointmentDetails').text(appointmentMessage);
              $('#appointmentModal').modal('show');
            } else {
              alert(`You clicked on ${monthYear.textContent} ${day}, ${year}`);
            }
          });

          dateCell.appendChild(daySpan);
          calendarDates.appendChild(dateCell);
        }

        // Fill the remaining cells to complete the last week
        const totalCells = firstDay + daysInMonth;
        const remainingCells = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
        for (let i = 0; i < remainingCells; i++) {
          const blankCell = document.createElement('div');
          blankCell.classList.add('bg-light');
          calendarDates.appendChild(blankCell);
        }
      }

      // Initial render
      renderCalendar(currentDate);

      // Event listeners for navigation
      prevMonthBtn.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
      });

      nextMonthBtn.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
      });

      // Appointment Form Submission Handling
      $('#appointmentForm').on('submit', function(e) {
        e.preventDefault();

        // Gather form data
        const date = $('#appointmentDate').val();
        const time = $('#appointmentTime').val();
        const notes = $('#notes').val().trim();

        // Simple validation (extend as needed)
        if (!date || !time) {
          swal("Error", "Please fill in all required fields.", "error");
          return;
        }

        // For demonstration, we'll just show a success message
        swal("Success", "Your appointment has been set!", "success");

        // Reset the form
        $(this)[0].reset();
      });
    });
  </script>
</body>

</html>