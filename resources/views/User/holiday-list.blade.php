@extends('App.Layout')
@section('container')

<!-- Phosphor Icons -->
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                        <li class="breadcrumb-item"><a href="#">HR, Payroll & Attendance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Holiday Calendar</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Holiday Calendar</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="ph-duotone ph-calendar me-2"></i>Company Holidays</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#holidayModal">
                        <i class="ti ti-plus"></i> Add Holiday
                    </button>
                </div>

                <!-- Holiday Statistics -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="ph-duotone ph-calendar-check" style="font-size: 2.5rem;"></i>
                                    <h4 class="mb-1 mt-2 fw-bold" id="totalHolidays">12</h4>
                                    <small>Total Holidays</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="ph-duotone ph-flag" style="font-size: 2.5rem;"></i>
                                    <h4 class="mb-1 mt-2 fw-bold" id="nationalHolidays">5</h4>
                                    <small>National Holidays</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="ph-duotone ph-gift" style="font-size: 2.5rem;"></i>
                                    <h4 class="mb-1 mt-2 fw-bold" id="festivalHolidays">4</h4>
                                    <small>Festival Holidays</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="ph-duotone ph-buildings" style="font-size: 2.5rem;"></i>
                                    <h4 class="mb-1 mt-2 fw-bold" id="companyHolidays">3</h4>
                                    <small>Company Holidays</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Holiday Modal -->
                <div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="holidayModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="holidayModalLabel">Add Holiday</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="holidayForm">
                                <input type="hidden" id="holidayId" name="holidayId" value="">
                                <div class="modal-body">
                                    <!-- Holiday Name -->
                                    <div class="mb-3">
                                        <label for="holidayName" class="form-label">Holiday Name</label>
                                        <input type="text" id="holidayName" name="holidayName" class="form-control" placeholder="Enter holiday name" required>
                                    </div>

                                    <!-- Date -->
                                    <div class="mb-3">
                                        <label for="holidayDate" class="form-label">Date</label>
                                        <input type="date" id="holidayDate" name="holidayDate" class="form-control" required>
                                    </div>

                                    <!-- Type -->
                                    <div class="mb-3">
                                        <label for="holidayType" class="form-label">Type</label>
                                        <select id="holidayType" name="holidayType" class="form-select" required>
                                            <option value="">Choose a type…</option>
                                            <option value="National">National</option>
                                            <option value="Festival">Festival</option>
                                            <option value="Regional">Regional</option>
                                            <option value="Company">Company</option>
                                            <option value="Optional">Optional</option>
                                        </select>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-3">
                                        <label for="holidayDescription" class="form-label">Description</label>
                                        <textarea id="holidayDescription" name="holidayDescription" class="form-control" rows="3" placeholder="Optional notes"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary" id="holidaySubmitBtn">Save Holiday</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Holiday List -->
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0"><i class="ti ti-list me-2"></i>Holiday List</h6>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="filterType" style="width: auto;">
                                <option value="">All Types</option>
                                <option value="National">National</option>
                                <option value="Festival">Festival</option>
                                <option value="Regional">Regional</option>
                                <option value="Company">Company</option>
                                <option value="Optional">Optional</option>
                            </select>
                            <select class="form-select form-select-sm" id="filterYear" style="width: auto;">
                                <option value="">All Years</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                            </select>
                        </div>
                    </div>

                    <!-- Holiday Table -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="holidaysTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">Sr.</th>
                                    <th width="25%">Holiday Name</th>
                                    <th width="15%">Date</th>
                                    <th width="15%">Type</th>
                                    <th width="20%">Description</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="holidayTableBody">
                                <tr data-type="National" data-year="2025">
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-primary me-2"></i>
                                            <strong>Republic Day</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-primary text-primary">26-01-2025</span></td>
                                    <td><span class="badge bg-primary">National</span></td>
                                    <td>India's Republic Day celebration</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(1)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(1)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-type="Festival" data-year="2025">
                                    <td>2</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-warning me-2"></i>
                                            <strong>Holi</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-warning text-warning">14-03-2025</span></td>
                                    <td><span class="badge bg-warning">Festival</span></td>
                                    <td>Festival of Colors</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(2)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(2)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-type="National" data-year="2025">
                                    <td>3</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-primary me-2"></i>
                                            <strong>Independence Day</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-primary text-primary">15-08-2025</span></td>
                                    <td><span class="badge bg-primary">National</span></td>
                                    <td>India's Independence Day</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(3)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(3)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-type="Festival" data-year="2025">
                                    <td>4</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-warning me-2"></i>
                                            <strong>Diwali</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-warning text-warning">01-11-2025</span></td>
                                    <td><span class="badge bg-warning">Festival</span></td>
                                    <td>Festival of Lights</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(4)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(4)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-type="National" data-year="2025">
                                    <td>5</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-primary me-2"></i>
                                            <strong>Gandhi Jayanti</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-primary text-primary">02-10-2025</span></td>
                                    <td><span class="badge bg-primary">National</span></td>
                                    <td>Mahatma Gandhi's Birthday</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(5)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(5)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-type="Company" data-year="2025">
                                    <td>6</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-info me-2"></i>
                                            <strong>Company Foundation Day</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-info text-info">15-05-2025</span></td>
                                    <td><span class="badge bg-info">Company</span></td>
                                    <td>Annual celebration of company founding</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(6)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(6)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-type="Festival" data-year="2025">
                                    <td>7</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-warning me-2"></i>
                                            <strong>Christmas</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-warning text-warning">25-12-2025</span></td>
                                    <td><span class="badge bg-warning">Festival</span></td>
                                    <td>Christmas celebration</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(7)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(7)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-type="National" data-year="2025">
                                    <td>8</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-primary me-2"></i>
                                            <strong>May Day</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-primary text-primary">01-05-2025</span></td>
                                    <td><span class="badge bg-primary">National</span></td>
                                    <td>International Workers' Day</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(8)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(8)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-type="Regional" data-year="2025">
                                    <td>9</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-success me-2"></i>
                                            <strong>Durga Puja</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-success text-success">10-10-2025</span></td>
                                    <td><span class="badge bg-success">Regional</span></td>
                                    <td>Bengali festival</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(9)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(9)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-type="Company" data-year="2025">
                                    <td>10</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-info me-2"></i>
                                            <strong>Annual Day</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-info text-info">20-12-2025</span></td>
                                    <td><span class="badge bg-info">Company</span></td>
                                    <td>Company annual celebration</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(10)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(10)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-type="Festival" data-year="2025">
                                    <td>11</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-warning me-2"></i>
                                            <strong>Eid</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-warning text-warning">31-03-2025</span></td>
                                    <td><span class="badge bg-warning">Festival</span></td>
                                    <td>Islamic festival</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(11)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(11)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-type="National" data-year="2025">
                                    <td>12</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-calendar-event text-primary me-2"></i>
                                            <strong>Dr. Ambedkar Jayanti</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-primary text-primary">14-04-2025</span></td>
                                    <td><span class="badge bg-primary">National</span></td>
                                    <td>Birth anniversary of Dr. B.R. Ambedkar</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editHoliday(12)" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteHoliday(12)" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State (Initially Hidden) -->
                    <div id="emptyState" class="text-center py-5" style="display: none;">
                        <div class="mb-3">
                            <i class="ti ti-calendar-off" style="font-size: 4rem; color: #ccc;"></i>
                        </div>
                        <h5 class="text-muted">No Holidays Found</h5>
                        <p class="text-muted mb-3">No holidays match your filter criteria.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterType = document.getElementById('filterType');
    const filterYear = document.getElementById('filterYear');
    const tableBody = document.getElementById('holidayTableBody');
    const emptyState = document.getElementById('emptyState');
    const holidayForm = document.getElementById('holidayForm');
    const holidayModal = new bootstrap.Modal(document.getElementById('holidayModal'));

    // Filter functionality
    function filterTable() {
        const typeValue = filterType.value;
        const yearValue = filterYear.value;
        const rows = tableBody.getElementsByTagName('tr');
        let visibleCount = 0;

        Array.from(rows).forEach((row, index) => {
            const rowType = row.getAttribute('data-type');
            const rowYear = row.getAttribute('data-year');
            
            const typeMatch = !typeValue || rowType === typeValue;
            const yearMatch = !yearValue || rowYear === yearValue;
            
            if (typeMatch && yearMatch) {
                row.style.display = '';
                visibleCount++;
                // Update serial number
                row.cells[0].textContent = visibleCount;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide empty state
        if (visibleCount === 0) {
            emptyState.style.display = 'block';
            document.querySelector('.table-responsive').style.display = 'none';
        } else {
            emptyState.style.display = 'none';
            document.querySelector('.table-responsive').style.display = 'block';
        }

        // Update statistics
        updateStatistics();
    }

    // Update statistics based on visible rows
    function updateStatistics() {
        const rows = Array.from(tableBody.getElementsByTagName('tr')).filter(row => row.style.display !== 'none');
        
        const national = rows.filter(row => row.getAttribute('data-type') === 'National').length;
        const festival = rows.filter(row => row.getAttribute('data-type') === 'Festival').length;
        const company = rows.filter(row => row.getAttribute('data-type') === 'Company').length;
        
        document.getElementById('totalHolidays').textContent = rows.length;
        document.getElementById('nationalHolidays').textContent = national;
        document.getElementById('festivalHolidays').textContent = festival;
        document.getElementById('companyHolidays').textContent = company;
    }

    // Attach filter event listeners
    filterType.addEventListener('change', filterTable);
    filterYear.addEventListener('change', filterTable);

    // Form submission
    holidayForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const holidayId = document.getElementById('holidayId').value;
        const action = holidayId ? 'updated' : 'added';
        
        Swal.fire({
            title: 'Success!',
            text: `Holiday ${action} successfully!`,
            icon: 'success',
            confirmButtonColor: '#3085d6'
        });
        
        holidayModal.hide();
        holidayForm.reset();
        document.getElementById('holidayId').value = '';
        document.getElementById('holidayModalLabel').textContent = 'Add Holiday';
    });

    // Reset form when modal is closed
    document.getElementById('holidayModal').addEventListener('hidden.bs.modal', function() {
        holidayForm.reset();
        document.getElementById('holidayId').value = '';
        document.getElementById('holidayModalLabel').textContent = 'Add Holiday';
    });
});

