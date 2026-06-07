<div class="row">
    <div class="col-12">
        <div class="table-responsive mb-2">
            
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product / Service</th>
                        <th>HSN /SAC</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Total Amount</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                   <?php 
						$i = 0;
						$taxableAmt = 0;
						$totalDisc = 0;
						$totalTax = 0;
						$totalAmount = 0;
						$special_discount_amount = 0;
						$totalGovPay = 0;
						$totalSerPay = 0;
						$gst_trans = ""; 
					?>
                    @foreach ($quotations_values as $value)
                    <tr>
                        <td>{{ $i = $i+1 }}</td>
                        <td>
                            
                                {{ $value->item_name }}
                            
                        </td>
                        <td>{{ ($value->sac_code != "") ? $value->sac_code : $value->hsn_code }}</td>
                        <td>
                            <input type="text" name="quantity" id="quantity_{{ $value->id }}" data-id="{{ $value->id }}" 
                                data-sid="{{ $value->sid }}" data-prod_id="{{ $value->prod_id }}" 
                                onChange="changeQuotationQuantity(this)" class="form-control quantity" value="{{ $value->quantity }}" 
                                onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                        <td>
                            <input type="text" name="rate" id="rate_{{ $value->id }}" data-id="{{ $value->id }}" 
                                data-sid="{{ $value->sid }}" onChange="changeQuotationRate(this)" class="form-control rate" 
                                value="{{ $value->rate }}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                        <td>{{ $value->disc_amt }}</td>
                        <td>₹{{ $value->amount }}</td>
                        <td class="d-flex align-items-center">
                            <!--<a href="javascript:void(0);" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" 
                            data-rate="{{ $value->rate }}" data-discamt="{{ $value->disc_amt }}" data-taxtype="{{ $value->tax_type }}" 
                            onclick="editQuotationItem(this)" class="avtar avtar-s btn-link-primary btn-pc-default" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit"><span><i class="ti ti-pencil f-20"></i></span></a>-->
                            <a href="javascript:void(0);" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" 
                            onclick="delQuotationItem(this)" class="avtar avtar-s btn-link-danger btn-pc-default" 
                            data-bs-target="#delete_discount"><span><i class="ti ti-trash f-20"></i></span></a>
                        </td>
                    </tr>
                    <?php 
                        $taxableAmt += ($value->rate * $value->quantity);
                        $totalDisc += $value->disc_amt;
                        $totalTax += $value->tax_amt;
                        $totalAmount += $value->amount;
						$totalGovPay += $value->gov_pay; 
						$totalSerPay += $value->ser_pay; 
                        $special_discount = $value->special_discount;
                        $special_discount_amount = $value->special_discount_amount;
                        $special_discount_type = $value->special_discount_type;
						$gst_trans = $value->gst_trans;
                    ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
        <hr class="mb-3">
        <div class="col-12">
            <div class="invoice-total ms-auto">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Discount On Grand Total</label>
                            
                            <input type="text" name="discount_amount" id="discount_amount" class="form-control" value="{{ $value->special_discount_amount }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1 text-start">Sub Total :</p>
                    </div>
                    <div class="col-6">
                        <p class="f-w-600 mb-1 text-end">₹<?php echo $totalAmount ?></p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1 text-start">Discount :</p>
                    </div>
                    <div class="col-6">
                        <p class="f-w-600 mb-1 text-end text-success">₹<?php echo $totalDisc; ?></p>
                    </div>
                    @if($gst_trans == 'intrastate')
						<div class="col-6">
							<p class="text-muted mb-1 text-start">CGST :</p>
						</div>
						<div class="col-6">
							<p class="f-w-600 mb-1 text-end">₹{{ number_format($totalTax/2, 2) }}</p>
						</div>

						<div class="col-6">
							<p class="text-muted mb-1 text-start">SGST :</p>
						</div>
						<div class="col-6">
							<p class="f-w-600 mb-1 text-end">₹{{ number_format($totalTax/2, 2) }}</p>
						</div>
					@else
						<div class="col-6">
							<p class="text-muted mb-1 text-start">IGST :</p>
						</div>
						<div class="col-6">
							<p class="f-w-600 mb-1 text-end">₹{{ number_format($totalTax, 2) }}</p>
						</div>
					@endif
					<div class="col-6">
						<p class="text-muted mb-1 text-start">Government Fees :</p>
					</div>
					<div class="col-6">
						<p class="f-w-600 mb-1 text-end">₹{{ number_format($totalGovPay, 2) }}</p>
					</div>

					<div class="col-6">
						<p class="text-muted mb-1 text-start">Service Charges :</p>
					</div>
					<div class="col-6">
						<p class="f-w-600 mb-1 text-end">₹{{ number_format($totalSerPay, 2) }}</p>
					</div>
                    <div class="col-6">
                        <p class="f-w-600 mb-1 text-start">Grand Total :</p>
                    </div>
                    <div class="col-6">
                        <p class="f-w-600 mb-1 text-end" id="grand_total_amount">₹{{ number_format($totalAmount + $totalTax + $totalGovPay + $totalSerPay, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#discount_amount').on('keyup', function() {
            var discount = $(this).val();
            var grand_total = parseFloat($('#grand_total_amount').text().replace('₹', ''));
            var new_total = grand_total - discount;
            $('#grand_total_amount').text('₹' + new_total.toFixed(2));
        });
    });
	
</script>

        


