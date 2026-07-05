@if (Auth::check() && Auth::user()->u_type == 2)
    <!-- Subscription Reminder Modal -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden;">

                <!-- Header -->
                <div style="background: #FF2C55; padding: 22px; color: #fff;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="background-color: rgba(255,255,255,0.15); border-radius: 8px; width: 48px; height: 48px;"
                            class="d-flex align-items-center justify-content-center">
                            <i class="ti ti-alarm fs-4 text-white"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-white">Subscription Expiring Soon</h4>
                            <p class="mb-0 opacity-75 small text-white">
                                Your free trial period is ending
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">

                    <!-- Days Left -->
                    <div class="d-flex align-items-center mb-3 p-3 rounded"
                        style="background-color:#fff5f7;border-left:4px solid #FF2C55;">
                        <div class="me-3 d-flex align-items-center justify-content-center"
                            style="width:60px;height:60px;background:#ffe5ea;border-radius:8px;">
                            <span class="fs-4 fw-bold text-danger" id="days-left">0</span>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-semibold">Days Remaining in Trial</h6>
                            <p class="mb-0 small text-muted">
                                Access to premium features will be limited after trial expiration
                            </p>
                        </div>
                    </div>

                    <!-- Benefits -->
                    <div class="p-3 rounded bg-light mb-4">
                        <h6 class="fw-semibold mb-2">
                            <i class="ti ti-check-circle text-danger me-1"></i>
                            Benefits of Premium Subscription
                        </h6>
                        <ul class="list-unstyled small mb-0">
                            <li><i class="ti ti-check text-danger me-1"></i> Unlimited access to all financial tools
                            </li>
                            <li><i class="ti ti-check text-danger me-1"></i> Priority customer support</li>
                            <li><i class="ti ti-check text-danger me-1"></i> Advanced reporting features</li>
                            <li><i class="ti ti-check text-danger me-1"></i> Data export capabilities</li>
                        </ul>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-2">
                        <button id="close-reminder" class="btn btn-light flex-fill" data-bs-dismiss="modal">Remind
                            Me Later</button>
                        <a href="{{ route('user.Plans') }}" class="btn flex-fill text-white"
                            style="background:#FF2C55;">View Plans</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="expiredModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius:12px">

                <div style="background:#111;padding:22px;color:#fff">
                    <p class="mb-0 opacity-75">Subscription Expired</p>
                </div>

                <div class="modal-body p-4 text-center">
                    <h5 class="mb-3 text-danger">
                        Your access is now limited
                    </h5>
                    <p class="text-muted">
                        Please upgrade your plan to continue using all features.
                    </p>

                    <div class="d-flex gap-2">
                        <button id="close-reminder" class="btn btn-light flex-fill"
                            data-bs-dismiss="modal">Remind Me Later</button>
                        <a href="{{ route('user.Plans') }}" class="btn flex-fill text-white"
                            style="background:#FF2C55;">View Plans</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Subscription Reminder Script Configuration & Asset -->
    <script>
        const SUBSCRIPTION_ACCESS_TYPE = "{{ $accessType ?? '' }}";
        const SUBSCRIPTION_TRIAL_DAYS = {{ (int) ($trialDaysLeft ?? 0) }};
    </script>
    <script src="{{ asset('assets/js/subscription.js') }}?v=1.1"></script>
@endif
