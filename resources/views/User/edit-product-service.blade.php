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
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="#">Business Operations</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.ProductServiceList') }}">Product & Services</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Product / Service</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-edit-product-service-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Edit Product / Service</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="row align-items-center mb-4">
        <div class="col-md-4">
            <h4 class="mb-0">Edit Product & Services</h4>
        </div>
    </div>
    
    <form action="javascript:void(0);" name="addItemFrm" id="addItemFrm" method="post" enctype="multipart/form-data" >
        <div class="row mb-4">
            <input type="hidden" name="id" id="prodId" value="{{$product->id}}">                          
            @csrf
            <h6 class="mb-0">Select Type</h6>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3 m-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="item_type" id="Product" value="product" <?php echo ($product->item_type=='product')? "checked":"" ?> disabled>
                        <label class="form-check-label" for="Product">Product</label>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3 m-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="item_type" id="Service" value="service" <?php echo ($product->item_type=='service')? "checked":"" ?> disabled>
                        <label class="form-check-label" for="Service">Service</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 product-section">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="item_name" id="item_name" value="{{$product->item_name}}" placeholder="Enter Product Name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">HSN Code<span class="text-danger">*</span></label>
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control"  name="hsn_code" id="hsn_code" value="{{$product->hsn_code}}" placeholder="Enter HSN Number">
                                    <a href="https://cbic-gst.gov.in/gst-goods-services-rates.html" target="_blank" class="btn btn-primary" id="searchHsnBtn">
                                        <i class="ti ti-search align-middle"></i> Search HSN
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="mb-3 col-md-3">
                            <label class="form-label" for="inputEmail4">Opening  Stock <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="opening_stock_bal" id="opening_stock_bal" value="{{$product->opening_stock_bal}}" placeholder="Enter Opening Stock">
                        </div> --}}
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="inputEmail4">GST Rate<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gst_rate_prod" id="gst_rate_prod" value="{{$product->gst_rate}}" placeholder="Enter GST Rate">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="inputEmail4">Goods Description</label>
                            <input type="text" class="form-control" name="goods_desc" id="goods_desc" value="{{$product->goods_desc}}" placeholder="Enter Goods Description">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Base Unit<span class="text-danger">*</span></label>
                            <select class="select form-select" name="base_unit" id="base_unit">
                                <option value="">None</option>
                                <option value="BAG" {{ isset($product->base_unit) && $product->base_unit == 'BAG' ? 'selected' : '' }}>BAGS (Bag)</option>
								<option value="BTL" {{ isset($product->base_unit) && $product->base_unit == 'BTL' ? 'selected' : '' }}>BOTTLES (Bottle)</option>
								<option value="BDL" {{ isset($product->base_unit) && $product->base_unit == 'BDL' ? 'selected' : '' }}>BUNDLES (Bundle)</option>
								<option value="BKL" {{ isset($product->base_unit) && $product->base_unit == 'BKL' ? 'selected' : '' }}>BUCKETS (Bucket)</option>
								<option value="BOU" {{ isset($product->base_unit) && $product->base_unit == 'BOU' ? 'selected' : '' }}>BOUNDS (Bound)</option>
								<option value="BOX" {{ isset($product->base_unit) && $product->base_unit == 'BOX' ? 'selected' : '' }}>BOXES (Box)</option>
								<option value="BUN" {{ isset($product->base_unit) && $product->base_unit == 'BUN' ? 'selected' : '' }}>BUNS (Bun)</option>
								<option value="CAN" {{ isset($product->base_unit) && $product->base_unit == 'CAN' ? 'selected' : '' }}>CANS (Can)</option>
								<option value="CBM" {{ isset($product->base_unit) && $product->base_unit == 'CBM' ? 'selected' : '' }}>CUBIC METERS (Cbm)</option>
								<option value="CCM" {{ isset($product->base_unit) && $product->base_unit == 'CCM' ? 'selected' : '' }}>CUBIC CENTIMETERS (Ccm)</option>
								<option value="CMS" {{ isset($product->base_unit) && $product->base_unit == 'CMS' ? 'selected' : '' }}>CENTIMETERS (Cms)</option>
								<option value="CTN" {{ isset($product->base_unit) && $product->base_unit == 'CTN' ? 'selected' : '' }}>CARTONS (Ctn)</option>
								<option value="DOZ" {{ isset($product->base_unit) && $product->base_unit == 'DOZ' ? 'selected' : '' }}>DOZENS (Dzn)</option>
								<option value="DRM" {{ isset($product->base_unit) && $product->base_unit == 'DRM' ? 'selected' : '' }}>DRUMS (Drm)</option>
								<option value="GGK" {{ isset($product->base_unit) && $product->base_unit == 'GGK' ? 'selected' : '' }}>GUNNY BAGS (Ggk)</option>
								<option value="GMS" {{ isset($product->base_unit) && $product->base_unit == 'GMS' ? 'selected' : '' }}>GRAMS (Gms)</option>
								<option value="GRS" {{ isset($product->base_unit) && $product->base_unit == 'GRS' ? 'selected' : '' }}>GROSS (Grs)</option>
								<option value="KGS" {{ isset($product->base_unit) && $product->base_unit == 'KGS' ? 'selected' : '' }}>KILOGRAMS (Kgs)</option>
								<option value="LTR" {{ isset($product->base_unit) && $product->base_unit == 'LTR' ? 'selected' : '' }}>LITERS (Ltr)</option>
								<option value="MLT" {{ isset($product->base_unit) && $product->base_unit == 'MLT' ? 'selected' : '' }}>MILLILITERS (Mlt)</option>
								<option value="MTR" {{ isset($product->base_unit) && $product->base_unit == 'MTR' ? 'selected' : '' }}>METERS (Mtr)</option>
								<option value="NOS" {{ isset($product->base_unit) && $product->base_unit == 'NOS' ? 'selected' : '' }}>NUMBERS (Nos)</option>
								<option value="PAC" {{ isset($product->base_unit) && $product->base_unit == 'PAC' ? 'selected' : '' }}>PACKS (Pac)</option>
								<option value="PCS" {{ isset($product->base_unit) && $product->base_unit == 'PCS' ? 'selected' : '' }}>PIECES (Pcs)</option>
								<option value="PRS" {{ isset($product->base_unit) && $product->base_unit == 'PRS' ? 'selected' : '' }}>PAIRS (Prs)</option>
								<option value="QTL" {{ isset($product->base_unit) && $product->base_unit == 'QTL' ? 'selected' : '' }}>QUINTALS (Qtl)</option>
								<option value="ROL" {{ isset($product->base_unit) && $product->base_unit == 'ROL' ? 'selected' : '' }}>ROLLS (Rol)</option>
								<option value="SET" {{ isset($product->base_unit) && $product->base_unit == 'SET' ? 'selected' : '' }}>SETS (Set)</option>
								<option value="TON" {{ isset($product->base_unit) && $product->base_unit == 'TON' ? 'selected' : '' }}>TONS (Ton)</option>
								<option value="NA"  {{ isset($product->base_unit) && $product->base_unit == 'NA' ? 'selected' : '' }}>NOT APPLICABLE (Na)</option>
                            </select>                            
                        </div>
                        <div class="mb-3 col-md-12 d-none" id="otherBaseUnitDiv">
                            <label class="form-label" for="inputEmail4">Other Base Unit<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter Other Base Unit" id="otherBaseUnit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Purchase Price<span class="text-danger">*</span></label>
                            <input type="number" required class="form-control" name="purchase_price" id="purchase_price" value="{{$product->purchase_price}}" placeholder="Enter Purchase Price">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Selling Price<span class="text-danger">*</span></label>
                            <input type="number" required class="form-control" name="selling_price" id="selling_price" value="{{$product->selling_price}}" placeholder="Enter Selling Price">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Discount on Selling Price</label>
                            <div class="form-group">
                                <div class="input-group mb-0">
                                    <input type="number" class="form-control has-success" name="disc_sell" id="disc_sell" value="{{$product->disc_sell}}" aria-label="Selling Price" placeholder="Discount" aria-invalid="false" style="width: 80%;">
                                    <select class="form-select has-success" name="disc_sell_type" id="disc_sell_type" aria-label="Select Action" aria-invalid="false" style="width: 20%;">
                                        <option value="percentage" {{ isset($product->disc_sell_type) && $product->disc_sell_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        <option value="amount" {{ isset($product->disc_sell_type) && $product->disc_sell_type == 'amount' ? 'selected' : '' }}>Amount</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="exampleFormControlTextarea1">Product Details</label>
                            <textarea class="form-control" name="prod_desc" id="prod_desc" rows="7" placeholder="Write Product Details">{{$product->prod_desc}}</textarea>
                        </div>
                        
						<div class="mb-3 col-md-6">
							<div class="card-body">
								<label class="form-label">Product Images</label>
								<div class="d-flex flex-wrap gap-2">
									@forelse($productImages as $img)
										<div class="position-relative">
											<img src="{{ asset('storage/product_images/'.$img->image_path) }}"
												 class="img-thumbnail"
												 style="width:120px;height:120px;object-fit:cover;">

											{{-- optional delete button --}}
											<button type="button"
													class="btn btn-sm btn-danger position-absolute top-0 end-0"
													onclick="deleteImage({{ $img->id }})">
												×
											</button>
										</div>
									@empty
										<p class="text-muted">No images uploaded</p>
									@endforelse
								</div>
								<hr>
								{{-- Upload new images --}}
								<input type="file" id="prod_image" name="prod_image[]" multiple class="form-control mt-2">
							</div>
						</div>
						
                        <div class="modal-footer">
                        <div class="message-container"></div>
                        <div id="addEmployeeLoader" class="loader"></div>
                        <button type="submit" class="btn btn-primary d-flex align-items-center">Save Changes <i class="ti ti-arrow-up-right-circle ms-2"></i></button>
                    </div>
                    </div>
                </div>
            </div>

            <!-- end tab content-->
        </div>

        <div class="col-12 service-section" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputEmail4">Service Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="service_name" id="service_name" value="{{$product->service_name}}" placeholder="Enter Product Name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SAC Code<span class="text-danger">*</span></label>
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control"   name="sac_code" id="sac_code" value="{{$product->sac_code}}" placeholder="Enter SAC Number">
                                    <!--<button class="btn btn-primary" type="button"><i class="ti ti-search align-middle"></i> Serarch SAC</button>-->
									<a href="https://cbic-gst.gov.in/gst-goods-services-rates.html" target="_blank" class="btn btn-primary" id="searchSacBtn">
                                        <i class="ti ti-search align-middle"></i> Serarch SAC
                                    </a>
                                </div>
                            </div>
                        </div>
						<div class="mb-3 col-md-3">
                            <label class="form-label" for="inputEmail4">GST Rate<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gst_rate_service" id="gst_rate_service" value="{{$product->gst_rate}}" placeholder="Enter GST Rate">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Govt. Payment<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gov_pay" id="gov_pay" value="{{ $product->gov_pay ?? 0 }}" placeholder="Enter govt. payment">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Service Payment<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ser_pay" id="ser_pay" value="{{ $product->ser_pay ?? 0 }}" placeholder="Enter service payment">
                        </div>
                    </div>
                    <div class="row">
                        <h5 class="text-muted"> Service Details</h5>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputEmail4">Service Price<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  name="ser_selling_price" id="ser_selling_price" value="{{$product->ser_selling_price}}" placeholder="Enter Selling Price">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputEmail4">Discount on service Price</label>
                            <div class="form-group">
                                <div class="input-group mb-0">
                                    <input type="text" class="form-control has-success" name="ser_disc_sell" id="ser_disc_sell" value="{{$product->ser_disc_sell}}" aria-label="Selling Price" placeholder="Discount" aria-invalid="false" style="width: 80%;">
                                    <select class="form-select has-success" name="ser_disc_sell_type" id="ser_disc_sell_type" aria-label="Select Action" aria-invalid="false" style="width: 20%;">
                                        <option value="percentage" {{ isset($product->ser_disc_sell_type) && $product->ser_disc_sell_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        <option value="amount" {{ isset($product->ser_disc_sell_type) && $product->ser_disc_sell_type == 'amount' ? 'selected' : '' }}>Amount</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="exampleFormControlTextarea1">Service Details</label>
                            <textarea class="form-control" name="ser_desc" id="ser_desc" rows="7" placeholder="Write Service Details">{{$product->ser_desc}}</textarea>
                        </div>
                       
						<div class="mb-3 col-md-6">
							<div class="card-body">
								<label class="form-label">Product Images</label>
								<div class="d-flex flex-wrap gap-2">
									@forelse($productImages as $img)
										<div class="position-relative">
											<img src="{{ asset('storage/product_images/'.$img->image_path) }}"
												 class="img-thumbnail"
												 style="width:120px;height:120px;object-fit:cover;">

											{{-- optional delete button --}}
											<button type="button"
													class="btn btn-sm btn-danger position-absolute top-0 end-0"
													onclick="deleteImage({{ $img->id }})">
												×
											</button>
										</div>
									@empty
										<p class="text-muted">No images uploaded</p>
									@endforelse
								</div>
								<hr>
								{{-- Upload new images --}}
								<input type="file" id="prod_image1" name="prod_image[]" multiple class="form-control mt-2">
							</div>
						</div>

                        <div class="modal-footer">
                        <div class="message-container"></div>
                        <div id="addEmployeeLoader" class="loader"></div>
                        <button type="submit" class="btn btn-primary d-flex align-items-center">Save Changes <i class="ti ti-arrow-up-right-circle ms-2"></i></button>
                    </div>
                </div>
            </div>
        
            <!-- end tab content-->
            
        </div>
    </form>
</div>

<span id="image-preview" class="d-flex gap-2 mt-2"></span>

<script>

	function getActiveImageInput() {
		if ($("#prod_image").is(":visible")) {
			return document.getElementById("prod_image");
		}
		if ($("#prod_image1").is(":visible")) {
			return document.getElementById("prod_image1");
		}
		return null;
	}

    document.addEventListener("DOMContentLoaded", function() {
        const productRadio = document.getElementById("Product");
        const serviceRadio = document.getElementById("Service");
        const productSection = document.querySelector(".product-section");
        const serviceSection = document.querySelector(".service-section");
		
		const sellingPrice = document.getElementById("selling_price");
		const serSellingPrice = document.getElementById("ser_selling_price");
        const govPay = document.getElementById("gov_pay");
        const serPay = document.getElementById("ser_pay");
        const toggleSections = () => {
            if (productRadio.checked) {
                productSection.style.display = "block";
                serviceSection.style.display = "none";
				
				sellingPrice.required = true;
				serSellingPrice.required = false;
                govPay.required = false;
                serPay.required = false;
            } else if (serviceRadio.checked) {
                productSection.style.display = "none";
                serviceSection.style.display = "block";
				
				sellingPrice.required = false;
				serSellingPrice.required = true;
                govPay.required = true;
                serPay.required = true;
            }
        };
        productRadio.addEventListener("change", toggleSections);
        serviceRadio.addEventListener("change", toggleSections);
        toggleSections();

        const form = document.getElementById('addItemFrm');
       // alert('hello');
        $("form#addItemFrm").bind("submit", function () {

            //alert('hello');
            //e.preventDefault();
				var prodId = $("#prodId").val();
				let imageInput = getActiveImageInput();
				let files = imageInput ? imageInput.files : [];
				//const totalImages = $("#prod_image")[0].files.length;
				const totalImages = files.length;
                let prod_image = $("#prod_image")[0];
                let fileInput = $("#prod_image")[0];
                //alert(fileInput);
                if (fileInput) {
                    let prod_image = fileInput.files?.[0] || null;
                    console.log(prod_image);
                } else {
                    console.log("Element not found: #prod_image");
                }

               // alert(prod_image);
                let itemData = new FormData();

                let item_type = $("input[name='item_type']:checked").val();
                let item_name = $("input[name='item_name']").val();
                let service_name = $("input[name='service_name']").val();
                
                let base_unit = $("select[name='base_unit']").val();
                
                
                //let sec_unit = $("#baseUnitFrm #sec_unit option:selected").val();
                //let base_unit_other = $("#baseUnitFrm #base_unit_other").val();
                let hsn_code = $("input[name='hsn_code']").val();
                //alert(hsn_code);
                let sac_code = $("input[name='sac_code']").val();
				let gst_rate;
				if(item_type == 'product')
				{
					gst_rate = $("input[name='gst_rate_prod']").val();
				}else{
					gst_rate = $("input[name='gst_rate_service']").val();
				}
                let goods_desc = $("input[name='goods_desc']").val();
                let purchase_price = $("input[name='purchase_price']").val();
                let selling_price = $("input[name='selling_price']").val();
                let ser_selling_price = $("input[name='ser_selling_price']").val();
                let gov_pay = $("input[name='gov_pay']").val();
                let ser_pay = $("input[name='ser_pay']").val();
                //alert(selling_price);
                let disc_sell = $("input[name='disc_sell']").val();
                let ser_disc_sell = $("input[name='ser_disc_sell']").val();
                let disc_sell_type = $("select[name='disc_sell_type']").val();
                let ser_disc_sell_type = $("select[name='ser_disc_sell_type']").val();
                let prod_desc = $("[name='prod_desc']").val();
                let ser_desc = $("[name='ser_desc']").val();
                //alert(prod_desc);
                itemData.append("id", prodId);
                itemData.append("item_type", item_type);
                itemData.append("item_name", item_name);
                itemData.append("service_name", service_name);
                itemData.append("base_unit", base_unit);
                itemData.append("gst_rate", gst_rate);
                itemData.append("goods_desc", goods_desc);
                //itemData.append("sec_unit", sec_unit);
               // itemData.append("base_unit_other", base_unit_other);

               // itemData.append("sac_code", sac_code);
                itemData.append("hsn_code", hsn_code);
                itemData.append("sac_code", sac_code);
                // itemData.append("opening_stock_bal", opening_stock_bal);
                itemData.append("purchase_price", purchase_price);
                itemData.append("selling_price", selling_price);
                itemData.append("ser_selling_price", ser_selling_price);
                itemData.append("gov_pay", gov_pay);
                itemData.append("ser_pay", ser_pay);
                itemData.append("disc_sell", disc_sell);
                itemData.append("ser_disc_sell", ser_disc_sell);
                itemData.append("disc_sell_type", disc_sell_type);
                itemData.append("ser_disc_sell_type", ser_disc_sell_type);
                itemData.append("prod_desc", prod_desc);
                itemData.append("ser_desc", ser_desc);
                /*for (let i = 0; i < totalImages; i++) {
                    itemData.append("prod_image" + i, prod_image);
                }*/
				//let files = document.getElementById("prod_image").files;
				for (let i = 0; i < files.length; i++) {
					itemData.append("prod_image[]", files[i]);
				}
                itemData.append("totalImages", totalImages);
            if (prodId == "") {
                    var suburl = "/save_product";
                } else {
                    var suburl = "/update_product";
                }
                //var formproductsData = $("form#addItemFrm").serialize();
                //let formproductsData = new FormData();
                //console.log($("form#add_vendor_bank").serialize());
                $("#loader").show();
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: suburl,
                    type: "POST",
                    data: itemData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#loader").hide();
                        // ✅ SUCCESS
                        if (response.status === "success") {
                            showToast(response.message, "success");
                            setTimeout(() => window.location.href = response.redirect, 2000);
                            return;
                        }

                        // ✅ VALIDATION ERRORS
                        if (response.status === "validation_error") {
                            let errorMessages = [];
                            $.each(response.errors, function(field, messages) {
                                messages.forEach(function(msg) {
                                    errorMessages.push(msg);
                                });
                            });
                            showToast(errorMessages.join(' '), "error");
                            return;
                        }

                        // ✅ OTHER ERROR
                        showToast(response.message || "Something went wrong", "error");
                    },

                    error: function(xhr) {
                        console.error("AJAX Error:", xhr);

                        // Laravel 422 validation fallback
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            let errorMessages = [];
                            $.each(xhr.responseJSON.errors, function(field, messages) {
                                messages.forEach(function(msg) {
                                    errorMessages.push(msg);
                                });
                            });
                            showToast(errorMessages.join(' '), "error");
                        } else {
                            showToast("Server error. Please try again.", "error");
                        }
                    }
                });
            });
            
    });
	

	$(document).on('change', '#prod_image, #prod_image1', function (e) {
		let preview = document.getElementById('image-preview');
		preview.innerHTML = '';

		[...e.target.files].forEach(file => {
			let reader = new FileReader();
			reader.onload = function () {
				let img = document.createElement('img');
				img.src = reader.result;
				img.style.width = '100px';
				img.classList.add('img-thumbnail', 'me-2');
				preview.appendChild(img);
			};
			reader.readAsDataURL(file);
		});
	});


	function deleteImage(id){
		if(confirm('Delete this image?')){
			$.ajax({
				url:'/product-image/'+id,
				type:'DELETE',
				data:{ _token:'{{ csrf_token() }}' },
				success:function(){
					location.reload();
				}
			});
		}
	}



    function startEditProductServiceTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Edit Product/Service Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Update unit price, tax status, description, and details of this product or service.</p></div>'
                },
                {
                    title: 'Edit Product/Service',
                    intro: 'Update unit price, tax status, description, and details of this product or service.'
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
        $('#start-edit-product-service-tour').on('click', function(e) {
            e.preventDefault();
            startEditProductServiceTour();
        });
    });
</script>
@endsection
