@extends('App.Layout')

@section('container')

<style>
    .help-hero-premium {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        border-radius: 12px;
        color: #fff;
        padding: 30px;
        position: relative;
        overflow: hidden;
    }
    .help-hero-premium::after {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 140px;
        height: 140px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }
    .faq-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }
    .faq-title {
        color: #1e293b;
        font-weight: 700;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 12px;
    }
    .contact-item-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: all 0.2s;
    }
    .contact-item-box:hover {
        border-color: #4f46e5;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .contact-icon-circle {
        width: 44px;
        height: 44px;
        background: rgba(79, 70, 229, 0.08);
        color: #4f46e5;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .accordion-help .accordion-item {
        border: 1px solid #e2e8f0;
        border-radius: 8px !important;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .accordion-help .accordion-button {
        font-weight: 600;
        color: #334155;
        background: #fff;
    }
    .accordion-help .accordion-button:not(.collapsed) {
        background: rgba(79, 70, 229, 0.03);
        color: #4f46e5;
        box-shadow: none;
    }
</style>

<div class="pc-content">
    <!-- Hero Banner -->
    <div class="help-hero-premium mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="avtar avtar-lg btn-light-primary rounded-circle bg-white bg-opacity-20 text-white">
                <i class="ti ti-headset f-28"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold mb-1">Help & Support Center</h4>
                <p class="text-white text-opacity-80 mb-0 f-13">Need assistance with your tasks, filings, or payments? Find quick solutions or reach out to us.</p>
            </div>
        </div>
    </div>

    <!-- 2 Column Layout (FAQ vs Contact Desk) -->
    <div class="row g-4">
        <!-- Left Column: FAQ & Ticket Instructions -->
        <div class="col-lg-8 col-12">
            <div class="card shadow-sm border-0 faq-card">
                <div class="card-body p-4">
                    <h5 class="faq-title mb-4"><i class="ti ti-help me-2 text-primary"></i>Frequently Asked Questions</h5>
                    
                    <div class="accordion accordion-help" id="helpCenterAccordion">
                        <!-- How to Fill a Support Ticket -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTicket">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTicket" aria-expanded="true" aria-controls="collapseTicket">
                                    <i class="ti ti-ticket me-2 text-primary"></i> How do I create a Support Ticket?
                                </button>
                            </h2>
                            <div id="collapseTicket" class="accordion-collapse collapse show" aria-labelledby="headingTicket" data-bs-parent="#helpCenterAccordion">
                                <div class="accordion-body bg-white text-muted f-14" style="line-height: 1.6;">
                                    <h6 class="text-dark fw-bold mb-2">To get faster resolutions, follow this structure:</h6>
                                    <div class="mb-3">
                                        <span class="text-dark fw-semibold">1. Subject Line:</span> A brief, descriptive title.
                                        <p class="mb-0 bg-light p-2 rounded mt-1 f-13 text-secondary">Example: <em>"GST Filing Portal Error", "Invoice PDF Generation failed"</em></p>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-dark fw-semibold">2. Message Details:</span> Clearly explain what you were trying to do, the error message, and the date/time of the event.
                                    </div>
                                    <div class="alert alert-light-primary mb-0 border">
                                        <i class="ti ti-info-circle me-1"></i> Supplying complete details helps our desk resolve your issues on the first reply.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resetting Password / Account Help -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAcc">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAcc" aria-expanded="false" aria-controls="collapseAcc">
                                    <i class="ti ti-lock me-2 text-primary"></i> How can I reset my password?
                                </button>
                            </h2>
                            <div id="collapseAcc" class="accordion-collapse collapse" aria-labelledby="headingAcc" data-bs-parent="#helpCenterAccordion">
                                <div class="accordion-body bg-white text-muted f-14">
                                    You can update your password by navigating to the **Profile / Change Password** section in your dashboard. If you've been locked out, click the "Forgot Password" link on the login page to receive an OTP reset email.
                                </div>
                            </div>
                        </div>

                        <!-- Payment Issues -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPay">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePay" aria-expanded="false" aria-controls="collapsePay">
                                    <i class="ti ti-credit-card me-2 text-primary"></i> Payment processing and invoice generation
                                </button>
                            </h2>
                            <div id="collapsePay" class="accordion-collapse collapse" aria-labelledby="headingPay" data-bs-parent="#helpCenterAccordion">
                                <div class="accordion-body bg-white text-muted f-14">
                                    All payments processed on E-Cashbook are updated instantly. Invoices are generated automatically and sent to your registered email, or can be downloaded under the **Payments / Billing** history tab.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Contact Desk Channels -->
        <div class="col-lg-4 col-12">
            <div class="card shadow-sm border-0 faq-card h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="faq-title mb-3"><i class="ti ti-messages me-2 text-primary"></i>Direct Channels</h5>
                        <p class="text-muted f-13 mb-4"><i class="ti ti-clock me-1 text-warning"></i>Active Support Hours: <strong>10:00 AM – 6:00 PM (IST)</strong></p>

                        <!-- Call Channel -->
                        <div class="p-3 mb-3 contact-item-box">
                            <div class="d-flex align-items-center gap-3">
                                <div class="contact-icon-circle">
                                    <i class="ti ti-phone f-20"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block f-11 text-uppercase">Phone Support</span>
                                    <span class="fw-bold text-dark f-14">+91-{{ $supportMobile }}</span>
                                </div>
                                <a href="tel:+91{{ $supportMobile }}" class="btn btn-primary btn-sm px-3">Call</a>
                            </div>
                        </div>

                        <!-- Email Channel -->
                        <div class="p-3 mb-3 contact-item-box">
                            <div class="d-flex align-items-center gap-3">
                                <div class="contact-icon-circle">
                                    <i class="ti ti-mail f-20"></i>
                                </div>
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <span class="text-muted d-block f-11 text-uppercase">Email Support</span>
                                    <span class="fw-bold text-dark f-14 text-truncate d-block">{{ $supportEmail }}</span>
                                </div>
                                <a href="{{ route('support-mail') }}" class="btn btn-primary btn-sm px-3">Email</a>
                            </div>
                        </div>

                        <!-- Chat Channel -->
                        <div class="p-3 contact-item-box">
                            <div class="d-flex align-items-center gap-3">
                                <div class="contact-icon-circle">
                                    <i class="ti ti-messages f-20"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-muted d-block f-11 text-uppercase">Live Chat</span>
                                    <span class="fw-bold text-dark f-14">Interactive Chat Desk</span>
                                </div>
                                @if(Auth::user()->u_type == 1 || Auth::user()->u_type == 4)
                                    <a href="{{ route('admin.ca-ticket') }}" class="btn btn-primary btn-sm px-3">Chat</a>
                                @else
                                    <a href="{{ route('admin.customer-ticket') }}" class="btn btn-primary btn-sm px-3">Chat</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top text-center text-muted f-12">
                        Response time varies by queue load.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection