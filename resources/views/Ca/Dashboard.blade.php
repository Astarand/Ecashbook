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

  /* Premium dashboard card metrics transitions */
  .metric-box {
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.03);
  }
  .metric-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05) !important;
  }
  .hover-badge {
    transition: all 0.2s ease;
  }
  .hover-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
  }
</style>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div class="pc-content">
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-4 col-sm-12">
          <h2 class="mb-0 ms-2 d-md-block d-none">Dashboard & Overview</h2>
        </div>
        <div class="col-md-8 col-sm-12">
          <div class="d-flex flex-nowrap align-items-center justify-content-md-end justify-content-center flex-md-nowrap flex-wrap gap-2 mt-md-0 mt-2 pe-md-2">
            {{-- Search Options --}}
            <div class="form-search tour-search" style="max-height: 35px;">
              <i class="ph-duotone ph-magnifying-glass icon-search" style="color: #422f90;"></i>
              <input type="search" class="form-control" placeholder="Search.." id="sidebarMenuSearch" autocomplete="off">
              <div id="sidebarSearchResults" class="dropdown-menu w-100 mt-1 shadow" style="display: none; max-height: 240px; overflow-y: auto;"></div>
            </div>
            <button id="start-ca-dashboard-tour" class="btn btn-outline-primary btn-sm flex-shrink-0 d-flex align-items-center" data-bs-toggle="tooltip" title="Take a Tour">
              <i class="ti ti-help me-1"></i> <span class="d-md-inline d-none">Guide Tour</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="row">
    <!-- Left Column -->
    <div class="col-lg-6">
      
      <!-- Monthwise Payment Status -->
      <div class="card statistics-card-1 overflow-hidden mb-4" id="payment-status-card">
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

      <!-- Employee Attendance List -->
      <div class="card mb-4" id="attendance-list-card">
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

      <!-- Month wise Onboard Platform Usages Details -->
      <div class="card mb-4" id="onboard-usages-card">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
          <h5 class="mb-0">Month wise Onboard Platform Usages Details</h5>
          <select class="form-select form-select-sm" id="onboard-month-filter" style="width: 130px;">
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
        <div class="card-body">
          <div id="monthwise-onboard-chart"></div>
          <div class="d-flex justify-content-center flex-wrap gap-2 mt-4">
            <div class="px-3 py-2 rounded-pill d-flex align-items-center hover-badge cursor-pointer" style="background-color: rgba(79, 70, 229, 0.05); border: 1px solid rgba(79, 70, 229, 0.1);">
              <span class="me-2 rounded-circle" style="width: 8px; height: 8px; background-color: #4f46e5; display: inline-block;"></span>
              <span class="text-muted f-12 fw-500">Total Engagement:</span>
              <h5 class="mb-0 ms-2 text-primary fw-600" style="font-size: 14px;">0</h5>
            </div>
            <div class="px-3 py-2 rounded-pill d-flex align-items-center hover-badge cursor-pointer" style="background-color: rgba(13, 148, 136, 0.05); border: 1px solid rgba(13, 148, 136, 0.1);">
              <span class="me-2 rounded-circle" style="width: 8px; height: 8px; background-color: #0d9488; display: inline-block;"></span>
              <span class="text-muted f-12 fw-500">Request Engagement:</span>
              <h5 class="mb-0 ms-2 text-success fw-600" style="font-size: 14px;">0</h5>
            </div>
            <div class="px-3 py-2 rounded-pill d-flex align-items-center hover-badge cursor-pointer" style="background-color: rgba(249, 115, 22, 0.05); border: 1px solid rgba(249, 115, 22, 0.1);">
              <span class="me-2 rounded-circle" style="width: 8px; height: 8px; background-color: #f97316; display: inline-block;"></span>
              <span class="text-muted f-12 fw-500">Own Engagement:</span>
              <h5 class="mb-0 ms-2 text-warning fw-600" style="font-size: 14px;">0</h5>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Right Column -->
    <div class="col-lg-6">

      <!-- Task wise Platform Usages -->
      <div class="card mb-4" id="platform-usages-card">
        <div class="card-header">
          <h5>Task wise Platform Usages</h5>
        </div>
        <div class="card-body">
          <div id="task-wise-clients-chart"></div>
        </div>
      </div>

      <!-- Platform Usage Payment Status -->
      <div class="card mb-4" id="payment-usages-card">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
          <h5 class="mb-0">Platform Usage Payment Status</h5>
          <select class="form-select form-select-sm" id="payment-month-filter" style="width: 130px;">
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
        <div class="card-body">
          <div class="row g-2 text-center align-items-center">
            <!-- Platform Usage Credit -->
            <div class="col-3">
              <div class="py-2 px-1 rounded metric-box" style="background-color: rgba(66, 47, 144, 0.03); border-radius: 8px;">
                <p class="text-muted mb-1" style="font-size: 10px; font-weight: 500; white-space: nowrap;">Platform Usage Credit</p>
                <h5 class="mb-0 text-primary fw-600" style="font-size: 14px;" id="total-earning">₹0.00</h5>
              </div>
            </div>
            <!-- Received -->
            <div class="col-3">
              <div class="py-2 px-1 rounded metric-box" style="background-color: rgba(16, 185, 129, 0.03); border-radius: 8px;">
                <p class="text-muted mb-1" style="font-size: 10px; font-weight: 500; white-space: nowrap;">Received</p>
                <h5 class="mb-0 text-success fw-600" style="font-size: 14px;" id="received-amount">₹0.00</h5>
              </div>
            </div>
            <!-- Pending -->
            <div class="col-3">
              <div class="py-2 px-1 rounded metric-box" style="background-color: rgba(245, 158, 11, 0.03); border-radius: 8px;">
                <p class="text-muted mb-1" style="font-size: 10px; font-weight: 500; white-space: nowrap;">Pending</p>
                <h5 class="mb-0 text-warning fw-600" style="font-size: 14px;" id="pending-amount">₹0.00</h5>
              </div>
            </div>
            <!-- Overdue -->
            <div class="col-3">
              <div class="py-2 px-1 rounded metric-box" style="background-color: rgba(244, 63, 94, 0.03); border-radius: 8px;">
                <p class="text-muted mb-1" style="font-size: 10px; font-weight: 500; white-space: nowrap;">Overdue</p>
                <h5 class="mb-0 text-danger fw-600" style="font-size: 14px;" id="overdue-amount">₹0.00</h5>
              </div>
            </div>
          </div>
          <div id="customer-payment-chart" class="mt-4"></div>
        </div>
      </div>

    </div>

    <!-- Full Width Row at the bottom -->
    <div class="col-md-12">
      <div class="card shadow-sm mb-4" id="task-status-card">
        <div class="card-header d-flex align-items-center justify-content-between py-3 border-0 bg-transparent">
          <h5 class="mb-0 fw-600 text-dark">Task Status</h5>
          <select class="form-select form-select-sm" id="task-month-filter" style="width: 130px;">
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
        <div class="card-body pt-0">
          <!-- Premium Metric Boxes in a single row -->
          <div class="row g-3 text-center mb-4">
            <!-- Total Tasks -->
            <div class="col-6 col-md-3">
              <div class="py-3 px-2 rounded metric-box" style="background-color: rgba(66, 47, 144, 0.03); border-radius: 12px;">
                <p class="text-muted mb-1 fw-500" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Total Tasks</p>
                <h3 class="mb-0 text-primary fw-700" id="task-total-count">0</h3>
              </div>
            </div>
            <!-- Completed Tasks -->
            <div class="col-6 col-md-3">
              <div class="py-3 px-2 rounded metric-box" style="background-color: rgba(16, 185, 129, 0.03); border-radius: 12px;">
                <p class="text-muted mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Completed Tasks</p>
                <h3 class="mb-0 text-success fw-700" id="task-completed-count">0</h3>
              </div>
            </div>
            <!-- Pending Tasks -->
            <div class="col-6 col-md-3">
              <div class="py-3 px-2 rounded metric-box" style="background-color: rgba(245, 158, 11, 0.03); border-radius: 12px;">
                <p class="text-muted mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Pending Tasks</p>
                <h3 class="mb-0 text-warning fw-700" id="task-pending-count">0</h3>
              </div>
            </div>
            <!-- Overdue Tasks -->
            <div class="col-6 col-md-3">
              <div class="py-3 px-2 rounded metric-box" style="background-color: rgba(244, 63, 94, 0.03); border-radius: 12px;">
                <p class="text-muted mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Overdue Tasks</p>
                <h3 class="mb-0 text-danger fw-700" id="task-overdue-count">0</h3>
              </div>
            </div>
          </div>

          <!-- Table with premium header and row styles -->
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light-primary" style="background-color: rgba(66, 47, 144, 0.02); border-radius: 6px;">
                <tr>
                  <th class="py-3 text-muted fw-600" style="font-size: 11px; letter-spacing: 0.5px; border-bottom: none;">PLATFORM USAGE NAME</th>
                  <th class="py-3 text-muted fw-600" style="font-size: 11px; letter-spacing: 0.5px; border-bottom: none;">TASK TYPE</th>
                  <th class="py-3 text-muted fw-600" style="font-size: 11px; letter-spacing: 0.5px; border-bottom: none;">DUE DATE</th>
                  <th class="py-3 text-muted fw-600" style="font-size: 11px; letter-spacing: 0.5px; border-bottom: none;">STATUS</th>
                </tr>
              </thead>
              <tbody id="caTasks">
                 <!-- Populated dynamically via AJAX -->
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

	function startCADashboardTour() {
		if (typeof introJs !== 'function') return;

		introJs().setOptions({
			steps: [
				{
					title: 'CA Admin Dashboard',
					intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-dashboard" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to the Chartered Accountant Control Center. Manage clients, track cashbook transactions, and oversee assigned staff.</p></div>'
				},
				{
					element: '#sidebarMenuSearch',
					title: 'Quick Menu Navigation',
					intro: 'Search and navigate instantly to any sub-section or accounting module using this search bar.'
				},
				{
					element: '#payment-status-card',
					title: 'Monthwise Payment Status',
					intro: 'Review summary of government fees paid, total amount received from clients, and outstanding dues.'
				},
				{
					element: '#attendance-list-card',
					title: 'Employee Attendance Summary',
					intro: 'Monitor daily attendance summary of your staff (ontime, late, absent) at a glance.'
				},
				{
					element: '#platform-usages-card',
					title: 'Platform Usages Overview',
					intro: 'Analyze task categories and company engagement statistics on the E-Cashbook platform.'
				},
				{
					element: '#payment-usages-card',
					title: 'Platform Subscription Ledger',
					intro: 'Overview of platform usage credits, pending balances, and overdue invoices.'
				},
				{
					element: '#onboard-usages-card',
					title: 'Onboard Engagement Analytics',
					intro: 'Analyze monthly trends of client onboardings, split by own engagements vs requested client mappings.'
				},
				{
					element: '#task-status-card',
					title: 'Tasks Status Registry',
					intro: 'Roster of active client compliance tasks, featuring upcoming due dates and real-time status tracking.'
				}
			],
			showBullets: true,
			showProgress: true,
			helperElementPadding: 5,
			exitOnOverlayClick: false,
			doneLabel: 'Done',
			nextLabel: 'Next',
			prevLabel: 'Prev',
			skipLabel: 'Skip'
		}).start();
	}

	$(document).ready(function() {
		$('#start-ca-dashboard-tour').on('click', function(e) {
			e.preventDefault();
			startCADashboardTour();
		});
	});

</script>

@endsection