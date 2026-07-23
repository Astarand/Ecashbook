@extends('App.Layout')

@section('container')

<div class="pc-content">
  <!-- [ breadcrumb ] start -->
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="d-flex justify-content-between align-items-center w-100">
            <ul class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Inventory</a></li>
              <li class="breadcrumb-item active" aria-current="page">Inventory Management</li>
            </ul>
            <a href="javascript:void(0);" id="start-inv-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
              <i class="ti ti-help-circle f-18"></i> <u>How does this Page works?</u>
            </a>
          </div>
        </div>
        <div class="col-md-4">
          <div class="page-header-title">
            <h2 class="mb-0">Inventory Management</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ breadcrumb ] end -->
  @php
    // Default buss_type = service if not present in request
    $bussType = request()->get('buss_type', 'service');
@endphp
	<form method="GET"
		  action="{{ route('user.Inventory') }}"
		  class="mb-3 d-flex justify-content-end align-items-center inv-nature-filter">

		<div class="form-check form-check-inline">
			<input class="form-check-input"
				   type="radio"
				   name="buss_type"
				   id="service"
				   value="service"
				   onchange="this.form.submit()"
				   {{ $bussType == 'service' ? 'checked' : '' }}>
			<label class="form-check-label" for="service">Service Provider</label>
		</div>

		<div class="form-check form-check-inline">
			<input class="form-check-input"
				   type="radio"
				   name="buss_type"
				   id="trading"
				   value="product"
				   onchange="this.form.submit()"
				   {{ $bussType == 'product' ? 'checked' : '' }}>
			<label class="form-check-label" for="trading">Trading / Reseller</label>
		</div>

		<div class="form-check form-check-inline">
			<input class="form-check-input"
				   type="radio"
				   name="buss_type"
				   id="mixed"
				   value="mixed"
				   onchange="this.form.submit()"
				   {{ $bussType == 'mixed' ? 'checked' : '' }}>
			<label class="form-check-label" for="mixed">Mixed Nature</label>
		</div>

	</form>



    <div class="row g-3 mb-4 inv-summary-cards">
        <!-- 1. Total Inventory Inward Value (Purchase) -->
        <div class="col-md-2-4 col-lg-2-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 50px; height: 50px;">
                        <i class="ph-duotone ph-arrow-down text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-primary" id="totalInwardValue">₹{{ number_format($inventoryInward,2) }}</h4>
                    <p class="mb-0 text-muted small fw-semibold">Inventory Inward</p>
                    <small class="text-muted" style="font-size: 0.7rem;">Purchase Value</small>
                </div>
            </div>
        </div>

        <!-- 2. Total Inventory Outward Value (Sales) -->
        <div class="col-md-2-4 col-lg-2-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 50px; height: 50px;">
                        <i class="ph-duotone ph-arrow-up text-success" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-success" id="totalOutwardValue">₹{{ number_format($inventoryOutward,2) }}</h4>
                    <p class="mb-0 text-muted small fw-semibold">Inventory Outward</p>
                    <small class="text-muted" style="font-size: 0.7rem;">Sales Value</small>
                </div>
            </div>
        </div>

        <!-- 3. Total Purchase Debit Notes Value -->
        <div class="col-md-2-4 col-lg-2-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 50px; height: 50px;">
                        <i class="ph-duotone ph-minus-circle text-danger" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger" id="purchaseDebitValue">₹{{ number_format($purchaseDebit,2) }}</h4>
                    <p class="mb-0 text-muted small fw-semibold">Purchase Debit</p>
                    <small class="text-muted" style="font-size: 0.7rem;">Debit Notes</small>
                </div>
            </div>
        </div>

        <!-- 4. Total Purchase Credit Notes Value -->
        <div class="col-md-2-4 col-lg-2-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 50px; height: 50px;">
                        <i class="ph-duotone ph-plus-circle text-warning" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-warning" id="purchaseCreditValue">₹{{ number_format($purchaseCredit,2) }}</h4>
                    <p class="mb-0 text-muted small fw-semibold">Purchase Credit</p>
                    <small class="text-muted" style="font-size: 0.7rem;">Credit Notes</small>
                </div>
            </div>
        </div>

        <!-- 5. Total Sales Debit Notes Value -->
        <div class="col-md-2-4 col-lg-2-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 50px; height: 50px;">
                        <i class="ph-duotone ph-receipt text-info" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-info" id="salesDebitValue">₹{{ number_format($salesDebit,2) }}</h4>
                    <p class="mb-0 text-muted small fw-semibold">Sales Debit</p>
                    <small class="text-muted" style="font-size: 0.7rem;">Debit Notes</small>
                </div>
            </div>
        </div>

        <!-- Row 2: 5 Boxes -->

        <!-- 6. Total Sales Credit Notes Value -->
        <div class="col-md-2-4 col-lg-2-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 50px; height: 50px; background: rgba(156, 39, 176, 0.1);">
                        <i class="ph-duotone ph-note" style="font-size: 1.5rem; color: #9c27b0;"></i>
                    </div>
                    <h4 class="mb-2 fw-bold" style="color: #9c27b0;" id="salesCreditValue">₹{{ number_format($salesCredit,2) }}</h4>
                    <p class="mb-0 text-muted small fw-semibold">Sales Credit</p>
                    <small class="text-muted" style="font-size: 0.7rem;">Credit Notes</small>
                </div>
            </div>
        </div>

        <!-- 7. Total Direct Inventory Expenses Value -->
        <div class="col-md-2-4 col-lg-2-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 50px; height: 50px;">
                        <i class="ph-duotone ph-coins text-secondary" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-secondary" id="directExpensesValue">₹{{ number_format($directExpenses,2) }}</h4>
                    <p class="mb-0 text-muted small fw-semibold">Direct Expenses</p>
                    <small class="text-muted" style="font-size: 0.7rem;">Inventory Costs</small>
                </div>
            </div>
        </div>

        <!-- 8. Total Inventory Write-Offs / Loss -->
        <div class="col-md-2-4 col-lg-2-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 50px; height: 50px;">
                        <i class="ph-duotone ph-warning text-danger" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger" id="writeOffValue">₹{{ number_format($writeOffs,2) }}</h4>
                    <p class="mb-0 text-muted small fw-semibold">Write-Offs</p>
                    <small class="text-muted" style="font-size: 0.7rem;">Inventory Loss</small>
                </div>
            </div>
        </div>

        <!-- 9. Closing Stock Value -->
        <div class="col-md-2-4 col-lg-2-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 50px; height: 50px; background: rgba(0, 150, 136, 0.1);">
                        <i class="ph-duotone ph-package" style="font-size: 1.5rem; color: #009688;"></i>
                    </div>
                    <h4 class="mb-2 fw-bold" style="color: #009688;" id="closingStockValue">₹{{ number_format($closingStock,2) }}</h4>
                    <p class="mb-0 text-muted small fw-semibold">Closing Stock</p>
                    <small class="text-muted" style="font-size: 0.7rem;">Current Inventory</small>
                </div>
            </div>
        </div>

        <!-- 10. Gross Profit Value -->
        <div class="col-md-2-4 col-lg-2-4">
            <div class="card border-success shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 50px; height: 50px;">
                        <i class="ph-duotone ph-trend-up text-success" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-success" id="grossProfitValue">₹{{ number_format($grossProfit,2) }}</h4>
                    <p class="mb-0 text-success small fw-bold">Gross Profit</p>
                    <small class="text-muted" style="font-size: 0.7rem;">Total Profit</small>
                </div>
            </div>
        </div>
    </div>


  <!-- [ Main Content ] start -->
  <div class=" row">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
      <div class="card card-body table-card inv-table-card">
        <div class="table-responsive">
          <table class="table tbl-product my-3" id="pc-dt-simple">
            <thead>
              <tr>
                <th class="text-end">#</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Listing Date</th>
                <th>HSN / SAC Code</th>
                <th>Current Stock</th>
                <th>Purchase Price</th>
                <th>Selling Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              @foreach ($items as $item)
              <tr>
                <td class="text-end"><?php echo $i++; ?></td>
                <td><span class="text-muted text-hover-primary">{{ $item->prodId }}</span></td>
                <td><span class="text-muted text-hover-primary">@if($item->item_type =='service')
                    {{ $item->service_name }}
                    @else
                    {{ $item->item_name }}
                    @endif</span></td>
                <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                <td><span class="text-muted text-hover-primary">@if($item->item_type =='service')
                    {{$item->sac_code}}
                    @else
                    {{ $item->hsn_code }}
                    @endif</span></td>
                <td><span class="text-muted text-hover-primary">
					@if($item->item_type =='service') 0 @else {{$item->current_stock}} @endif
					</span>
				</td>
                <td><span class="text-muted text-hover-primary">₹@if($item->item_type =='service')
                    0
                    @else
                    {{$item->purchase_price}}
                    @endif</span></td>
                <td><span class="text-muted text-hover-primary">₹@if($item->item_type =='service')
                    {{$item->ser_selling_price}}
                    @else
                    {{$item->selling_price}}
                    @endif</span></td>
                <td>
                  <span><i class="ti ti-dots-vertical f-20"></i></span>
                  <div class="prod-action-links">
                    <ul class="list-inline me-auto mb-0">
                      <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="History">
                        <a href="javascript:void(0);" class="avtar avtar-xs btn-link-warning btn-pc-default view-history" data-id="{{$item->id}}" data-code="{{$item->prodId}}" data-name="{{ $item->item_type == 'service' ? ($item->service_name ?? 'No Service Name') : ($item->item_name ?? 'No Product Name') }}" data-bs-toggle="modal" data-bs-target="#historyModal">
                          <i class="ti ti-eye f-18"></i>
                        </a>
                      </li>

                      @if($req_type != 1)
                      <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Stock In">
                        <a href="{{ route('user.CreatePurchaseInvoices') }}" class="avtar avtar-xs btn-link-success btn-pc-default item-row">
                          <i class="ti ti-circle-plus f-18"></i>
                        </a>
                      </li>
                      <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Stock Out">
                        <a href="{{ route('user.CreateSalesInvoices') }}" class="avtar avtar-xs btn-link-danger btn-pc-default item-row">
                          <i class="ti ti-circle-minus f-18"></i>
                        </a>
                      </li>
                      @endif
                    </ul>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
			<div class="d-flex justify-content-end mt-2">
				<ul class="pagination pagination-sm mb-0">
					{{-- Previous Page Link --}}
					@if ($items->onFirstPage())
						<li class="page-item disabled"><span class="page-link">&laquo;</span></li>
					@else
						<li class="page-item"><a class="page-link" href="{{ $items->previousPageUrl() }}" rel="prev">&laquo;</a></li>
					@endif
					{{-- Next Page Link --}}
					@if ($items->hasMorePages())
						<li class="page-item"><a class="page-link" href="{{ $items->nextPageUrl() }}" rel="next">&raquo;</a></li>
					@else
						<li class="page-item disabled"><span class="page-link">&raquo;</span></li>
					@endif
				</ul>
			</div>


        </div>
      </div>
    </div>
    <!-- [ sample-page ] end -->
  </div>
  <!-- [ Main Content ] end -->
