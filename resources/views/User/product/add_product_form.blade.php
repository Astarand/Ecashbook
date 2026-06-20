

<div class="pc-content">
    
    <form action="javascript:void(0);" name="addItemFrm" id="addItemFrm" method="post" enctype="multipart/form-data">
        <div class="row mb-4">
            <input type="hidden" name="id" id="prodId" value="">
            @csrf
            <h6 class="mb-0">Select Type</h6>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3 m-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="item_type" id="Product" value="product" checked>
                        <label class="form-check-label" for="Product">Product</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3 m-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="item_type" id="Service" value="service">
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
                            <input type="text" class="form-control" name="item_name" id="item_name" placeholder="Enter Product Name">
                        </div>
                       {{-- -<div class="mb-3">
                            <button id="getOtpBtn" class="btn btn-warning">Get OTP</button>
                        </div>

                        <div class="mb-3">
                            <input type="text" id="otp_input" class="form-control" placeholder="Enter OTP">
                            <input type="hidden" id="txn_input">
                            <button id="verifyOtpBtn" class="btn btn-success">Verify OTP</button>
                        </div>--}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">HSN Code<span class="text-danger">*</span></label>
                            <div class="mb-3">
                               <div class="input-group">
                                    <input type="text" class="form-control" name="hsn_code" id="hsn_code" placeholder="Enter HSN Number">
                                    <a href="https://cbic-gst.gov.in/gst-goods-services-rates.html" target="_blank" class="btn btn-primary" id="searchHsnBtn">
                                        <i class="ti ti-search align-middle"></i> Search HSN
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="inputEmail4">Opening Stock<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="opening_stock_bal" id="opening_stock_bal" placeholder="Enter Opening Stock">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="inputEmail4">GST Rate<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="gst_rate_prod" id="gst_rate_prod" placeholder="Enter GST Rate">
                        </div>
                         <div class="mb-3 col-md-3">
                            <label class="form-label" for="inputEmail4">Goods Description</label>
                            <input type="text" class="form-control" name="goods_desc" id="goods_desc" placeholder="Enter Goods Description">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="inputEmail4">Base Unit<span class="text-danger">*</span></label>
                            <select class="select form-select" name="base_unit" id="base_unit">
                                <option value="">None</option>
                                <option value="BAG">BAGS (Bag)</option>
                                <option value="BTL">BOTTLES (Bottle)</option>
                                <option value="BDL">BUNDLES (Bundle)</option>
                                <option value="BKL">BUCKETS (Bucket)</option>
                                <option value="BOU">BOUNDS (Bound)</option>
                                <option value="BOX">BOXES (Box)</option>                               
                                <option value="BUN">BUNS (Bun)</option>
                                <option value="CAN">CANS (Can)</option>
                                <option value="CBM">CUBIC METERS (Cbm)</option>
                                <option value="CCM">CUBIC CENTIMETERS (Ccm)</option>
                                <option value="CMS">CENTIMETERS (Cms)</option>
                                <option value="CTN">CARTONS (Ctn)</option>
                                <option value="DOZ">DOZENS (Dzn)</option>
                                <option value="DRM">DRUMS (Drm)</option>
                                <option value="GGK">GUNNY BAGS (Ggk)</option>
                                <option value="GMS">GRAMS (Gms)</option>
                                <option value="GRS">GROSS (Grs)</option>
                                <option value="GYD">GALLONS (Gyd)</option>
                                <option value="KGS">KILOGRAMS (Kgs)</option>
                                <option value="KLR">KILOLITERS (Klr)</option>
                                <option value="KME">KILOMETERS (Kme)</option>
                                <option value="LTR">LITERS (Ltr)</option>
                                <option value="MLT">MILLILITERS (Mlt)</option>
                                <option value="MTR">METERS (Mtr)</option>
                                <option value="MTS">MATS (Mts)</option>
                                <option value="NOS">NUMBERS (Nos)</option>
                                <option value="OTH">OTHERS (Oth)</option>
                                <option value="PAC">PACKS (Pac)</option>
                                <option value="PCS">PIECES (Pcs)</option>
                                <option value="PRS">PAIRS (Prs)</option>
                                <option value="QTL">QUINTALS (Qtl)</option>
                                <option value="ROL">ROLLS (Rol)</option>
                                <option value="SET">SETS (Set)</option>
                                <option value="SQF">SQUARE FEET (Sqf)</option>
                                <option value="SQM">SQUARE METERS (Sqm)</option>
                                <option value="SQY">SQUARE YARDS (Sqy)</option>
                                <option value="TBS">TABLETS (Tbs)</option>
                                <option value="TGM">TROUGHS (Tgm)</option>
                                <option value="THD">THREES (Thd)</option>
                                <option value="TON">TONS (Ton)</option>
                                <option value="TUB">TUBES (Tub)</option>
                                <option value="UGS">UNITS (Ugs)</option>
                                <option value="UNT">UNITS (Unt)</option>
                                <option value="YDS">YARDS (Yds)</option>
                                <option value="LTR">LITERS (Ltr)</option>
                                <option value="NA">NOT APPLICABLE (Na)</option>
                                <option value="KG">KILOGRAMMES (Kg)</option>
                                <option value="LTR">LITER (Ltr)</option>
                                <option value="MTR">METERS (Mtr)</option>
                                <option value="ML">MILILITER (Ml)</option>
                                <option value="NOS">NUMBERS (Nos)</option>
                                <option value="PACK">PACKS (Pac)</option>
                                <option value="PAIR">PAIRS (Prs)</option>
                                <option value="PCS">PIECES (Pcs)</option>
                                <option value="QTL">QUINTAL (Qtl)</option>
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
                            <input type="number" class="form-control" name="purchase_price" id="purchase_price" placeholder="Enter Purchase Price">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Selling Price<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="selling_price" id="selling_price" placeholder="Enter Selling Price">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Discount on Selling Price<span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="input-group mb-0">
                                    <input type="number" class="form-control has-success" required name="disc_sell" id="disc_sell" value="0" aria-label="Selling Price" placeholder="Discount" aria-invalid="false" style="width: 80%;">
                                    <select class="form-select has-success" name="disc_sell_type" id="disc_sell_type" aria-label="Select Action" aria-invalid="false" style="width: 20%;">
                                        <option value="percentage" selected="">Percentage</option>
                                        <option value="amount">Amount</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="exampleFormControlTextarea1">Product Details</label>
                            <textarea class="form-control" name="prod_desc" id="prod_desc" rows="4" placeholder="Write Product Details"></textarea>
                        </div>
                        <div class="mb-3 col-md-6">
                            <div class="card-body">
                                <div class="fallback">
                                    <input type="file" name="prod_image[]"  id="prod_image" multiple>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <div class="message-container"></div>
                            <div id="addEmployeeLoader" class="loader"></div>
                            <button type="submit" name="saveBtn" class="btn btn-primary d-flex align-items-center" disabled>Add Product <i class="ti ti-arrow-up-right-circle ms-2"></i></button>
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
                            <input type="text" class="form-control" name="service_name" id="service_name" placeholder="Enter Product Name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SAC Code<span class="text-danger">*</span></label>
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="sac_code" id="sac_code" placeholder="Enter SAC Number">
                                    <button class="btn btn-primary" type="button"><i class="ti ti-search align-middle"></i> Serarch SAC</button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">GST Rate<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gst_rate_service" id="gst_rate_service" placeholder="Enter GST Rate">
                        </div>
						<div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Govt. Payment<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gov_pay" id="gov_pay" value="0" placeholder="Enter govt. payment">
                        </div>
						<div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Service Payment<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ser_pay" id="ser_pay" value="0" placeholder="Enter service payment">
                        </div>
                    </div>
                    <div class="row">
                        <h5 class="text-muted"> Service Details</h5>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputEmail4">Selling Price<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ser_selling_price" id="ser_selling_price" placeholder="Enter Selling Price">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputEmail4">Discount on Selling Price<span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="input-group mb-0">
                                    <input type="text" class="form-control has-success" name="ser_disc_sell" id="ser_disc_sell" value="" aria-label="Selling Price" placeholder="Discount" aria-invalid="false" style="width: 80%;">
                                    <select class="form-select has-success" name="ser_disc_sell_type" id="ser_disc_sell_type" aria-label="Select Action" aria-invalid="false" style="width: 20%;">
                                        <option value="percentage" selected="">Percentage</option>
                                        <option value="amount">Amount</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="exampleFormControlTextarea1">Service Details</label>
                            <textarea class="form-control" name="ser_desc" id="ser_desc" rows="4" placeholder="Write Service Details"></textarea>
                        </div>
                        <!-- <div class="mb-3 col-md-6">
                            <div class="card-body">
                                
                                    <div class="fallback">
                                    <input type="file" id="prod_image" name="prod_image">
                                    </div>
                                
                            </div>
                        </div> -->
                        <div class="mb-3 col-md-6">
                            <div class="card-body">
                                <div class="fallback">
                                    <input type="file" name="prod_image[]"  id="prod_image1" multiple>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="message-container"></div>
                            <div id="addEmployeeLoader" class="loader"></div>
                            <button type="submit" name="saveBtn" id="serviceSubmitBtn" class="btn btn-primary d-flex align-items-center">Add Service <i class="ti ti-arrow-up-right-circle ms-2"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                <!-- end tab content-->
    </form>
</div>
</form>
</div>

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

   
    $(document).ready(function () {
        function toggleSections() {
            let itemType = $('input[name="item_type"]:checked').val();
            if (itemType === 'product') {
                $('.product-section').show();
                $('.service-section').hide();

                // Make product fields required
                $('#item_name, #hsn_code, #opening_stock_bal, #base_unit, #purchase_price, #selling_price, #disc_sell').attr('required', true);
                $('#service_name, #sac_code, #ser_selling_price, #ser_disc_sell').removeAttr('required');

                // Enable/disable submit button
                checkProductForm();
            } else {
                $('.product-section').hide();
                $('.service-section').show();

                // Make service fields required
                $('#service_name, #sac_code, #ser_selling_price, #ser_disc_sell').attr('required', true);
                $('#item_name, #hsn_code, #opening_stock_bal, #base_unit, #purchase_price, #selling_price, #disc_sell').removeAttr('required');

                // Enable/disable submit button
                checkServiceForm();
            }
        }

        // Trigger on change
        $('input[name="item_type"]').change(function () {
            toggleSections();
        });

        // Recheck inputs on typing
        $('input, select, textarea').on('input change', function () {
            toggleSections();
        });

        function checkProductForm() {
            let filled = $('#item_name, #hsn_code, #gst_rate_prod, #opening_stock_bal, #base_unit, #purchase_price, #selling_price, #disc_sell')
                .toArray()
                .every(input => $(input).val().trim() !== '');
            $('button[name="saveBtn"]').prop('disabled', !filled);
        }

        function checkServiceForm() {
            let filled = $('#service_name, #sac_code, #gst_rate_service, #ser_selling_price, #ser_disc_sell')
                .toArray()
                .every(input => $(input).val().trim() !== '');
            $('button[name="saveBtn"]').prop('disabled', !filled);
        }

        // Initial call
        toggleSections();

   $("#searchHsnBtn").on("click", function() {
    let hsnCode = $("#hsn_code").val();
    if (!hsnCode) { 
        alert("Please enter HSN Code"); 
        return; 
    }

    // Step 1: OTP Request
    $.post("/gst/otp-request", {_token: "{{ csrf_token() }}"}, function(res) {
        console.log("OTP Request Response:", res);

        if (res.header && res.header.txn) {
            let txn = res.header.txn;
            console.log("Txn ID (save this):", txn);

            // Ask user to enter OTP (manual input for testing)
            let otp = prompt("Enter the OTP sent to your registered email/mobile:");

            // Step 2: OTP Verify
            $.post("/gst/otp-verify", {otp: otp, txn: txn, _token: "{{ csrf_token() }}"}, function(res2) {
                console.log("OTP Verify Response:", res2);

                if (res2.success) {
                    console.log("Auth Token:", res2.data.auth_token);
                    console.log("Refresh Token:", res2.data.refresh_token);

                    // Step 3: HSN Search
                    $.post("/gst/hsn-details", {hsn_code: hsnCode, _token: "{{ csrf_token() }}"}, function(res3) {
                        console.log("HSN Details Response:", res3);

                        if (res3.success) {
                            $("#cgst_rate").val(res3.data.cgst_rate);
                            $("#sgst_rate").val(res3.data.sgst_rate);
                            $("#igst_rate").val(res3.data.igst_rate);
                            $("#cess_rate").val(res3.data.cess_rate);
                        } else {
                            alert(res3.message);
                        }
                    });
                } else {
                    alert(res2.message);
                }
            });
        } else {
            console.error("Txn not found in OTP request response");
        }
    });
});
$("#getOtpBtn").on("click", function() {
        console.log("Get OTP button clicked"); // सबसे पहले ये console में दिखना चाहिए

        $.ajax({
            url: "/gst/otp-request",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                console.log("OTP Request Response:", res);
            },
            error: function(xhr, status, error) {
                console.error("OTP Request Error:", xhr.responseText);
            }
        });
    });
});






    //--------------------
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
        $("form#addItemFrm").on("submit", function(e) {
            e.preventDefault(); // Prevent default form submission

            const itemType = $("[name='item_type']:checked").val() || $("[name='item_type']").val();

            if (itemType === "product") {
                const openingStock = $("[name='opening_stock_bal']").val() || "0";

                Swal.fire({
                    title: 'Confirm Opening Stock',
                    text: `You have entered Opening Stock: ${openingStock}. Do you want to proceed?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, continue',
                    cancelButtonText: 'No, go back'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm(); // Now this is defined below
                    }
                });

                return; // stop here, wait for confirmation
            }

            submitForm(); // for non-product item types
        });

        function submitForm() {
            const prodId = $("#prodId").val();
			var sId = $("#sId").val();
			let imageInput = getActiveImageInput();
			let files = imageInput ? imageInput.files : [];

            if (!imageInput) {
                console.error("Element not found: #prod_image");
                return;
            }

            const totalImages = files.length;
            let itemData = new FormData();
			itemData.append("sId", sId);
			itemData.append("gst_trans", "intrastate");
			for (let i = 0; i < files.length; i++) {
				itemData.append("prod_image[]", files[i]);
			}
            itemData.append("totalImages", totalImages);

            // Collect other form values
            const fields = [
                "item_type", "item_name", "service_name", "base_unit",
                "hsn_code", "sac_code", "gst_rate_prod", "gst_rate_service","gov_pay","ser_pay", "goods_desc", "opening_stock_bal",
                "purchase_price", "selling_price", "ser_selling_price",
                "disc_sell", "ser_disc_sell", "disc_sell_type",
                "ser_disc_sell_type", "prod_desc", "ser_desc"
            ];

            fields.forEach(field => {
                const value = $(`[name='${field}']:checked`).val() || $(`[name='${field}']`).val();
                itemData.append(field, value || "");
            });

            itemData.append("id", prodId);

            const suburl = getSubmitUrl();
			$("#invoiceData").html('');
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
                    showToast("Product Added Successfully", "success");
					$("#invoiceData").html(response);
					$('#productServiceModal').modal('hide');
					$('#addItemFrm')[0].reset();
                },
                error: function(xhr, status, error) {
					$("#loader").hide();
                    console.error("AJAX Error:", status, error);
                },
            });
        }
		
		function getSubmitUrl() {
			let path = window.location.pathname;

			if (path.includes('edit-sales-invoice')) {
				return '/save-product-and-add-sales';
			} 
			if (path.includes('edit-purchase-invoice')) {
				return '/save-product-and-add-purchase';
			}
			if (path.includes('edit-quotation-invoice')) {
				return '/save-product-and-add-quotation';
			} 
			if (path.includes('edit-proforma-invoice')) {
				return '/save-product-and-add-proforma';
			}

			// default fallback
			return '/save-product-and-add-sales';
		}

    });
</script>
