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
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="#">Business Operations</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.ProductServiceList') }}">Product & Services</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Product / Service</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Product / Service</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="row align-items-center mb-4">
        <div class="col-md-4">
            <h4 class="mb-0">View Product & Services</h4>
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
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="inputEmail4">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="item_name" id="item_name" value="{{$product->item_name}}" placeholder="Enter Product Name">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">HSN Code<span class="text-danger">*</span></label>
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control"  name="hsn_code" id="hsn_code" value="{{$product->hsn_code}}" placeholder="Enter HSN Number">
                                    <button class="btn btn-primary" type="button"><i class="ti ti-search align-middle"></i> Serarch HSN</button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="inputEmail4">Opening Stock<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="opening_stock_bal" id="opening_stock_bal" value="{{$product->opening_stock_bal}}" placeholder="Enter Opening Stock">
                        </div>
                        <div class="mb-3 col-md-3">
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
                            <input type="text" class="form-control" name="purchase_price" id="purchase_price" value="{{$product->purchase_price}}" placeholder="Enter Purchase Price">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Selling Price<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="selling_price" id="selling_price" value="{{$product->selling_price}}" placeholder="Enter Selling Price">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Discount on selling Price<span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="input-group mb-0">
                                    <input type="text" class="form-control has-success" name="disc_sell" id="disc_sell" value="{{$product->disc_sell}}" aria-label="Selling Price" placeholder="Discount" aria-invalid="false" style="width: 80%;">
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
                        <a href="{{ url('/product-service-list') }}" class="btn btn-danger cancel me-2">Close</a>

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
                                    <input type="text" class="form-control"   name="sac_code" id="sac_code" value="{{$product->sac_code}}" placeholder="Enter HSN Number">
                                    <button class="btn btn-primary" type="button"><i class="ti ti-search align-middle"></i> Serarch SAC</button>
                                </div>
                            </div>
                        </div>
						<div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">GST Rate<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gst_rate_service" id="gst_rate_service" value="{{$product->gst_rate}}" placeholder="Enter GST Rate">
                        </div>
						<div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Govt. Payment<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gov_pay" id="gov_pay" value="{{$product->gov_pay}}" placeholder="Enter govt. payment">
                        </div>
						<div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Service Payment<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ser_pay" id="ser_pay" value="{{$product->ser_pay}}" placeholder="Enter service payment">
                        </div>
                    </div>
                    <div class="row">
                        <h5 class="text-muted"> Service Details</h5>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputEmail4">Selling Price<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  name="ser_selling_price" id="ser_selling_price" value="{{$product->ser_selling_price}}" placeholder="Enter Selling Price">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputEmail4">Discount on selling Price<span class="text-danger">*</span></label>
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
                            <label class="form-label" for="exampleFormControlTextarea1">Product Details</label>
                            <textarea class="form-control" name="ser_desc" id="ser_desc" rows="7" placeholder="Write Product Details">{{$product->ser_desc}}</textarea>
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
                        <a href="{{ url('/product-service-list') }}" class="btn btn-danger cancel me-2">Close</a>
                        </div>
                </div>
            </div>
        
            <!-- end tab content-->
            </form>
        </div>
    </form>
</div>

<span id="image-preview" class="d-flex gap-2 mt-2"></span>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const productRadio = document.getElementById("Product");
        const serviceRadio = document.getElementById("Service");
        const productSection = document.querySelector(".product-section");
        const serviceSection = document.querySelector(".service-section");
        const toggleSections = () => {
            if (productRadio.checked) {
                productSection.style.display = "block";
                serviceSection.style.display = "none";
            } else if (serviceRadio.checked) {
                productSection.style.display = "none";
                serviceSection.style.display = "block";
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
            const totalImages = $("#prod_image")[0].files.length;
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
                alert(hsn_code);
                let sac_code = $("input[name='sac_code']").val();
                let opening_stock_bal = $("input[name='opening_stock_bal']").val();
                let purchase_price = $("input[name='purchase_price']").val();
                let selling_price = $("input[name='selling_price']").val();
                let ser_selling_price = $("input[name='ser_selling_price']").val();
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
                //itemData.append("sec_unit", sec_unit);
               // itemData.append("base_unit_other", base_unit_other);

               // itemData.append("sac_code", sac_code);
                itemData.append("hsn_code", hsn_code);
                itemData.append("sac_code", sac_code);
                itemData.append("opening_stock_bal", opening_stock_bal);
                itemData.append("purchase_price", purchase_price);
                itemData.append("selling_price", selling_price);
                itemData.append("ser_selling_price", ser_selling_price);
                itemData.append("disc_sell", disc_sell);
                itemData.append("ser_disc_sell", ser_disc_sell);
                itemData.append("disc_sell_type", disc_sell_type);
                itemData.append("ser_disc_sell_type", ser_disc_sell_type);
                itemData.append("prod_desc", prod_desc);
                itemData.append("ser_desc", ser_desc);
                for (let i = 0; i < totalImages; i++) {
                    itemData.append("prod_image" + i, prod_image);
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
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: suburl,
                    type: "POST",
                    data: itemData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        //console.log(response);
                        // $("#addvendoromerLoader").hide();
                        if (response.class == "succ") {
                            //console.log(response);
                            //$("#add_vendor_bank .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
                            window.location.href = response.redirect;
                        } else {
                            $.each(response, function (idx, obj) {
                                $("#add_vendor_bank .message-container").html(
                                    '<div class="err">' + obj + "</div>"
                                );
                            });
                        }
                    },
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

</script>

@endsection
