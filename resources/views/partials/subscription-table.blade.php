
@foreach ($data as $key => $row)
    <tr>
        <td>{{ $data->firstItem() + $key }}</td>
        <td>{{ $row->name }}</td>
        <td>{{ $row->package_name }}</td>
        <td>{{ $row->plan_type }}</td>
        <td>₹{{ number_format($row->paid_amount) }}</td>
        <td>₹{{ number_format($row->ca_amt) }}</td>
        <td>{{ \Carbon\Carbon::parse($row->start_at)->format('d-m-Y') }}</td>
        <td>{{ \Carbon\Carbon::parse($row->end_at)->format('d-m-Y') }}</td>
        <td>{{ $row->transaction_id }}</td>
		<td>
			<a href="#" class="view-payment-btn" data-bs-toggle="modal" data-bs-target="#paymentDetailsModal" data-id="{{ $row->id }}">
				<i class="ti ti-eye f-18"></i>
			</a>
		</td>
    </tr>
@endforeach

