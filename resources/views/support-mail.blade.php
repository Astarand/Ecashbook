@extends('App.Layout')

@section('container')
<div class="pc-content">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow">

                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Get Help / Contact Support</h5>
                    <a href="{{ route('help-center') }}" class="btn btn-light btn-sm">
                        <i class="ti ti-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('support.send') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Message</label>
                            <textarea name="message" rows="5" class="form-control" required></textarea>
                        </div>
						
						{{-- Human verification --}}
						<div class="mb-3">
							<label class="form-label">
								Are you a human? <small class="text-muted">(Solve this)</small>
							</label>
							<div class="d-flex align-items-center">
								<span class="badge bg-light text-dark p-2 me-2">
									{{ session('human_question') }}
								</span>
								<input type="text" name="human_answer" class="form-control" required>
							</div>
						</div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('help-center') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Back
                            </a>

                            <button class="btn btn-primary">
                                <i class="ti ti-send"></i> Send to Support
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