</div>


<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyModalLabel">Inventory History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <strong id="modalItemName">Item Name</strong><br>
          <span>Item Code: <span id="modalItemCode">Code</span></span>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead class="table-light">
              <tr>
                <th>Date</th>
                <th>Units</th>
                <th>Quantity</th>
                <th>Type</th>
              </tr>
            </thead>
            <tbody id="historyTableBody">

            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Stock In Modal -->
<div class="modal fade" id="stockInModal" tabindex="-1" aria-labelledby="stockInModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="stockInModalLabel">Add Stock In</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="javascript:void(0);" method="POST" name="addInvstock" id="addInvstock" enctype="multipart/form-data">
          <input type="hidden" name="prodId" id="prodId" value="">
          @csrf
          <!-- Quantity and Units -->
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="itemDate" class="form-label">Date <span class="text-danger">*</span></label>
              <input type="datetime-local" class="form-control" id="created_at" name="created_at">
            </div>
            <div class="col-md-6">
              <label for="itemName" class="form-label">Product Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="prod_name" name="prod_name" readonly>
            </div>
            <div class="col-md-6">
              <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="quantity" name="quantity" value="0" min="0">
            </div>
            <div class="col-md-6">
              <label for="units" class="form-label">Units <span class="text-danger">*</span></label>
              <select class="form-select" id="units" name="units">
                <option value="">None</option>
                <option value="bags">BAGS (Bag)</option>
                <option value="bottle">BOTTLES (Bottle)</option>
                <option value="box">BOXS (Box)</option>
                <option value="can">CANS (Can)</option>
                <option value="ctn">CARTONS (Ctn)</option>
                <option value="dzn">DOZENS (Dzn)</option>
                <option value="grm">GRAMMES (Gm)</option>
                <option value="kg">KILOGRAMMES (Kg)</option>
                <option value="ltr">LITER (Ltr)</option>
                <option value="mtr">METERS (Mtr)</option>
                <option value="ml">MILILITER (Ml)</option>
                <option value="nos">NUMBERS (Nos)</option>
                <option value="pack">PACKS (Pac)</option>
                <option value="pair">PAIRS (Prs)</option>
                <option value="pcs">PIECES (Pcs)</option>
                <option value="qtl">QUINTAL (Qtl)</option>
              </select>
            </div>
          </div>

          <!-- Notes -->
          <div class="mb-3">
            <label for="notes" class="form-label">Add for Reason<span class="text-danger">*</span></label>
            <select class="form-select" id="reason" name="reason">
              <option value="Pieces" selected>Sale</option>
              <option value="Kilograms">Purchase</option>
              <option value="Liters">Returns</option>
              <option value="Liters">Damage</option>
              <option value="Liters">Expired</option>
              <option value="Liters">Other</option>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Add Quantity</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Stock Out Modal -->
