<div class="modal fade" id="sendMailModal" tabindex="-1"
     aria-labelledby="sendMailModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="sendMailModalLabel">
                    <i class="ti ti-mail text-primary me-2"></i>
                    Send Document
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body text-center">

                <div class="mb-3">
                    <i class="ti ti-mail-forward text-primary"
                       style="font-size:45px;"></i>
                </div>

                <h5 class="mb-2">
                    Send <span id="sendMailDocumentName">Invoice</span>?
                </h5>

                <p class="text-muted mb-0">
                    The PDF will be generated and sent to the customer's registered email address.
                </p>

                <input type="hidden" id="sendMailId">
                <input type="hidden" id="sendMailModule">

            </div>

            <div class="modal-footer justify-content-center">

                <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button"
                        class="btn btn-primary"
                        id="confirmSendMail">
                    <i class="ti ti-mail me-1"></i>
                    Yes, Send Mail
                </button>

            </div>

        </div>
    </div>
</div>