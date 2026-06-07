@extends('App.Layout')

@section('container')
<style>
  #customer-rate-graph1 {
    width: 100%;
    height: 300px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
  }

  /* Calendar styling */
  .date-picker-container {
    display: flex;
    align-items: center;
  }

  .date-picker-wrapper {
    cursor: pointer;
  }

  #attendance_date_display {
    background-color: #fff;
    cursor: pointer;
    font-size: 14px;
  }

  .calendar-trigger {
    cursor: pointer;
    transition: all 0.2s ease;
    background-color: #f0f8ff;
    border-color: #ccc;
  }

  .calendar-trigger:hover {
    background-color: #e0f2fe;
  }

  .calendar-trigger i {
    font-size: 1.2rem;
    color: #0d6efd;
  }

  /* Flatpickr customization */
  .flatpickr-calendar {
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
  }
</style>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div class="pc-content">
  <div class="col-md-6 col-sm-12">
    <h5 class="mb-0 ms-2 d-md-block d-none">Dashboard</h5>

    {{-- Search Options --}}
    <div class="px-3 py-2 position-relative">
      <div class="input-group">
          <span class="input-group-text bg-white border-end-0">
              <i class="ph-duotone ph-magnifying-glass"></i>
          </span>
          <input id="sidebarMenuSearch" type="text" class="form-control border-start-0"
              placeholder="Search menu..." autocomplete="off">
      </div>

      <div id="sidebarSearchResults" class="dropdown-menu w-100 mt-1 shadow" style="display: none; max-height: 240px; overflow-y: auto;"></div>
    </div>
  </div>


  <div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-12">
          <div class="card statistics-card-1 overflow-hidden">
            
            <div class="card-body">
              <img src="../assets/images/widget/img-status-8.svg" alt="img" class="img-fluid img-bg">
              <div class="d-flex align-items-center">
                <img src="../assets/images/widget/takeover.png" alt="img" class="img-fluid">
                <div class="flex-grow-1 ms-3">
                  <h4 class="mb-2">Monthwise Payment Status <i class="ph-duotone ph-question" data-bs-toggle="tooltip" aria-label="View" data-bs-original-title="View"></i></h4>
                  <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6 d-flex align-items-end justify-content-end">
                      <select class="form-control form-control-xxl border-1 " id="total_receivales">
                        <option value="1">January</option>
						<option value="2">February</option>
						<option value="3">March</option>
						<option value="4">April</option>
						<option value="5">May</option>
						<option value="6">June</option>
						<option value="7">July</option>
						<option value="8">August</option>
						<option value="9">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row g-3 mt-3 text-center">
                <div class="col-4">
                  <p class="mb-0 text-muted">Total Govt. Fees Paid</p>
                  <h5 class="mb-0 text-primary" id="govFees">₹0</h5>
                </div>
                <div class="col-4 border-start">
                  <p class="mb-0 text-muted">Total Amount Received</p>
                  <h5 class="mb-0 text-success" id="totalReceived">₹0</h5>
                </div>
                <div class="col-4 border-start">
                  <p class="mb-0 text-muted">Total Amount Due</p>
                  <h5 class="mb-0 text-danger" id="totalDue">₹0</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
		
		<div class="col-lg-12">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5>Employee Attendance List</h5>
              <div class="date-picker-container">
                <div class="input-group">
                  <input type="text" id="attendance_date_display" class="form-control" readonly>
                  <button type="button" class="input-group-text calendar-trigger" id="calendar-btn">
                    <i class="ti ti-calendar"></i>
                  </button>
                  <input type="hidden" id="attendence_count" name="attendence_count">
                </div>
              </div>
            </div>


            <div class="card-body">
              <div class="bg-body mt-3 py-2 px-3 rounded d-flex align-items-center justify-content-between">
                <p class="mb-0"><i class="ph-duotone ph-circle text-purple-500 f-12"></i> Total Employees</p>
                <h5 class="mb-0 ms-1" id="totalEmployee">0</h5>
              </div>
              <div class="bg-body mt-1 py-2 px-3 rounded d-flex align-items-center justify-content-between">
                <p class="mb-0"><i class="ph-duotone ph-circle text-primary f-12"></i> Ontime Employees</p>
                <h5 class="mb-0 ms-1" id="ontimeEmployee">0</h5>
              </div>
              <div class="bg-body mt-1 py-2 px-3 rounded d-flex align-items-center justify-content-between">
                <p class="mb-0"><i class="ph-duotone ph-circle text-warning f-12"></i> Late Employees</p>
                <h5 class="mb-0 ms-1" id="lateEmployee">0</h5>
              </div>
              <div class="bg-body mt-1 py-2 px-3 rounded d-flex align-items-center justify-content-between">
                <p class="mb-0"><i class="ph-duotone ph-circle text-danger f-12"></i> Absent Employees</p>
                <h5 class="mb-0 ms-1" id="absentEmployee">0</h5>
              </div>
            </div>
          </div>
        </div>
       
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5>Task wise Platform Usages</h5>
        </div>
        <div class="card-body">
          <div id="task-wise-clients-chart"></div>
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-3">Platform Usage Payment Status</h5>
          <div class="dropdown">
            <select class="form-select" id="payment-month-filter">
              <option value="January">January</option>
              <option value="February">February</option>
              <option value="March">March</option>
              <option value="April">April</option>
              <option value="May">May</option>
              <option value="June">June</option>
              <option value="July">July</option>
              <option value="August">August</option>
              <option value="September">September</option>
              <option value="October">October</option>
              <option value="November">November</option>
              <option value="December">December</option>
            </select>
          </div>
        </div>
        <div class="card-body">
          <div class="row g-3 text-center">
            <div class="col-3">
              <p class="mb-0 text-muted">Platform Usage Credit</p>
              <h5 class="mb-0 text-primary" id="total-earning">₹0.00</h5>
            </div>
            <div class="col-3">
              <p class="mb-0 text-muted">Received</p>
              <h5 class="mb-0 text-success" id="received-amount">₹0.00</h5>
            </div>
            <div class="col-3">
              <p class="mb-0 text-muted">Pending</p>
              <h5 class="mb-0 text-danger" id="pending-amount">₹0.00</h5>
            </div>
            <div class="col-3">
              <p class="mb-0 text-muted">Overdue</p>
              <h5 class="mb-0 text-dark" id="overdue-amount">₹0.00</h5>
            </div>
          </div>
          <div id="customer-payment-chart" class="mt-4"></div>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-3">Month wise Onboard Platform Usages Details</h5>
          <div class="dropdown">
            <select class="form-select" id="onboard-month-filter">
				<option value="January">January</option>
				<option value="February">February</option>
				<option value="March">March</option>
				<option value="April">April</option>
				<option value="May">May</option>
				<option value="June">June</option>
				<option value="July">July</option>
				<option value="August">August</option>
				<option value="September">September</option>
				<option value="October">October</option>
				<option value="November">November</option>
				<option value="December">December</option>
            </select>
          </div>
        </div>
        <div class="card-body">
          <div id="monthwise-onboard-chart"></div>
          <div class="d-flex justify-content-center mt-3">
            <div class="mx-3 d-flex align-items-center">
              <span class="me-2 chart-legend" style="background-color: #2196f3;"></span>
              <span>Total Engagement Enabled</span>
              <h5 class="mb-0 ms-2">0</h5>
            </div>
            <div class="mx-3 d-flex align-items-center">
              <span class="me-2 chart-legend" style="background-color: #00e396;"></span>
              <span>Request Engagement Enabled</span>
              <h5 class="mb-0 ms-2">0</h5>
            </div>
            <div class="mx-3 d-flex align-items-center">
              <span class="me-2 chart-legend" style="background-color: #ff4560;"></span>
              <span>Own Engagement Enabled</span>
              <h5 class="mb-0 ms-2">0</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-3">Task Status</h5>
          <div class="dropdown">
            <select class="form-select" id="task-month-filter">
              <option value="January">January</option>
              <option value="February">February</option>
              <option value="March">March</option>
              <option value="April">April</option>
              <option value="May">May</option>
              <option value="June">June</option>
              <option value="July">July</option>
              <option value="August">August</option>
              <option value="September">September</option>
              <option value="October">October</option>
              <option value="November">November</option>
              <option value="December">December</option>
            </select>
          </div>
        </div>
        <div class="card-body">
          <div class="row g-3 text-center mb-4">
            <div class="col-3">
              <p class="mb-0 text-muted">Total Task</p>
              <h3 class="mb-0 text-primary">0</h3>
            </div>
            <div class="col-3">
              <p class="mb-0 text-muted">Completed Task</p>
              <h3 class="mb-0 text-success">0</h3>
            </div>
            <div class="col-3">
              <p class="mb-0 text-muted">Pending Task</p>
              <h3 class="mb-0 text-warning">0</h3>
            </div>
            <div class="col-3">
              <p class="mb-0 text-muted">Overdue Task</p>
              <h3 class="mb-0 text-danger">0</h3>
            </div>
          </div>

          <div class="task-status-legends d-flex align-items-center justify-content-center mb-4">
            <span class="me-3"><i class="fa fa-circle text-success me-2"></i> Completed Task</span>
            <span class="me-3"><i class="fa fa-circle text-warning me-2"></i> Pending Task</span>
            <span><i class="fa fa-circle text-danger me-2"></i> Overdue Task</span>
          </div>

          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Platform Usage Name</th>
                  <th>Task Type</th>
                  <th>Due Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="caTasks">
                 
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('assets/js/ca-chart.js') }}"></script>

