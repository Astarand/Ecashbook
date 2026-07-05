<div class="modal fade" id="auditModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
            <i class="ti ti-shield-check"></i> Audit Log Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-6">
                <strong>User:</strong>
                <div id="m_user_id" class="text-muted"></div>
            </div>

            <div class="col-md-6">
                <strong>Role:</strong>
                <div id="m_user_type" class="text-muted"></div>
            </div>

            <div class="col-md-6">
                <strong>Action:</strong>
                <div id="m_action" class="text-muted"></div>
            </div>

            <div class="col-md-6">
                <strong>Module:</strong>
                <div id="m_module" class="text-muted"></div>
            </div>

            <div class="col-md-12">
                <strong>Description:</strong>
                <div id="m_description" class="text-muted"></div>
            </div>

            <div class="col-md-6">
                <strong>Method:</strong>
                <div id="m_method" class="text-muted"></div>
            </div>

            <div class="col-md-6">
                <strong>IP Address:</strong>
                <div id="m_ip" class="text-muted"></div>
            </div>

            <div class="col-md-12">
                <strong>User Agent:</strong>
                <div id="m_user_agent" class="small text-muted"></div>
            </div>

            <div class="col-md-12">
                <strong>Old Data:</strong>
                <pre class="bg-light p-2 rounded small" id="m_old_data"></pre>
            </div>

            <div class="col-md-12">
                <strong>New Data:</strong>
                <pre class="bg-light p-2 rounded small" id="m_new_data"></pre>
            </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
            Close
        </button>
      </div>
    </div>
  </div>
</div>