// Edit Holiday Function
function editHoliday(id) {
    // In real application, fetch data from server
    const holidays = {
        1: {name: 'Republic Day', date: '2025-01-26', type: 'National', description: "India's Republic Day celebration"},
        2: {name: 'Holi', date: '2025-03-14', type: 'Festival', description: 'Festival of Colors'},
        3: {name: 'Independence Day', date: '2025-08-15', type: 'National', description: "India's Independence Day"},
        4: {name: 'Diwali', date: '2025-11-01', type: 'Festival', description: 'Festival of Lights'},
        5: {name: 'Gandhi Jayanti', date: '2025-10-02', type: 'National', description: "Mahatma Gandhi's Birthday"},
        6: {name: 'Company Foundation Day', date: '2025-05-15', type: 'Company', description: 'Annual celebration of company founding'},
        7: {name: 'Christmas', date: '2025-12-25', type: 'Festival', description: 'Christmas celebration'},
        8: {name: 'May Day', date: '2025-05-01', type: 'National', description: "International Workers' Day"},
        9: {name: 'Durga Puja', date: '2025-10-10', type: 'Regional', description: 'Bengali festival'},
        10: {name: 'Annual Day', date: '2025-12-20', type: 'Company', description: 'Company annual celebration'},
        11: {name: 'Eid', date: '2025-03-31', type: 'Festival', description: 'Islamic festival'},
        12: {name: 'Dr. Ambedkar Jayanti', date: '2025-04-14', type: 'National', description: 'Birth anniversary of Dr. B.R. Ambedkar'}
    };

    const holiday = holidays[id];
    
    if (holiday) {
        document.getElementById('holidayId').value = id;
        document.getElementById('holidayName').value = holiday.name;
        document.getElementById('holidayDate').value = holiday.date;
        document.getElementById('holidayType').value = holiday.type;
        document.getElementById('holidayDescription').value = holiday.description;
        document.getElementById('holidayModalLabel').textContent = 'Edit Holiday';
        
        const modal = new bootstrap.Modal(document.getElementById('holidayModal'));
        modal.show();
    }
}

// Delete Holiday Function
function deleteHoliday(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleted!',
                text: 'Holiday has been deleted successfully.',
                icon: 'success',
                confirmButtonColor: '#3085d6'
            });
            // In real application, make AJAX call to delete from server
        }
    });
}
</script>
@endsection