<div class="modal fade" id="stockOutModal" tabindex="-1" aria-labelledby="stockOutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="stockInModalLabel">Add Stock Out</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="javascript:void(0);" method="POST" name="removeinvstock" id="removeinvstock" enctype="multipart/form-data">
          <input type="hidden" name="prodId" id="prodId" value="">
          @csrf
          <!-- Quantity and Units -->
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="itemDate" class="form-label">Date <span class="text-danger">*</span></label>
              <input type="datetime-local" class="form-control" id="recreated_at" name="recreated_at">
            </div>
            <div class="col-md-6">
              <label for="itemName" class="form-label">Product Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="service_name" name="service_name" readonly>
            </div>
            <div class="col-md-6">
              <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="requantity" name="requantity" value="0" min="0">
            </div>
            <div class="col-md-6">
              <label for="units" class="form-label">Units <span class="text-danger">*</span></label>
              <select class="form-select" id="reunits" name="reunits">
                <option value="">None</option>
                <option value="bags">BAGS (Bag)</option>
                <option value="bottle">BOTTLES (Bottle)</option>
                <option value="box">BOXS (Box)</option>
                <option value="can">CANS (Can)</option>
                <option value="ctn">CARTONS (Ctn)</option>
                <option value="dzn">DOZENS (Dzn)</option>
                <option value="grm">GRAMMES (Gm)</option>
                <option value="kg">KILOGRAMMES (Kg)</option>
                <option value="ltr">LITER (Ltr)</option>
                <option value="mtr">METERS (Mtr)</option>
                <option value="ml">MILILITER (Ml)</option>
                <option value="nos">NUMBERS (Nos)</option>
                <option value="pack">PACKS (Pac)</option>
                <option value="pair">PAIRS (Prs)</option>
                <option value="pcs">PIECES (Pcs)</option>
                <option value="qtl">QUINTAL (Qtl)</option>
              </select>
            </div>
          </div>

          <!-- Notes -->
          <div class="mb-3">
            <label for="notes" class="form-label">Reason for Remove<span class="text-danger">*</span></label>
            <select class="form-select" id="servicereason" name="servicereason">
              <option value="Sale" selected>Sale</option>
              <option value="Purchase">Purchase</option>
              <option value="Returns">Returns</option>
              <option value="Damage">Damage</option>
              <option value="Expired">Expired</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Remove Quantity</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    //stock in
    //const form = document.getElementById('addInvstock');
    // alert('hello');
    $(document).on("click", ".item-row", function() {
      var rowId = $(this).data("id");
      var rowName = $(this).data("name") || "No Name Available";
      var itemType = $(this).data("item-type"); // Check if it's a service or product

      console.log("rowId:", rowId);
      console.log("rowName:", rowName);
      console.log("itemType:", itemType);

      $("form").find("#prodId").val(rowId);

      if (itemType === "product") {
        $("#addInvstock input[name='prod_name']").val(rowName); // Set product name
        $("#addInvstock input[name='service_name']").val(""); // Clear service field
        $("#removeinvstock input[name='service_name']").val(rowName); // Set service name
        $("#removeinvstock input[name='prod_name']").val(""); // Clear product field
      } else if (itemType === "service") {
        $("#addInvstock input[name='prod_name']").val(rowName); // Set product name
        $("#addInvstock input[name='service_name']").val(""); // Clear service field
        $("#removeinvstock input[name='service_name']").val(rowName); // Set service name
        $("#removeinvstock input[name='prod_name']").val(""); // Clear product field
      }

      // alert("Selected Item: " + rowName + " (Type: " + itemType + ")");
    });


    // Form submit event
    $("form#addInvstock").on("submit", function(event) {
      event.preventDefault();
      var prodId = $("#prodId").val();

      let invData = new FormData();
      let created_at = $("input[name='created_at']").val();
      let prod_name = $("input[name='prod_name']").val();
      let quantity = $("input[name='quantity']").val();
      let units = $("select[name='units']").val();
      let reason = $("select[name='reason']").val();

      invData.append("prodId", prodId); // Append row ID
      invData.append("created_at", created_at);
      invData.append("prod_name", prod_name);
      invData.append("quantity", quantity);
      invData.append("units", units);
      invData.append("reason", reason);

      var suburl = "/save_stock"; // URL for submission

      $.ajax({
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: suburl,
        type: "POST",
        data: invData,
        contentType: false,
        processData: false,
        success: function(response) {
          if (response.class == "succ") {

            showToast("Stock In Add successfully", "success");
            setTimeout(() => window.location.href = response.redirect, 2000); // Reload after 2s
          } else {
            $.each(response, function(idx, obj) {
              showToast("Error: Stock In Update", "error");
              // $("#addInvstock .message-container").html(
              //     '<div class="err">' + obj + "</div>"
              // );
            });
          }
        },
      });
    });

    //stock out
    //const form = document.getElementById('removeinvstock');
    //  alert('hello');
    $("form#removeinvstock").bind("submit", function() {
      //alert('hello');
      //e.preventDefault();
      var prodId = $("#prodId").val();

      let invreData = new FormData();


      let recreated_at = $("input[name='recreated_at']").val();
      //alert(recreated_at);
      let service_name = $("input[name='service_name']").val();
      //alert(service_name);
      let requantity = $("input[name='requantity']").val();

      let reunits = $("select[name='reunits']").val();
      //alert(reunits);
      let servicereason = $("select[name='servicereason']").val();

      invreData.append("prodId", prodId);
      invreData.append("recreated_at", recreated_at);
      invreData.append("service_name", service_name);
      invreData.append("requantity", requantity);
      invreData.append("reunits", reunits);
      invreData.append("servicereason", servicereason);


      var suburl = "/save_removestock";


      $.ajax({
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: suburl,
        type: "POST",
        data: invreData,
        contentType: false,
        processData: false,
        success: function(response) {
          //console.log(response);
          // $("#addvendoromerLoader").hide();
          if (response.class == "succ") {
            //console.log(response);
            //$("#add_vendor_bank .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');

            showToast("Stock Out Add Successfully", "success");
            setTimeout(() => window.location.href = response.redirect, 2000); // Reload after 2s
          } else {
            $.each(response, function(idx, obj) {
              showToast("Error: Stock Out Add", "error");
              // $("#removeinvstock .message-container").html(
              //     '<div class="err">' + obj + "</div>"
              // );
            });
          }
        },
      });
    });

    //inventory History
    $(".view-history").click(function() {
      var productId = $(this).data("id"); // Get product ID
      var itemName = $(this).data("name"); // Get item name
      var itemCode = $(this).data("code"); // Get item code

      // Update modal values dynamically
      $("#modalItemName").text(itemName);
      $("#modalItemCode").text(itemCode);
      $("#historyModal").modal("show"); // Show modal

      // Show loading state before fetching data
      $("#historyTableBody").html('<tr><td colspan="4">Loading...</td></tr>');

      $.ajax({
        url: "/getinventoryhistory",
        type: "GET",
        data: {
          id: productId
        },
        success: function(response) {
          var rows = "";

          if (response.length > 0) {
            $.each(response, function(index, record) {
              var adjustmentClass = record.adjustment > 0 ? "text-success" : "text-danger";
              rows += `<tr>
                                <td>${record.date}</td>
                                <td>${record.units}</td>
                                <td class="${adjustmentClass}">${record.quantity}</td>
                                <td>${record.type}</td>
                            </tr>`;
            });
          } else {
            rows = '<tr><td colspan="4">No history found.</td></tr>';
          }

          $("#historyTableBody").html(rows); // Replace old data with new data
        },
        error: function() {
          $("#historyTableBody").html('<tr><td colspan="4" class="text-danger">Error loading data.</td></tr>');
        }
      });
    });

    $('#start-inv-tour').on('click', function(e) {
      e.preventDefault();
      startInvTour();
    });

  });

  function startInvTour() {
    if (typeof introJs !== 'function') return;
    introJs().setOptions({
      steps: [
        {
          title: 'Inventory Guide',
          intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-package" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to Inventory Management. Track stock values, purchases, sales, write-offs, and profits.</p></div>'
        },
        {
          element: '.inv-nature-filter',
          title: 'Business Nature Filter',
          intro: 'Filter inventory records by Service Provider, Trading/Reseller, or Mixed nature.'
        },
        {
          element: '.inv-summary-cards',
          title: 'Metrics & Values',
          intro: 'Instantly view total Inward (Purchase), Outward (Sales), Debit/Credit Notes, Write-offs, Closing Stock, and Gross Profit values.'
        },
        {
          element: '.inv-table-card',
          title: 'Inventory List Table',
          intro: 'Manage your product database. Check current stocks, purchase and selling prices, and view stock ledger history.'
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
</script>
<style>
/* Custom 5-column layout for equal width */
@media (min-width: 768px) {
    .col-md-2-4 {
        flex: 0 0 20%;
        max-width: 20%;
    }
}

@media (min-width: 992px) {
    .col-lg-2-4 {
        flex: 0 0 20%;
        max-width: 20%;
    }
}

/* Card hover effect */
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}
</style>
@endsection
