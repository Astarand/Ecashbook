@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Payroll Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">HR Letter</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">HR Letter</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-body table-card">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Send Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($letters as $index => $letter)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $letter->subject }}</td>
                                <td>
                                    @if ($letter->sent_at)
                                    {{ \Carbon\Carbon::parse($letter->sent_at)->format('d-m-Y') }}
                                    @else
                                    <span class="text-muted">Not Sent</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-success viewLetterBtn"
                                        data-subject="{{ $letter->subject }}" data-content="{{ $letter->content }}">
                                        <i class="ti ti-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No HR letters available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- ===== View Letter Modal ===== -->
<div class="modal fade" id="viewLetterModal" tabindex="-1" aria-labelledby="viewLetterLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewLetterLabel"> <i class="ti ti-file-text me-2"></i>HR Letter Preview
                </h5> <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="modalLetterSubject" class="fw-bold text-center mb-4"></h4>
                <div id="modalLetterContent" style="line-height: 1.7; font-size: 15px;"></div>
            </div>
            <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <i
                        class="ti ti-x me-1"></i>Close </button> </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')

<script>
    document.addEventListener("DOMContentLoaded", function () {
         document.querySelectorAll(".viewLetterBtn").forEach(button =>{ 
            button.addEventListener("click", function () { 
                const subject = this.getAttribute("data-subject"); 
                const content = this.getAttribute("data-content"); 
                document.getElementById("modalLetterSubject").innerText = subject; 
                document.getElementById("modalLetterContent").innerHTML = content; 
                const modal = new bootstrap.Modal(document.getElementById("viewLetterModal")); modal.show(); 
            }); 
        }); 
    }); 
</script>

@endsection