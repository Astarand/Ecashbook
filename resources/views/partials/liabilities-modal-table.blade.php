<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                {{ isset($row->added_date) 
                    ? date('d-m-Y', strtotime($row->added_date)) 
                    : '-' }}
            </td>
            <td>
                ₹ {{ number_format($row->amount, 2) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-end">
    {{ $data->links('pagination::bootstrap-5') }}
</div>