<script>
	$('#total_receivales').on('change', function () {
		let month = $(this).val();

		$.ajax({
			url: '/ca/monthly-payments',
			type: 'GET',
			data: { month: month },
			success: function (res) {
				$('#govFees').text('₹' + res.gov);
				$('#totalReceived').text('₹' + res.received);
				$('#totalDue').text('₹' + res.due);
			}
		});
	});
	// trigger on page load
	$('#total_receivales').trigger('change');

	document.addEventListener("DOMContentLoaded", function () {

		const displayInput = document.getElementById("attendance_date_display");
		const hiddenInput  = document.getElementById("attendence_count");
		const calendarBtn  = document.getElementById("calendar-btn");

		const today = new Date();

		const fp = flatpickr(displayInput, {
			dateFormat: "d-m-Y",
			defaultDate: today,
			allowInput: true,
			onChange: function (selectedDates, dateStr) {
				const parts = dateStr.split("-");
				const apiDate = parts[2] + "-" + parts[1] + "-" + parts[0];
				hiddenInput.value = apiDate;
				fetchAttendance(apiDate);
			}
		});

		// Set initial value
		const yyyy = today.getFullYear();
		const mm = String(today.getMonth() + 1).padStart(2, "0");
		const dd = String(today.getDate()).padStart(2, "0");
		hiddenInput.value = `${yyyy}-${mm}-${dd}`;
		displayInput.value = `${dd}-${mm}-${yyyy}`;

		fetchAttendance(hiddenInput.value);

		// Button opens flatpickr
		calendarBtn.addEventListener("click", function () {
			fp.open();
		});

	});


  //-------- Searchable Sidebar Menu Script --------//
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('sidebarMenuSearch');
    const results = document.getElementById('sidebarSearchResults');
    if (!input || !results) return;

    // Collect all sidebar menu links (text + href)
    const links = Array.from(document.querySelectorAll('.pc-navbar a.pc-link'))
        .map(a => ({
            text: a.textContent.trim().replace(/\s+/g, ' '),
            href: a.href
        }))
        .filter(l => l.text);

    const normalize = (str) => (str || '').replace(/\s+/g, ' ').trim().toLowerCase();

    const renderResults = (items) => {
        if (!items.length) {
            results.style.display = 'none';
            results.innerHTML = '';
            return;
        }

        results.innerHTML = items.map(item => `
            <button type="button" class="dropdown-item py-2">${item.text}</button>
        `).join('');

        results.style.display = 'block';

        // Attach click handlers
        Array.from(results.querySelectorAll('.dropdown-item')).forEach((btn, idx) => {
            btn.addEventListener('click', () => {
                window.location.href = items[idx].href;
            });
        });
    };

    input.addEventListener('input', function() {
        const q = normalize(this.value);
        if (!q) {
            renderResults([]);
            return;
        }

        const filtered = links.filter(link => normalize(link.text).includes(q));
        renderResults(filtered);
    });

    // Hide results if user clicks outside
    document.addEventListener('click', function(evt) {
        if (!results.contains(evt.target) && evt.target !== input) {
            results.style.display = 'none';
        }
    });

    input.addEventListener('focus', function() {
        if (results.innerHTML.trim()) {
            results.style.display = 'block';
        }
    });
});

	

</script>

@endsection