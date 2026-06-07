@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item border rounded shadow-sm mb-3">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button text-white shadow-none rounded-top" style="background-color: #7a65cd;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Call - 10:00 AM – 6:00 PM (IST)
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body bg-white">
                            <strong>+91-{{ $supportMobile }}</strong>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border rounded shadow-sm mb-3">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button text-white shadow-none rounded-top collapsed" style="background-color: #7a65cd;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Email Support for detailed issues - 10:00 AM – 6:00 PM (IST)
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body bg-white">
                            <strong>{{ $supportEmail }}</strong>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border rounded shadow-sm">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button text-white shadow-none rounded-top collapsed" style="background-color: #7a65cd;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Chat for faster help - 10:00 AM – 6:00 PM (IST)
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
						<div class="accordion-body bg-white">
							<h6 class="mb-3 text-primary">How to Fill a Support Ticket?</h6>

							<p class="mb-2">
								<strong>Subject</strong> – A short title of your issue  
								<br>
								<span class="text-muted">
									Example: <em>GST Return Error, Login Problem, Invoice Not Generating</em>
								</span>
							</p>

							<p class="mb-2">
								<strong>Message</strong> – Explain your problem clearly. Please include:
							</p>

							<ul class="ps-3 mb-3">
								<li>What you were trying to do?</li>
								<li>What error you received?</li>
								<li>Date &amp; time of the issue (if possible)</li>
							</ul>

							<div class="alert alert-light border">
								Providing clear details helps our support team resolve your issue faster.
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gap-4 mt-5">
        <h5 class="my-3 text-center">Didn't Find your Quries? You can connect with us for more support!</h5>
        <div class="col-sm-10 mx-auto">
            <div class="row text-center">
                <!-- Call Support -->
                <div class="col-sm-4">
                    <div class="card shadow-sm border-0 text-center">
                        <div class="card-body py-4">
                            <i class="ph-duotone ph-phone-call fs-2"></i>
                            <h5 class="mt-3 fw-bold">Call</h5>
                            <p class="text-muted mb-2">Speak with our support team.</p>
                            <a href="tel:+91{{ $supportMobile }}" class="btn btn-primary btn-sm px-4">Call Now</a>
                        </div>
                    </div>
                </div>

                <!-- Email Support -->
                <div class="col-sm-4">
                    <div class="card shadow-sm border-0 text-center">
                        <div class="card-body py-4">
                            <i class="ph-duotone ph-envelope-open fs-2"></i>
                            <h5 class="mt-3 fw-bold">Email</h5>
                            <p class="text-muted mb-2">Get help via email support.</p>
                            <a href="{{route ('support-mail')}}" class="btn btn-primary btn-sm px-4">Email Us</a>
                        </div>
                    </div>
                </div>

                <!-- Chat Support -->
                <div class="col-sm-4">
                    <div class="card shadow-sm border-0 text-center">
                        <div class="card-body py-4">
                            <i class="ph-duotone ph-chat-circle-dots fs-2"></i>
                            <h5 class="mt-3 fw-bold">Chat</h5>
                            <p class="text-muted mb-2">Chat with our support team.</p>
							@if(Auth::user()->u_type == 1 || Auth::user()->u_type == 4)
                            <a href="{{ route('admin.ca-ticket') }}" class="btn btn-primary btn-sm px-4">Start Chat</a>
							@else
                            <a href="{{ route('admin.customer-ticket') }}" class="btn btn-primary btn-sm px-4">Start Chat</a>
							@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection