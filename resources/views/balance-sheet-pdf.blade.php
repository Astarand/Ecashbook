<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>


@page{
    margin:120px 25px 70px 25px;
}

body{
    font-family: DejaVu Sans;
    font-size:11px;
    color:#000;
    line-height:1.4;
}

header{
    position:fixed;
    top:-105px;
    left:0;
    right:0;
}

footer{
    position:fixed;
    bottom:-45px;
    left:0;
    right:0;
    font-size:10px;
}

table{
    width:100%;
    border-collapse:collapse;
    border-spacing:0;
}

table,
table th,
table td{
    border:1px solid #000 !important;
}

th{
    background:#F2F2F2;
    font-weight:bold;
    text-align:center;
}

th,td{
    padding:6px;
    vertical-align:middle;
}

.no-border,
.no-border tr,
.no-border td,
.no-border th{
    border:none !important;
}

.text-center{
    text-align:center;
}

.text-right{
    text-align:right;
}

.section-title{
    background:#E8E8E8;
    font-weight:bold;
    padding:8px;
    border:1px solid #000;
    margin-top:15px;
    margin-bottom:5px;
}

.page-number:after{
    content:counter(page);
}

/* Apply same border to injected HTML */
.pdf-content table,
.pdf-content table td,
.pdf-content table th{
    border:1px solid #000 !important;
    border-collapse:collapse !important;
}

.pdf-content td,
.pdf-content th{
    padding:6px;
}


	</style>
</head>

<!-- ================= Header Every page ================= -->
<header>
	<table class="no-border">
		<tr>
			<td width="15%">
				@if(!empty($company->company_logo))
				<img src="{{ public_path('storage/profile/'.$company->company_logo) }}"
				style="height:70px;">
				@endif
			</td>
			<td width="85%" class="text-center">
				<h2 style="margin:0">{{ $company->comp_name }}</h2>
				<div>{{ $company->comp_bill_addone }}</div>
				<div>GSTIN :{{ $company->gst_no ?? '-' }}</div>
				<h3 style="margin:6px 0;">BALANCE SHEET</h3>
			</td>
		</tr>
	</table>

</header>

<body>

<div class="pdf-content">
{!! $html !!}
</div>

@if($details)
	@if(!empty($result))

	<div style="page-break-before:always;"></div>

	<h3>Share Holder Fund Details</h3>
		@foreach($result as $title => $rows)
			<div class="section-title">

			{{ $title }}

			</div>
			<table width="100%" border="1" cellspacing="0" cellpadding="5">
				@if($title == 'Share Capital')
					<thead>
						<tr>
							<th>Share Type</th>
							<th>Class of Share</th>
							<th>Shares Issued</th>
							<th>Face Value</th>
							<th>Total Amount</th>
						</tr>
					</thead>
					<tbody>
						@foreach($rows as $row)
						<tr>
							<td>{{ $row->share_holder_type }}</td>
							<td>{{ $row->class_of_shares }}</td>
							<td>{{ $row->shares_issued }}</td>
							<td align="right">{{ number_format($row->face_value_per_share,2) }}</td>
							<td align="right">{{ number_format($row->total_amount,2) }}</td>
						</tr>
						@endforeach
					</tbody>
				@else
					<thead>
						<tr>
							<th>Reserve Type</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
						@foreach($rows as $row)
						<tr>
							<td>{{ ucwords(str_replace('_',' ', $row->reserves_surplus_type)) }}</td>
							<td align="right">
								{{
									number_format(
										$row->opening_balance
										?: ($row->transfer_amount
										?: $row->total_dividend_amount),
										2
									)
								}}
							</td>
						</tr>
						@endforeach
					</tbody>
				@endif
			</table>
			<br>
		@endforeach
	@endif
@endif


<!-- ================= CA SIGNATURE (ONLY ON LAST PAGE) ================= -->
@if(!empty($ca))
<div style="margin-top:70px;">
    <table class="no-border">
        <tr>
            <td width="60%">

            </td>
            <td width="40%" class="text-center">
                @if(!empty($ca->signature_doc))
                    <img src="{{ public_path('storage/profile/'.$ca->signature_doc) }}" style="height:70px;">
                @endif

                <br>
                <strong>{{ $ca->comp_name ?? '' }}</strong>
                <br>
                Chartered Accountant
            </td>
        </tr>
    </table>
</div>
@endif

</body>

<!-- ================= Footer Every page ================= -->
	<footer>
		<hr>
		<table class="no-border">
			<tr>
			<td>
				Generated : {{ date('d-m-Y h:i A') }}
			</td>
			<td align="right">
				Page <span class="page-number"></span>
			</td>
			</tr>
		</table>
	</footer>
</html>
