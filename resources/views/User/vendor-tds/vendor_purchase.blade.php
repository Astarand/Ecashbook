@extends('App.Layout')

@section('container')

<div class="pc-content">

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="">Purchase & Procurement</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vendor Report</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Vendor Reports</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3 align-items-end">
                @csrf

                <div class="col-md-5">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control filter-control" required>
                </div>

                <div class="col-md-5">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control filter-control" required>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 filter-control">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Result Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="resultTable">
                    <thead class="table-light">
                        <tr>
                            <th>Vendor Name</th>
                            <th>PAN</th>
                            <th>Total Purchase (₹)</th>
                            <th>TDS Rate (%)</th>
                            <th>TDS Amount (₹)</th>
                            <th>TDS Applicable</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Apply date filter to view data
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- PDF Button -->
            <div class="text-end mt-3">
                <form method="POST" action="/tds/vendor-purchase/pdf" class="d-inline">
                    @csrf
                    <input type="hidden" name="from_date" id="pdf_from">
                    <input type="hidden" name="to_date" id="pdf_to">
                    <button class="btn btn-primary">
                        <i class="fas fa-file-pdf me-1"></i> Download PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- DETAILS MODAL -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
				<h5 class="modal-title">Invoice Details</h5>

				<div class="d-flex gap-2">
					<!-- Excel Download -->
					<button class="btn btn-success btn-sm" id="downloadExcel">
						<i class="fas fa-file-excel"></i> Excel
					</button>
				</div>

				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Item Name</th>
                            <th>Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody id="detailsBody"></tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
	function numberFormat(value, decimals = 2) {
		return Number(value || 0).toLocaleString('en-IN', {
			minimumFractionDigits: decimals,
			maximumFractionDigits: decimals
		});
	}

	$('#filterForm').submit(function(e){
		e.preventDefault();

		let formData = $(this).serialize();
		let from = $('input[name="from_date"]').val();
		let to = $('input[name="to_date"]').val();

		$('#pdf_from').val(from);
		$('#pdf_to').val(to);
		$("#loader").show();
		$.post('/tds/vendor-purchase/filter', formData, function(res){
			$("#loader").hide();
			let html = '';
			if(res.data.length === 0){
				html = `
					<tr>
						<td colspan="6" class="text-center text-muted">
							No records found
						</td>
					</tr>`;
			} else {
				$.each(res.data, function(i,row){
					let detailsData = encodeURIComponent(JSON.stringify(row.details));
					html += `
						<tr>
							<td>${row.vendor_name}</td>
							<td>${row.pan_no}</td>
							<td>${numberFormat(row.fy_purchase)}</td>
							<td>${row.tds_rate}%</td>
							<td>${numberFormat(row.tds_amount)}</td>
							<td>
								<span class="badge ${row.tds_applicable === 'YES' ? 'bg-danger' : 'bg-secondary'}">
									${row.tds_applicable}
								</span>
							</td>
							<td>
								<button class="btn btn-sm btn-primary viewDetailsBtn"
									data-details="${detailsData}">
									<i class="fas fa-eye"></i>
								</button>
							</td>
						</tr>`;
				});
			}

			$('#resultTable tbody').html(html);
		});
	});

	let currentDetails = [];
	$(document).on('click', '.viewDetailsBtn', function(){

		let details = JSON.parse(decodeURIComponent($(this).data('details')));

		currentDetails = details; // ✅ store for download

		let html = '';

		if(details.length === 0){
			html = `<tr><td colspan="3" class="text-center">No Data</td></tr>`;
		} else {
			details.forEach(function(d){
				html += `
					<tr>
						<td>${d.invoice_no}</td>
						<td>${d.item_name}</td>
						<td>${numberFormat(d.amount)}</td>
					</tr>
				`;
			});
		}

		$('#detailsBody').html(html);
		$('#detailsModal').modal('show');
	});
	
	$('#downloadExcel').click(function(){

		if(currentDetails.length === 0){
			alert('No data to export');
			return;
		}

		// Convert JSON to worksheet
		let data = currentDetails.map(d => ({
			"Invoice No": d.invoice_no,
			"Item Name": d.item_name,
			"Amount": d.amount
		}));

		let worksheet = XLSX.utils.json_to_sheet(data);

		// Create workbook
		let workbook = XLSX.utils.book_new();
		XLSX.utils.book_append_sheet(workbook, worksheet, "Vendor Details");

		// Download as .xlsx
		XLSX.writeFile(workbook, "Vendor_Report_Invoice_details.xlsx");
	});
	
	
</script>

@endsection
