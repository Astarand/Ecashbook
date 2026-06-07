

$(function() {
	var base_url = $("#base_url").val();
	$.ajaxSetup({
		headers: {
		  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	// Toast Notification Function
	function showToast(message, type) {
		Toastify({
			text: message,
			duration: 3000, // Show for 3 seconds
			close: true,
			gravity: "top", // Position: Top
			position: "right", // Align: Right
			backgroundColor: type === "success" ? "#28a745" : "#dc3545", // Green for success, Red for error
			stopOnFocus: true, // Stop on hover
			style: {
				fontSize: "18px", // Larger Font
				padding: "16px 24px", // More Padding
				borderRadius: "8px", // Smooth Edges
				background: type === "success" ? "#28a745" : "#dc3545", // Green for success, Red for error
				color: "#fff", // White text
				boxShadow: "0px 5px 15px rgba(0, 0, 0, 0.2)", // Nice Shadow
			},
		}).showToast();
	}

	var setReminderFrmCA = $('#setReminderFrmCA').validate({
		rules: {
			reminder_type: {
				required: true
			},
			user_type: {
				required: true
			},
			customer_type: {
				required: true
			},
			reminder_through: {
				required: true
			},
			sub_text: {
				required: true
			},
			msg_text: {
				required: true
			},
			
		},
		messages: {
				reminder_type: {
					required: "Remider type is required"
				},
				user_type: {
					required: "User type is required"
				},
				customer_type: {
					required: "Customer type is required"
				},
				reminder_through: {
					required: "Remider through is required"
				},
				sub_text: {
					required: "Subject is required"
				},
				msg_text: {
					required: "Message is required"
				},
				
			},
			errorElement: "em",
			errorPlacement: function(error, element) {
				error.addClass("help-block");
				error.insertAfter(element);
			},
			highlight: function(element, errorClass, validClass) {
				$(element).addClass("has-error").removeClass("has-success");
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).addClass("has-success").removeClass("has-error");
			},
		});

		$('form#setReminderFrmCA').bind('submit',function(){
			
			if (setReminderFrmCA.form()) {
				//alert('hidd');
				$('#loader').show();
				let reminderData = new FormData(this);
				//var formData = $('form#setReminderFrmCA').serialize();
				/*let reminder_type = $('#setReminderFrmCA #reminder_type').val();
				let user_type = $('#setReminderFrmCA #user_type').val();
				let customer_type = $('#setReminderFrmCA #customer_type').val();
				let reminder_through = $('#setReminderFrmCA #reminder_through').val();
				let userId = $('#setReminderFrmCA #userId').val();
				//let fileAttached = $('#setReminderFrmCA #fileAttached').prop('files')[0];
				let sub_text = $('#setReminderFrmCA #sub_text').val();
				let msg_text = $('#setReminderFrmCA #msg_text').val();

				let reminderData = new FormData();
				reminderData.append('reminder_type', reminder_type);
				reminderData.append('user_type', user_type);
				reminderData.append('customer_type', customer_type);
				reminderData.append('reminder_through', reminder_through);
				reminderData.append('userId', userId);
				reminderData.append('fileAttached', fileAttached);
				reminderData.append('sub_text', sub_text);
				reminderData.append('msg_text', msg_text);*/		
						
				var suburl = base_url + '/sendReminderCA';
				$.ajax({
					url: suburl,
					type:'POST',
					data:reminderData,
					contentType: false,
					processData: false,
					success: function(response) {
						console.log(response);
						$('#loader').hide();
						if (response.class=="succ") {
							showToast(response.message, "success");
							$("#sub_text").val('');
							$("#msg_text").val('');
							$("#setReminderFrmCA")[0].reset();
							$("input[name='userId[]']").remove();
							$("#toastContainer").empty();
							
							
						} else {
							showToast("Error: " + response.message, "error");
						}
					}
				});
			}
		});

	//Start CA update profile
		var frmprofileimageCA = $('#frmprofileimageCA').validate({
			rules: {
				comp_logo: {
					required: true
				},
			},
			messages: {
				comp_logo: {
					required: "Image is required"
				}
			},
			errorElement: "em",
			errorPlacement: function(error, element) {
				error.addClass("help-block");
				error.insertAfter(element);
			},
			highlight: function(element, errorClass, validClass) {
				$(element).addClass("has-error").removeClass("has-success");
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).addClass("has-success").removeClass("has-error");
			},
		});

		$("#triggerFileUpload").click(function () {
			$('#comp_logo_ca').click();
			$("#comp_logo_ca").off('change').on('change', function () {
				if (frmprofileimageCA.form()) {
					$('#loader').show();
					let comp_logo = $(this).prop('files')[0];
					let comp_profile_data = new FormData();
					comp_profile_data.append('comp_logo', comp_logo);
		
					$.ajax({
						url: base_url + '/update_comp_logo_ca',
						type: 'POST',
						data: comp_profile_data,
						contentType: false,
						processData: false,
						success: function (response) {
							$('#loader').hide();
							if (response.class == "succ") {
								showToast(response.message, "success");
								$('#image-preview').attr('src', base_url + '/storage/ca_profile/' + response.image_name);
								$('#comp_logo_ca').val('');
							} else {
								$.each(response, function (idx, obj) {
									showToast(obj, "error");
								});
							}
						},
						error: function (xhr, status, error) {
							$('#loader').hide();
					
							if (xhr.status === 413 || xhr.status === 422) {
								showToast("File too large. Max allowed size is 5MB.", "error");
							} else {
								showToast("Upload failed. Please try again.", "error");
							}
						}
					});
					
				}
			});
		});
		

		//Delete CA logo delete
		$(".compimagedelCA").click(function() {
				$('#loader').show();
				let comp_logo_data = new FormData();

				$.ajax({
					url: base_url + '/delete_comp_logo_ca',
					type:'POST',
					data:comp_logo_data,
					contentType: false,
					processData: false,
					success: function(response) {
						if (response.class=="succ") {
							$("#frmprofileimage .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
							$('#image-preview').attr('src', base_url+'/public/assets/img/profiles/avatar-10.jpg');
						} else {
							$('#loader').hide();
							$.each(response, function(idx, obj) {
								console.log(obj);
								$("#frmprofileimage .message-container").html('<div class="err">'+obj+'</div>');
							});
						}
					}
				});
		});
		
		//Start update company details
		var CAfrmcompdet = $('#CAfrmcompdet').validate({
			rules: {

				comp_name: {
					required: true,
					minlength: 3,
				},
				comp_phone: {
					required: true,
					minlength: 10,
					maxlength: 10,
					number: true
				},
				comp_email: {
					required: true,
					email: true
				},
				no_ca_firm: {
					required: true,
					number: true
				},
				no_employee: {
					required: true,
					number: true
				},

				type_of_firm: {
					required: true
				},
				constitution_type: {
					required: true
				},
				year_of_experience: {
					required: true,
					number: true
				},
				software_licenses: {
					required: true
				},
				// basic_percentage: {
				// 	required: true,
				// 	number: true,
				// 	min: 40,
				// 	max: 60
				// },

				about_firm: {
					required: true
				},
				comp_bill_addone: {
					required: true
				},
				comp_bill_state: {
					required: true
				},
				comp_bill_city: {
					required: true
				},
				comp_bill_pin: {
					required: true,
					digits: true,
					minlength: 6,
					maxlength: 6
				},
			},

			messages: {
				comp_name: {
					required: "Name is required",
				},
				comp_phone: {
					required: "Mobile is required",
					minlength: "Enter 10 digits",
					maxlength: "Enter 10 digits"
				},
				comp_email: {
					required: "Email is required",
				},
				no_ca_firm: {
					required: "No. of CA is required"
				},
				no_employee: {
					required: "No. of employee is required"
				},

				type_of_firm: {
					required: "Select firm type"
				},
				constitution_type: {
					required: "Select constitution type"
				},
				year_of_experience: {
					required: "Enter experience"
				},
				software_licenses: {
					required: "Enter software details"
				},
				// basic_percentage: {
				// 	required: "Enter percentage",
				// 	min: "Minimum 40%",
				// 	max: "Maximum 60%"
				// },

				about_firm: {
					required: "About firm is required"
				},
				comp_bill_addone: {
					required: "Address line 1 is required",
				},
				comp_bill_state: {
					required: "State is required",
				},
				comp_bill_city: {
					required: "City is required",
				},
				comp_bill_pin: {
					required: "Pincode is required",
					minlength: "Must be 6 digits",
					maxlength: "Must be 6 digits"
				},
			},

			errorElement: "em",
			errorPlacement: function(error, element) {
				error.addClass("help-block");
				error.insertAfter(element);
			},
			highlight: function(element) {
				$(element).addClass("is-invalid").removeClass("is-valid");
			},
			unhighlight: function(element) {
				$(element).addClass("is-valid").removeClass("is-invalid");
			},
		});

			$('form#CAfrmcompdet').bind('submit',function(){

				if (CAfrmcompdet.form()) {

					$('#loader').show();
					var formCompData = {
						 
						 comp_name : $("#CAfrmcompdet #comp_name").val(),
						 comp_phone : $("#CAfrmcompdet #comp_phone").val(),
						 comp_email : $("#CAfrmcompdet #comp_email").val(),
						 no_ca_firm : $("#CAfrmcompdet #no_ca_firm").val(),
						 no_employee : $("#CAfrmcompdet #no_employee").val(),
						 total_no_client : $("#CAfrmcompdet #total_no_client").val(),
						 comp_gst_no : $("#CAfrmcompdet #comp_gst_no").val(),
						 // basic_percentage : $("#CAfrmcompdet #basic_percentage").val(),

						 about_firm : $("#CAfrmcompdet #about_firm").val(),
						 comp_bill_addone : $("#CAfrmcompdet #comp_bill_addone").val(),
						 comp_bill_addtwo : $("#CAfrmcompdet #comp_bill_addtwo").val(),
						 //comp_bill_country : $("#CAfrmcompdet #country option:selected").val(),
						 comp_bill_state : $("#CAfrmcompdet #state option:selected").val(),
						 comp_bill_city : $("#CAfrmcompdet #city option:selected").val(),
						 comp_bill_pin : $("#CAfrmcompdet #comp_bill_pin").val(),

						  // NEW FIELDS ADDED (Not required)
						type_of_firm: $("#type_of_firm").val(),
						constitution_type: $("#constitution_type").val(),
						year_of_experience: $("#year_of_experience").val(),
						software_licenses: $("#software_licenses").val(),
						tan_no: $("#tan_no").val(),
						pt_reg_no: $("#pt_reg_no").val(),
						epf_reg_no: $("#epf_reg_no").val(),
						esic_reg_no: $("#esic_reg_no").val(),
					}
					$.ajax({
						url: base_url + '/update_compdet_ca',
						type:'POST',
						data:formCompData,
						success: function(response) {
							if (response.class=="succ") {
								showToast(response.message, "success");
                        		setTimeout(() => location.reload(), 2000);

								// $("#CAfrmcompdet .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
							} else {
								$('#loader').hide();
								$.each(response, function(idx, obj) {
									showToast(obj, "error");
									//alert(obj);
									// $("#CAfrmcompdet .message-container").html('<div class="err">'+obj+'</div>');
								});
							}
						}
					});


				}
			});
			
		//Start CA Speclization details
		var frmCa_spec = $('#frmCa_spec').validate({
			rules: {
				'ca_spec[]': {
					required: true
				},
				other_service_box: {
					required: function() {
						return $('#other_service_box').is(':checked');
					}
				}
			},
			messages: {
				'ca_spec[]': {
					required: "Specialization is required"
				},
				other_service_box: {
					required: "Please specify your specialized service"
				}
			},
			errorElement: "em",
			errorPlacement: function(error, element) {
				error.addClass("help-block");
				error.insertAfter(element);
			},
			highlight: function(element) {
				$(element).addClass("has-error").removeClass("has-success");
			},
			unhighlight: function(element) {
				$(element).addClass("has-success").removeClass("has-error");
			}
		});

		$('form#frmCa_spec').on('submit',function(e){
			//e.preventDefault();
			if (frmCa_spec.form()) {
				$('#loader').show();
				var formCASpec = $('form#frmCa_spec').serialize();
				$.ajax({
					url: base_url + '/update_ca_speclization',
					type:'POST',
					data:formCASpec,
					success: function(response) {
						$('#loader').hide();
						if (response.class=="succ") {
							showToast(response.message, "success");
							// $("#frmCa_spec .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
						} else {
							$('#loader').hide();
							$.each(response, function(idx, obj) {
								showToast(obj, "error");
								// $("#frmCa_spec .message-container").html('<div class="err">'+obj+'</div>');
							});
						}
					}
				});
			}
		});
			
		//Start CA bank details
		var CAfrmbankdet = $('#CAfrmbankdet').validate({
		rules: {
			bank_name: {
				required: true
			},
			ac_no: {
				required: true,
				number: true
			},
			ifsc_code: {
				required: true
			}

		},
		messages: {
				bank_name: {
					required: "Bank name is required",
				},
				ac_no: {
					required: "A/C is required",
				},
				ifsc_code: {
					required: "IFSC is required",
				}
			},
			errorElement: "em",
			errorPlacement: function(error, element) {
				error.addClass("help-block");
				error.insertAfter(element);
			},
			highlight: function(element, errorClass, validClass) {
				$(element).addClass("has-error").removeClass("has-success");
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).addClass("has-success").removeClass("has-error");
			},
		});

		$('form#CAfrmbankdet').on('submit',function(e){
			//e.preventDefault();
			if (CAfrmbankdet.form()) {
				$('#loader').show();
				var formCompBank = $('form#CAfrmbankdet').serialize();
				$.ajax({
					url: base_url + '/update_bankdet_ca',
					type:'POST',
					data:formCompBank,
					success: function(response) {
						$('#loader').hide();
						if (response.class=="succ") {
							showToast(response.message, "success");
							// $("#CAfrmbankdet .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
						} else {							
							$.each(response, function(idx, obj) {
							showToast(obj, "error");

								// $("#CAfrmbankdet .message-container").html('<div class="err">'+obj+'</div>');
							});
						}
					}
				});
			}
		});
		
		//Start CA Partners details
		var frmPartnerdet = $('#frmPartnerdet').validate({
		rules: {
			partner_name: {
				required: true
			},
			partner_no: {
				required: true,
				number: true
			},
			partner_email: {
				required: true
			}

		},
		messages: {
				partner_name: {
					required: "Partner name is required",
				},
				partner_no: {
					required: "Contact is required",
				},
				partner_email: {
					required: "Email is required",
				}
			},
			errorElement: "em",
			errorPlacement: function(error, element) {
				error.addClass("help-block");
				error.insertAfter(element);
			},
			highlight: function(element, errorClass, validClass) {
				$(element).addClass("has-error").removeClass("has-success");
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).addClass("has-success").removeClass("has-error");
			},
		});

		$('form#frmPartnerdet').on('submit',function(e){
			//e.preventDefault();
			if (frmPartnerdet.form()) {
				$('#loader').show();
				var formCApartner = $('form#frmPartnerdet').serialize();
				$.ajax({
					url: base_url + '/update_partner_ca',
					type:'POST',
					data:formCApartner,
					success: function(response) {
						$('#loader').hide();
						if (response.class=="succ") {
							showToast(response.message, "success");
						} else {							
							$.each(response, function(idx, obj) {
								showToast(obj, "error");
							});
						}
					}
				});
			}
		});
		
		//Start CA attachments update
		var gstdocstate = $("#gstdocstate").val();
		if(gstdocstate =="")
		{
			var CAfrmattadet = $('#CAfrmattadet').validate({
				rules: {
					// checkboxAccept: {
					// 	required: true
					// },
					// gst_doc: {
					// 	required: true
					// },
					// pan_doc: {
					// 	required: true
					// },
					// tan_doc: {
					// 	required: true
					// },
					// cin_doc: {
					// 	required: true
					// },
					// other_logo_doc: {
					// 	required: true
					// },
					// signature_doc: {
					// 	required: true
					// },
					// stamp_doc: {
					// 	required: true
					// },
				},
				messages: {
					// gst_doc: {
					// 	required: "GST doc is required"
					// },
					// pan_doc: {
					// 	required: "PAN doc is required"
					// },
					// tan_doc: {
					// 	required: "TAN doc is required"
					// },
					// cin_doc: {
					// 	required: "CIN doc is required"
					// },
					// other_logo_doc: {
					// 	required: "Logo is required"
					// },
					// signature_doc: {
					// 	required: "Signature doc is required"
					// },
					// stamp_doc: {
					// 	required: "Stamp doc is required"
					// },
				},
				errorElement: "em",
				errorPlacement: function(error, element) {
					error.addClass("help-block");
					error.insertAfter(element);
				},
				highlight: function(element, errorClass, validClass) {
					$(element).addClass("has-error").removeClass("has-success");
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).addClass("has-success").removeClass("has-error");
				},
			});
		}else{
				var CAfrmattadet = $('#CAfrmattadet').validate({
				rules: {
				},
				messages: {
				},
			});

		}

		$('form#CAfrmattadet').bind('submit',function(){
			var checkAccept = $('#checkboxAccept').is(':checked');
			if (!checkAccept) {
				// alert("Please accept Terms & Conditions");
				showToast("Please accept Terms & Conditions", "error");

			}
			else if (CAfrmattadet.form() && checkAccept) {
				$('#loader').show();
				let gst_doc = $('#CAfrmattadet #gst_doc').prop('files')[0];
				let pan_doc = $('#CAfrmattadet #pan_doc').prop('files')[0];
				let tan_doc = $('#CAfrmattadet #tan_doc').prop('files')[0];
				let cin_doc =   $('#CAfrmattadet #cin_doc').prop('files')[0];
				let other_logo_doc =   $('#CAfrmattadet #other_logo_doc').prop('files')[0];
				let signature_doc =   $('#CAfrmattadet #signature_doc').prop('files')[0];
				let stamp_doc =   $('#CAfrmattadet #stamp_doc').prop('files')[0];

				let comp_atta_data = new FormData();

				comp_atta_data.append('gst_doc', gst_doc);
				comp_atta_data.append('pan_doc', pan_doc);
				comp_atta_data.append('tan_doc', tan_doc);
				comp_atta_data.append('cin_doc', cin_doc);
				comp_atta_data.append('other_logo_doc', other_logo_doc);
				comp_atta_data.append('signature_doc', signature_doc);
				comp_atta_data.append('stamp_doc', stamp_doc);
				comp_atta_data.append('gstdocstate', gstdocstate);
				$.ajax({
					url: base_url + '/update_ca_attachment',
					type:'POST',
					data:comp_atta_data,
					contentType: false,
					processData: false,
					success: function(response) {
						$('#loader').hide();
						if (response.class=="succ") {
							$("#gstdocstate").val(response.gstdocstate);
							showToast(response.message, "success");
							
						} else {							
							$.each(response, function(idx, obj) {
								//alert(obj);
								showToast(obj, "error");

								
							});
						}
					}
				});


			}
		});

		//Activate customer
		$('.custCAactive').click(function() {
			var status = $(this).data('stat');
			var cust_id = $(this).data('id');
			$.ajax({
				type: "GET",
				dataType: "json",
				url: base_url + '/changeCustomerStatus',
				data: {'status': status, 'id': cust_id},
				success: function(data) {
					showToast(data.message, "success");
		
					// Redirect after 2 seconds
					setTimeout(function() {
						window.location.href = data.redirect;
					}, 2000);
				}
			});
		});
		
		//view Customer Details
		$('.viewCustomerDet').click(function() {
			var cust_id = $(this).data('id');
			$.ajax({
				type: "POST",
				dataType: "json",
				url: base_url + '/viewCustomerDet',
				data: {'id': cust_id},
				success: function(data){
				  $("#cLogo").attr("src", base_url+'/public/uploads/profile/'+data.comp_logo);
				  $("#cName").html(data.name);
				  $("#cEmail").html(data.comp_email);
				  $("#cPhone").html(data.comp_phone);
				  $("#cCompName").html(data.comp_name);
				  $(".customer-mail").html(data.comp_website);
				  $(".customer-whatsapp").html(data.comp_phone);
				  var addr1 = (data.comp_bill_addone!=null)?data.comp_bill_addone:"" ;
				  var addr2 = (data.comp_bill_pin!=null)?data.comp_bill_pin:"";
				  //var fullAddre = (addr1 !="" && addr2 =!="")?(addr1 +","+ addr2):"";
				  $("#cAddr").html(addr1 +","+ addr2);
				  $("#cReqFor").html(data.request_for);
				  $("#requestedAt").html(data.requestedAt);
				}
			});
		});
		
		$('.viewCustomerDet').click(function() {
			var cust_id = $(this).data('id');
			$('.requestAccept').click(function() {
				var status = 1;
				$.ajax({
					type: "GET",
					dataType: "json",
					url: base_url + '/acceptCustomerStatus',
					data: {'status': status, 'id': cust_id},
					success: function(data){
					  //console.log(data.success)
					  window.location.href=data.redirect;
					}
				});
			});
			
			$('.requestDelete').click(function() {
				var status = 3;
				$.ajax({
					type: "GET",
					dataType: "json",
					url: base_url + '/acceptCustomerStatus',
					data: {'status': status, 'id': cust_id},
					success: function(data){
					  //console.log(data.success)
					  window.location.href=data.redirect;
					}
				});
			});
		});
		
			// $('.requestAccept').click(function() {
			// 	var cust_id = $(this).data('id');
			// 	var status = 1;
			// 	$.ajax({
			// 		type: "GET",
			// 		dataType: "json",
			// 		url: base_url + '/acceptCustomerStatus',
			// 		data: {'status': status, 'id': cust_id},
			// 		success: function(data){
			// 		  //console.log(data.success)
					  
			// 		  showToast(data.message, "success", );
			// 		  window.location.href=data.redirect;
			// 		}
			// 	});
			// });

			$('.requestAccept').click(function() {
					var cust_id = $(this).data('id');
					var status = 1;  // Accept status
					$.ajax({
						type: "POST",  // Ensure it's a POST request
						dataType: "json",
						url: base_url + '/acceptCustomerStatus',
						data: {
							'status': status,
							'id': cust_id,
							"_token": $("meta[name='csrf-token']").attr("content")  // CSRF token for security
						},
						success: function(data) {
							console.log(data);

							if (data.status == 'success') {
								// Show success message
								showToast(data.message, "success");
								window.location.href = data.redirect;
							} else {
								// Show error message if something goes wrong
								showToast(data.message, "error");
							}
						},
						error: function(xhr, status, error) {
							// Handle unexpected errors
							showToast("Something went wrong, please try again.", "error");
						}
					});
				});

				$('.requestDelete').click(function() {
					var cust_id = $(this).data('id');
					var status = 3;  // Accept status
					$.ajax({
						type: "POST",  // Ensure it's a POST request
						dataType: "json",
						url: base_url + '/acceptCustomerStatus',
						data: {
							'status': status,
							'id': cust_id,
							"_token": $("meta[name='csrf-token']").attr("content")  // CSRF token for security
						},
						success: function(data) {
							console.log(data);

							if (data.status == 'success') {
								// Show success message
								showToast(data.message, "success");
								window.location.href = data.redirect;
							} else {
								// Show error message if something goes wrong
								showToast(data.message, "error");
								window.location.href = data.redirect;
							}
						},
						error: function(xhr, status, error) {
							// Handle unexpected errors
							showToast("Something went wrong, please try again.", "error");
						}
					});
				});


			
			// $('.requestDelete').click(function() {
			// 	var cust_id = $(this).data('id');
			// 	var status = 3;
			// 	$.ajax({
			// 		type: "GET",
			// 		dataType: "json",
			// 		url: base_url + '/acceptCustomerStatus',
			// 		data: {'status': status, 'id': cust_id},
			// 		success: function(data){
			// 		  //console.log(data.success)
			// 		  window.location.href=data.redirect;
			// 		}
			// 	});
			// });
				
		//Start add agent
		var addAgentFrm = $('#addAgentFrm').validate({
		rules: {
			agent_name: {
				required: true
			},
			agent_email: {
				required: true
			},
			agent_phone: {
				required: true,
				number: true
			},
			agent_whats_no: {
				required: true,
				number: true
			},
			address_lineone: {
				required: true
			},
			agent_country: {
				required: true
			},
			agent_state: {
				required: true
			},
			agent_city: {
				required: true
			},
			agent_pincode: {
				required: true
			},

		},
		messages: {
				agent_name: {
					required: "Name is required"
				},
				agent_email: {
					required: "Email is required"
				},
				agent_phone: {
					required: "Contact is required"
				},
				agent_whats_no: {
					required: "Whats no. is required"
				},
				address_lineone: {
					required: "Address is required"
				},
				agent_country: {
					required: "Country is required"
				},
				agent_state: {
					required: "State is required"
				},
				agent_city: {
					required: "City is required"
				},
				agent_pincode: {
					required: "Pincode is required"
				},

			},
			errorElement: "em",
			errorPlacement: function(error, element) {
				error.addClass("help-block");
				error.insertAfter(element);
			},
			highlight: function(element, errorClass, validClass) {
				$(element).addClass("has-error").removeClass("has-success");
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).addClass("has-success").removeClass("has-error");
			},
		});

		$('form#addAgentFrm').on('submit', function (e) {
			e.preventDefault();

			if (addAgentFrm.form()) {
				// $('#addAgentLoader').show();

				let formData = new FormData(this);
				let agentId = $("#agentId").val();
				let suburl = agentId === ""
					? base_url + '/save_agent'
					: base_url + '/update_agent';

				$("#loader").show();
				$.ajax({
					url: suburl,
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,

					success: function (response) {
						$("#loader").hide();
						// $('#addAgentLoader').hide();

						// ✅ SUCCESS
						if (response.status === "success" || response.class === "succ") {
							showToast(response.message, "success");

							setTimeout(function () {
								window.location.href = response.redirect;
							}, 2000);
							return;
						}

						// ❌ VALIDATION ERROR (422 handled in success if returned manually)
						if (response.status === "validation_error" && response.errors) {
							let firstError = Object.values(response.errors)[0][0];
							showToast(firstError, "error");
							return;
						}

						// ❌ NORMAL ERROR MESSAGE
						if (response.message) {
							showToast(response.message, "error");
						} else {
							showToast("Something went wrong. Please try again.", "error");
						}
					},

					// ❌ REAL HTTP ERROR (422, 500, 419, etc.)
					error: function (xhr) {
						$('#addAgentLoader').hide();

						// Laravel validation 422
						if (xhr.status === 422 && xhr.responseJSON?.errors) {
							let firstError = Object.values(xhr.responseJSON.errors)[0][0];
							showToast(firstError, "error");
							return;
						}

						// Other server errors
						if (xhr.responseJSON?.message) {
							showToast(xhr.responseJSON.message, "error");
						} else {
							showToast("Server error. Please try again.", "error");
						}
					}
				});
			}
		});



		// $('form#addAgentFrm').on('submit', function(e) {
		// 	e.preventDefault();
		// 	if (addAgentFrm.form()) {
		// 		$('#addAgentLoader').show();
		
		// 		var formData = new FormData(this);
		// 		var agentId = $("#agentId").val();
		// 		var suburl = agentId == "" ? base_url + '/save_agent' : base_url + '/update_agent';
		
		// 		$.ajax({
		// 			url: suburl,
		// 			type: 'POST',
		// 			data: formData,
		// 			contentType: false,
		// 			processData: false,
		// 			success: function(response) {
		// 				// console.log(response);
						
		// 				$('#addAgentLoader').hide();
		// 				if (response.class == "succ") {
		// 					showToast(response.message, "success");
		// 					setTimeout(function() {
		// 						window.location.href = response.redirect;
		// 					}, 2000);
		// 				} else {
		// 					if (typeof response.message !== 'undefined') {
		// 						showToast(response.message, "error");
		// 					} else {
		// 						$.each(response, function(idx, obj) {
		// 							showToast(obj, "error");
		// 						});
		// 					}
		// 				}
		// 			}
		// 		});
		// 	}
		// });
		

		//End add agent
		
		//Activate agent
		// $('.agent_active').click(function() {
		// 	var status = $(this).data('stat');
		// 	var agent_id = $(this).data('id');
		// 	$.ajax({
		// 		type: "GET",
		// 		dataType: "json",
		// 		url: base_url + '/changeAgentStatus',
		// 		data: {'status': status, 'id': agent_id},
		// 		success: function(data){
		// 		  //console.log(data.success)
		// 			window.location.href=data.redirect;

		// 		}
		// 	});
		// });

		
		
		
		//Delete agent
		$('.agentdelete').click(function() {
			var agent_id = $(this).data('id');
			$('#del_agent').click(function() {
				$.ajax({
					type: "GET",
					dataType: "json",
					url: base_url + '/delAgent',
					data: {'id': agent_id},
					success: function(data){
					  //console.log(data.success)
					  window.location.href=data.redirect;

					}
				});
			});
		});

		//Start add task
		var addTaskFrm = $('#addTaskFrm').validate({
			rules: {
				task_date: {          
						required: true
					},
					task_time: {
						required: true
					},
					company_id: {
						required: true
					},
					task_category: {
						required: true
					},
					// task_sub_category: {
					// 	required: true
					// },
					gov_fees: {
						required: true
					},
					services_charges: {
						required: true
					},					
					emp_id: {
						required: true
					},
					project_priority: {
						required: true
					},
					due_date:{
						required: true
					},
					project_status: {
						required: true
					},
		
				},
				messages: {
					task_date: {
							required: "Date is required"
						},
						task_time: {
							required: "Time is required"
						},
						company_id: {
							required: "Name is required"
						},
						task_category: {
							required: "Category is required"
						},
						// task_sub_category: {
						// 	required: "Sub category is required"
						// },
						gov_fees: {
							required: "Goverment is required"
						},
						services_charges: {
							required: "Charges is required"
						},						
						emp_id: {
							required: "Name is required"
						},
						project_priority: {
							required: "Priority is required"
						},
						due_date:{
							required: "Due date is required"
						},
						project_status: {
							required: "Status is required"
						},
		
					},
					errorElement: "em",
					errorPlacement: function(error, element) {
						error.addClass("help-block");
						error.insertAfter(element);
					},
					highlight: function(element, errorClass, validClass) {
						$(element).addClass("has-error").removeClass("has-success");
					},
					unhighlight: function(element, errorClass, validClass) {
						$(element).addClass("has-success").removeClass("has-error");
					},
				});
		
				$('form#addTaskFrm').bind('submit',function(){
					//alert('hi');
					if (addTaskFrm.form()) {
						$('#addTaskLoader').show();
						var formTaskData = $('form#addTaskFrm').serialize();
						var taskId = $("#taskId").val();
						if(taskId =="") {
							var suburl = base_url + '/save_task';
						}else{
							var suburl = base_url + '/update_task';
						}
						$.ajax({
							url: suburl,
							type:'POST',
							data:formTaskData,
							success: function(response) {
								$('#addTaskLoader').hide();
							
								if (response.class == "succ") {
									// Show success toast
									showToast(response.message, "success");
							
									// Redirect after 2 seconds
									setTimeout(function () {
										window.location.href = response.redirect;
									}, 2000);
								} else {
									// Show validation errors
									$.each(response, function(idx, obj) {
										$("#addAgentFrm .message-container").html('<div class="err">' + obj + '</div>');
									});
								}
							}
							
						});
					}
		});

		$('#task_category').click(function() {
			var taskcatId = $("#task_category option:selected").val();	
			//alert(taskcatId);		
			
				$.ajax({
					type: "POST",
					dataType: "json",
					url: base_url + '/getcat',
					data: {'id': taskcatId},
					success: function(data){
					  console.log(data.success)
					  if(data !=""){
						$("#gov_fees").val(data.govfee);
						$("#services_charges").val(data.service_charge);
						$("#total_amount").val(Number(data.govfee)+Number(data.service_charge))
						$("#due_amount").val(Number(data.govfee)+Number(data.service_charge))
						$("#advance_payment").val(0);
					  }else{
						$("#gov_fees").val(0);
						$("#services_charges").val(0);
						$("#total_amount").val(0);
						$("#due_amount").val(0);
						$("#advance_payment").val(0);
					  }
					}
				});
			
		});
		
		$("#advance_payment").on("change input",function(){
			var advance_payment = $(this).val();
			var total_amount = $("#total_amount").val();
			var due_amount = Number(total_amount) - Number(advance_payment);
			$("#due_amount").val(due_amount);
		});
		//End add task

	
		//Delete task
		$('.taskdelete').click(function() {
			var taskId = $(this).data('id');			
			$('#del_task').click(function() {
				$.ajax({
					type: "GET",
					dataType: "json",
					url: base_url + '/delTask',
					data: {'id': taskId},
					success: function(data){
					  //console.log(data.success)
					  window.location.href=data.redirect;

					}
				});
			});
		});

		//Start add taskQuote
	    var addTaskquoteFrm = $('#addTaskquoteFrm').validate({
				rules: {
					task_cat: {          
							required: true
						},
						task_sub_cat: {
							required: true
						},
						govfee: {
							required: true
						},
						service_charge: {
							required: true
						}
			
					},
					messages: {
						task_cat: {
								required: "Category is required"
							},
							task_sub_cat: {
								required: "Sub category is required"
							},
							govfee: {
								required: "fee is required"
							},
							service_charge: {
								required: "service charge is required"
							}
			
						},
						errorElement: "em",
						errorPlacement: function(error, element) {
							error.addClass("help-block");
							error.insertAfter(element);
						},
						highlight: function(element, errorClass, validClass) {
							$(element).addClass("has-error").removeClass("has-success");
						},
						unhighlight: function(element, errorClass, validClass) {
							$(element).addClass("has-success").removeClass("has-error");
						},
					});
			
					$('form#addTaskquoteFrm').bind('submit',function(){
						//alert('hi');
						if (addTaskquoteFrm.form()) {
							$('#addTaskLoader').show();
							var formQuoteData = $('form#addTaskquoteFrm').serialize();
							var quoteId = $("#quoteId").val();
							if(quoteId =="") {
								var suburl = base_url + '/save_quote';
							}else{
								var suburl = base_url + '/update_quote';
							}
							$.ajax({
								url: suburl,
								type:'POST',
								data:formQuoteData,
								success: function(response) {
									$('#addTaskLoader').hide();
									if (response.class == "succ") {
										showToast(response.message, "success");
										setTimeout(function () {
											window.location.href = response.redirect;
										}, 2000); // Wait 2 seconds before redirecting
									} else {
										$.each(response, function (idx, obj) {
											showToast(obj, "error");
										});
									}
								}
							});
						}
			});
		//	End taskQuote

		//Delete taskQuote
		// $('#del_quot').on('click', function () {
		// 	if (deleteId) {
		// 		$.ajaxSetup({
		// 			headers: {
		// 				"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
		// 			},
		// 		});
		
		// 		$.ajax({
		// 			url: '/delQuote',
		// 			type: 'POST',
		// 			data: { id: deleteId },
		// 			success: function (response) {
		// 				showToast(response.message, response.class); // Show toast
		// 				setTimeout(function () {
		// 					window.location.href = response.redirect;
		// 				}, 2000);
		// 			},
		// 			error: function (xhr) {
		// 				showToast("Error deleting Quote!", 'error');
		// 			}
		// 		});
		// 	}
		// });
		

		

		//Start add employee
		var addEmployeeFrm = $('#addEmployeeFrm').validate({
		rules: {
			name: {
				required: true
			},
			phone: {
				required: true,
				minlength: 10,
				maxlength: 10,
				number: true
			},
			email: {
				required: true,
				email:true
			},
			password: {
				required: true,
				minlength: 6
			},
			conf_password: {
				required: true,
				minlength: 6,
				equalTo: "#password"
			},
			dept_id: {
				required: true,
			},
			desig_id: {
				required: true,
			},
			dob: {
				required: true,
			},
			gender: {
				required: true,
			},
			qualification: {
				required: true,
			},
			c_addr_lineone: {
				required: true
			},
			c_emp_state: {
				required: true
			},
			c_emp_city: {
				required: true
			},
			c_emp_pincode: {
				required: true,
				number: true
			},
			p_addr_lineone: {
				required: true
			},
			p_emp_state: {
				required: true
			},
			p_emp_city: {
				required: true
			},
			p_emp_pincode: {
				required: true,
				number: true
			},
			
			basic_sal: {
				required: true,
				number: true
			},
			hra: {
				required: true,
				number: true
			},
			convayance: {
				required: true,
				number: true
			},
			special_bonus: {
				required: true,
				number: true
			},
			provident_fund: {
				required: true,
				number: true
			},
			esi: {
				required: true,
				number: true
			},
			loan: {
				required: true,
				number: true
			},
			ptax: {
				required: true,
				number: true
			},
			tds: {
				required: true,
				number: true
			},
			total_deduction: {
				required: true,
				number: true
			},
			total_addition: {
				required: true,
				number: true
			},
			net_sal: {
				required: true,
				number: true
			},
			net_sal_word: {
				required: true,
			},
			emp_permission: {
				required: true
			},

		},
		messages: {
				name: {
					required: "Name is required"
				},
				phone: {
					required: "Phone is required"
				},
				email: {
					required: "Email is required"
				},
				password: {
					required: "Password is required"
				},
				conf_password: {
					required: "Confirm password is required"
				},
				dept_id: {
					required: "Dept. is required"
				},
				desig_id: {
					required: "Designation is required"
				},
				dob: {
					required: "DOB is required"
				},
				gender: {
					required: "Gender is required"
				},
				qualification: {
					required: "Qualification is required"
				},
				c_addr_lineone: {
					required: "Address is required"
				},
				c_emp_state: {
					required: "State is required"
				},
				c_emp_city: {
					required: "City is required"
				},
				c_emp_pincode: {
					required: "Pincode is required"
				},
				p_addr_lineone: {
					required: "Address is required"
				},
				p_emp_state: {
					required: "State is required"
				},
				p_emp_city: {
					required: "City is required"
				},
				p_emp_pincode: {
					required: "Pincode is required"
				},
				
				basic_sal: {
					required: "Salary basic is required"
				},
				hra: {
					required: "HRA is required"
				},
				convayance: {
					required: "Convayance is required"
				},
				special_bonus: {
					required: "Bonus is required"
				},
				provident_fund: {
					required: "PF is required"
				},
				esi: {
					required: "ESI is required"
				},
				loan: {
					required: "Loan is required"
				},
				ptax: {
					required: "PTAX is required"
				},
				tds: {
					required: "TDS is required"
				},
				total_deduction: {
					required: "Total deduction is required"
				},
				total_addition: {
					required: "Total addition is required"
				},
				net_sal: {
					required: "Net salary is required"
				},
				net_sal_word: {
					required: "Salary in word is required"
				},
				

			},
			errorElement: "em",
			errorPlacement: function(error, element) {
				error.addClass("help-block");
				error.insertAfter(element);
			},
			highlight: function(element, errorClass, validClass) {
				$(element).addClass("has-error").removeClass("has-success");
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).addClass("has-success").removeClass("has-error");
			},
		});

		$('form#addEmployeeFrm').bind('submit',function(){
			if (addEmployeeFrm.form()) {
				$('#addEmployeeLoader').show();
				var formEmployeeData = $('form#addEmployeeFrm').serialize();
				var empId = $("#empId").val();
				if(empId =="") {
					var suburl = base_url + '/save_employee';
				}else{
					var suburl = base_url + '/update_employee';
				}
				$.ajax({
					url: suburl,
					type:'POST',
					data:formEmployeeData,
					success: function(response) {
						$('#addEmployeeLoader').hide();
						if (response.class=="succ") {
							$("#addEmployeeFrm .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
							window.location.href=response.redirect;
						} else {
							$.each(response, function(idx, obj) {
								$("#addEmployeeFrm .message-container").html('<div class="err">'+obj+'</div>');
							});
						}
					}
				});
			}
		});
		
		$('.emp_active').click(function() {
			var status = $(this).data('stat');
			var emp_id = $(this).data('id');
			alert(emp_id);
			$.ajax({
				type: "GET",
				dataType: "json",
				url: base_url + '/changeEmployeeStatus',
				data: {'status': status, 'id': emp_id},
				success: function(data){
				  //console.log(data.success)
				  window.location.href=data.redirect;

				}
			});
		});
		
		$('.empdelete').click(function() {
			var emp_id = $(this).data('id');
			$('#del_emp').click(function() {
				$.ajax({
					type: "GET",
					dataType: "json",
					url: base_url + '/delEmployee',
					data: {'id': emp_id},
					success: function(data){
					  //console.log(data.success)
					  window.location.href=data.redirect;

					}
				});
			});
		});
		
		var addDepertmentFrm = $('#addDepertmentFrm').validate({
		rules: {
			dept_name: {
				required: true
			},
		},
		messages: {
				dept_name: {
					required: "Deptertment is required"
				},
			},
			errorElement: "em",
			errorPlacement: function(error, element) {
				error.addClass("help-block");
				error.insertAfter(element);
			},
			highlight: function(element, errorClass, validClass) {
				$(element).addClass("has-error").removeClass("has-success");
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).addClass("has-success").removeClass("has-error");
			},
		});

		$('form#addDepertmentFrm').bind('submit',function(){
			if (addDepertmentFrm.form()) {
				$('#addEmployeeLoader').show();
				var formData = $('form#addDepertmentFrm').serialize();
				var suburl = base_url + '/add_depertment';
				$.ajax({
					url: suburl,
					type:'POST',
					data:formData,
					success: function(response) {
						$('#addEmployeeLoader').hide();
						if (response.class=="succ") {
							$("#addDepertmentFrm .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
							window.location.reload();
						} else {
							$.each(response, function(idx, obj) {
								$("#addDepertmentFrm .message-container").html('<div class="err">'+obj+'</div>');
							});
						}
					}
				});
			}
		});
		
		var addDesignationFrm = $('#addDesignationFrm').validate({
		rules: {
			deptName: {
				required: true
			},
			designation_name: {
				required: true
			},
		},
		messages: {
				deptName: {
					required: "Deptertment is required"
				},
				designation_name: {
					required: "Designation is required"
				},
			},
			errorElement: "em",
			errorPlacement: function(error, element) {
				error.addClass("help-block");
				error.insertAfter(element);
			},
			highlight: function(element, errorClass, validClass) {
				$(element).addClass("has-error").removeClass("has-success");
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).addClass("has-success").removeClass("has-error");
			},
		});

		$('form#addDesignationFrm').bind('submit',function(){
			if (addDesignationFrm.form()) {
				$('#addEmployeeLoader').show();
				var formData = {
						 designation_name : $("#addDesignationFrm #designation_name").val(),
						 dept_id : $("#deptName option:selected").val(),
					}
				var suburl = base_url + '/add_designation';
				$.ajax({
					url: suburl,
					type:'POST',
					data:formData,
					success: function(response) {
						$('#addEmployeeLoader').hide();
						if (response.class=="succ") {
							$("#addDesignationFrm .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
							window.location.reload();
						} else {
							$.each(response, function(idx, obj) {
								$("#addDesignationFrm .message-container").html('<div class="err">'+obj+'</div>');
							});
						}
					}
				});
			}
		});
		//End add employee
		
		//start reminder
		$('#filterApplyBtn').click(function () {
			var task_category = $("#taskCategorySelect option:selected").val();
			if(task_category == ""){
				alert("Please select task category");
				$('#compTaskLists').html("");
			}else{
				$('#compTaskLists').html("");
				$('#addReminderLoader').show();
				$.ajax({
					method: "POST",
					url: base_url + '/company_task_list',
					data: {'task_category': task_category},
					datatype: 'json',
					success: function(result){
						$('#addReminderLoader').hide();
						$('#compTaskLists').html(result);
					}
				});
			}
        });
		
		var bulkMessageFrm = $('#bulkMessageFrm').validate({
			rules: {
				
			},
			messages: {
					
				},
				errorElement: "em",
				errorPlacement: function(error, element) {
					error.addClass("help-block");
					error.insertAfter(element);
				},
				highlight: function(element, errorClass, validClass) {
					$(element).addClass("has-error").removeClass("has-success");
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).addClass("has-success").removeClass("has-error");
				},
		});

		$('form#bulkMessageFrm').bind('submit',function(){
			if (bulkMessageFrm.form()) {
				$('#addReminderLoader').show();
				var formData = {
						 reminderText : $("#bulkMessageFrm #reminderText").val(),
						 task_category : $("#taskCategorySelect option:selected").val(),
					}
				var suburl = base_url + '/send_bulk_message';
				$.ajax({
					url: suburl,
					type:'POST',
					data:formData,
					success: function(response) {
						$('#addReminderLoader').hide();
						if (response.class=="succ") {
							$("#bulkMessageFrm .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
							//window.location.reload();
						} else {
							$.each(response, function(idx, obj) {
								$("#bulkMessageFrm .message-container").html('<div class="err">'+obj+'</div>');
							});
						}
					}
				});
			}
		});
		//end reminder
		
		//Start for statutory
		
		var addStatutoryFrm = $('#addStatutoryFrm').validate({
			rules: {
				compId: {
					required: true
				},
				statutory_doc: {
					required: true
				},
				statutory_due_date: {
					required: true,
					date: true
				},
				statutory_msg: {
					required: true,
					minlength: 3,
				},

				// ✅ NEW FIELD (only required if "Other" selected)
				// other_statutory_doc: {
				// 	required: function () {
				// 		return $('#statutory_doc').val() === 'Other';
				// 	},
				// 	minlength: 3
				// }
			},

			messages: {
				compId: {
					required: "Company is required"
				},
				statutory_doc: {
					required: "Document is required"
				},
				statutory_due_date: {
					required: "Due date is required"
				},
				statutory_msg: {
					required: "Message is required",
					minlength: "Enter at least 3 characters"
				},

				// ✅ Message for Other
				other_statutory_doc: {
					required: "Please enter compliance name",
					minlength: "Enter at least 3 characters"
				}
			},

			errorElement: "em",
			errorPlacement: function(error, element) {
				error.addClass("help-block");
				error.insertAfter(element);
			},
			highlight: function(element) {
				$(element).addClass("has-error").removeClass("has-success");
			},
			unhighlight: function(element) {
				$(element).addClass("has-success").removeClass("has-error");
			}
		});

			$('form#addStatutoryFrm').bind('submit',function(){

				if (addStatutoryFrm.form()) {
					$('#statutoryLoader').show();
					var eId = $("#eId").val();
					if(eId == ""){
						var surl = base_url + '/save_statutory';
					}else{
						var surl = base_url + '/update_statutory';
					}
					var expensesData = $('form#addStatutoryFrm').serialize();
					$.ajax({
						url: surl,
						type:'POST',
						data:expensesData,
						success: function(response) {
							$('#statutoryLoader').hide();
							if (response.class=="succ") {
								$("#addStatutoryFrm .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
								window.location.href=response.redirect;
							} else {								
								$.each(response, function(idx, obj) {
									$("#addStatutoryFrm .message-container").html('<div class="err">'+obj+'</div>');
								});
							}
						}
					});


				}
			});

		//End for statutory
					

});

	function clearNoti(el)
	{

		var to_uid = el;
		var base_url = $("#base_url").val();
		if(to_uid > 0){
			$.ajax({
				method: "POST",
				//dataType: "json",
				url: base_url + '/clearNotification',
				data: {'to_uid': to_uid},
				success: function(result){
				 $(".notiCount").html(0);
				 $(".notification-list").html('');
				}
			});	
		}
	}
	
	function getDesignationOptions(el)
	{
		var base_url = $("#base_url").val();
		var id = el.value;
		$.ajaxSetup({
			  headers: {
				  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
			  }
		});
	  $.ajax({
		url: base_url + "/getDesignationOptions?"+id,
		dataType: "json",
		//type: "post",
		data: {id: id},
		success: function( data ) {
		  $("#desig_id").empty();
		  var str ='<option value="">Select</option>';
		  $.each(data, function (idx, item) {
				str +='<option value="' + item.id + '">' + item.name + '</option>';
			});
		  $("#desig_id").html(str);

		}

	  });
	}
	
	function changeState(el)
	{
		var base_url = $("#base_url").val();
		var id = el.value;

		 $.ajaxSetup({
			  headers: {
				  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
			  }
		  });
	  $.ajax({
		url: base_url + "/getCity?"+id,
		dataType: "json",
		//type: "post",
		data: {id: id},
		success: function( data ) {
		  $("#city").empty();
		  var str ='<option value="">Select City</option>';
		  $.each(data, function (idx, item) {
				str +='<option value="' + item.id + '">' + item.name + '</option>';
			});
		  $("#city").html(str);

		}

	  });
	}

	function changeState_curr(el)
	{
		var base_url = $("#base_url").val();
		var id = el.value;

		 $.ajaxSetup({
			  headers: {
				  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
			  }
		  });
	  $.ajax({
		url: base_url + "/getCity?"+id,
		dataType: "json",
		//type: "post",
		data: {id: id},
		success: function( data ) {
		  $("#curr_city").empty();
		  var str ='<option value="">Select City</option>';
		  $.each(data, function (idx, item) {
				str +='<option value="' + item.id + '">' + item.name + '</option>';
			});
		  $("#curr_city").html(str);

		}

	  });
	}

	
	

	function getUserAccess(el) {
		var base_url = $("#base_url").val();
		var select_typee = el.value;
		var customer_type = $("#customer_type").val();
		var reminder_type = $("#reminder_type").val();
	
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
			},
		});
	
		$.ajax({
			url: base_url + "/userListsAccess",
			dataType: "json",
			type: "post",
			data: { sendData: 'getData', customer_type: customer_type, select_type: select_typee, },
			success: function (data) {
				// Clear previous toasts
				$("#toastContainer").empty();
		
				let str = "";
				$.each(data.matched_names, function (idx, item) {
					str += `
							<div class="toast align-items-center border-0 show user-toast">
								<div class="d-flex">
									<div class="toast-body">
										${item.comp_name}
										<input type="hidden" name="userId[]" value="${item.userId}">
									</div>
									<button type="button" class="btn-close me-2 m-auto remove-user" data-bs-dismiss="toast" aria-label="Close"></button>
								</div>
							</div>
						`;
				});
		
				// Append to the toast container
				$("#toastContainer").html(str);
				$("#setReminderFrmCA input[name='userId[]']").remove();
				$("#setReminderFrmCA").append($("#toastContainer input[name='userId[]']"));
			}
		});
	}
	


	function getUserAccessByStatus(el) {
        var base_url = $("#base_url").val();
        var customer_type = el.value;
        var select_typee = $("#user_type").val();
		var reminder_type = $("#reminder_type").val();


        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            url: base_url + "/userListsAccess",
            dataType: "json",
            type: "post",
            data: { sendData: 'getData' ,customer_type: customer_type, select_type: select_typee },
            success: function (data) {
                //console.log(data);
				$("#toastContainer").empty();
		
				let str = "";
				$.each(data.matched_names, function (idx, item) {
					str += `
							<div class="toast align-items-center border-0 show user-toast">
								<div class="d-flex">
									<div class="toast-body">
										${item.comp_name}
										<input type="hidden" name="userId[]" value="${item.userId}">
									</div>
									<button type="button" class="btn-close me-2 m-auto remove-user" data-bs-dismiss="toast" aria-label="Close"></button>
								</div>
							</div>
						`;
				});
		
				// Append to the toast container
				$("#toastContainer").html(str);
				$("#setReminderFrmCA input[name='userId[]']").remove();
				$("#setReminderFrmCA").append($("#toastContainer input[name='userId[]']"));
            },
        });
    }
	
	$(document).on('click', '.remove-user', function () {
		$(this).closest('.user-toast').remove();
	});


 	
