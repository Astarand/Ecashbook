//-------- MethotX Interactive Tour Guide --------//
$(document).ready(function() {
  function markTourCompleted() {
    if (typeof TOUR_COMPLETE_ROUTE !== 'undefined' && typeof CSRF_TOKEN !== 'undefined') {
      $.ajax({
        url: TOUR_COMPLETE_ROUTE,
        type: 'POST',
        data: {
          _token: CSRF_TOKEN
        },
        success: function(response) {
          console.log('Tour status saved to database.');
        },
        error: function(xhr) {
          console.error('Failed to save tour status to database.');
        }
      });
    }
    localStorage.setItem('methotx_dashboard_tour', 'completed');
  }

  function startIntroTour() {
    // Only run tour if introJs function is defined
    if (typeof introJs !== 'function') return;
    
    // Defer tour if any modal is currently visible on screen
    if ($('.modal.show').length > 0) {
      $('.modal.show').one('hidden.bs.modal', function() {
        setTimeout(startIntroTour, 500);
      });
      return;
    }
    
    // Only run tour if elements exist on page (specifically for dashboard page tour)
    if (!document.getElementById('start-tour-btn') && !document.querySelector('.tour-search')) return;

    introJs().setOptions({
      steps: [
        {
          title: 'Welcome to MethotX Compliance Infrastructure Platform',
          intro: '<div class="py-1"><div class="mb-3 d-flex align-items-center"><span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold"><i class="ti ti-rocket me-1"></i> Interactive Tour</span></div><h6 class="fw-bold text-dark mb-2" style="font-size: 1rem;">Explore Your Financial Dashboard</h6><p class="mb-0 text-muted small" style="line-height:1.6;">Let\'s walk through the primary tools, key performance indicators, and compliance modules available on your portal.</p></div>'
        },
        {
          element: '.tour-search',
          title: 'Universal Search & Navigation',
          intro: 'Instantly search and access any feature, ledger, or setting across the portal. Shortcut: <kbd class="bg-light text-dark border px-1 rounded">Ctrl + K</kbd>.'
        },
        {
          element: '.tour-assign-ca',
          title: 'Assign CA Firm',
          intro: 'Connect your company profile with an accredited Chartered Accountant (CA) firm for real-time audit, advice, and tax filing.'
        },
        {
          element: '.tour-add-sales',
          title: 'Create Sales Invoices',
          intro: 'Generate professional GST-compliant sales invoices, record customer payments, and track outstanding receivables.'
        },
        {
          element: '.tour-add-purchases',
          title: 'Record Purchase Bills',
          intro: 'Log vendor procurement bills, track accounts payable, and claim eligible Input Tax Credit (ITC).'
        },
        {
          element: '#slet_financial_year',
          title: 'Financial Year Filter',
          intro: 'Switch financial assessment years to update all dashboard analytics, chart visualizations, and summary reports.'
        },
        {
          element: '.tour-receivables',
          title: 'Total Receivables Summary',
          intro: 'Monitor customer outstandings, breakdown current vs. overdue receivables, and manage collection timelines.'
        },
        {
          element: '.tour-payables',
          title: 'Total Payables Summary',
          intro: 'Keep track of vendor obligations, credit balances, and cash outflow commitments.'
        },
        {
          element: '.tour-turnover',
          title: 'Turnover & Revenue Analytics',
          intro: 'Analyze gross turnover trends across financial quarters with comparative performance charts.'
        },
        {
          element: '.tour-income-expenses',
          title: 'Income vs. Operating Expenses',
          intro: 'Evaluate net profit margins by comparing total gross income against categorized operating expenses.'
        },
        {
          element: '.tour-cashflow',
          title: 'Cash Flow Dynamics',
          intro: 'Review net liquid cash movement and monthly operating cash flow performance.'
        },
        {
          element: '.tour-assets',
          title: 'Business Asset Portfolio',
          intro: 'Track current fixed and liquid asset valuations across active business ledgers.'
        },
        {
          element: '.tour-liabilities',
          title: 'Liabilities Breakdown',
          intro: 'Overview statutory, short-term, and long-term liabilities requiring financial settlement.'
        },
        {
          element: '.tour-gst',
          title: 'GST Input & Output Tax Balance',
          intro: 'Real-time reconciliation of Input Tax Credit (ITC) receivables and Output GST tax payables.'
        },
        {
          element: '.tour-attendance',
          title: 'Workforce Attendance Tracker',
          intro: 'Monitor daily employee attendance metrics, on-time arrivals, late marks, and leave status.'
        },
        {
          element: '.tour-compliances',
          title: 'Statutory Compliances & CA Communication',
          intro: 'Stay updated on GSTR-1 & TDS filing deadlines and communicate directly with your assigned CA firm.'
        }
      ],
      showBullets: false,
      showProgress: true,
      helperElementPadding: 2,
      exitOnOverlayClick: false,
      doneLabel: 'Complete Tour',
      nextLabel: 'Next',
      prevLabel: 'Back',
      skipLabel: 'Skip'
    }).start().oncomplete(function() {
      markTourCompleted();
    }).onexit(function() {
      markTourCompleted();
    });
  }

  // Auto start tour on first visit (checks database, localstorage, and session states)
  const isCompletedDb = typeof USER_TOUR_COMPLETED !== 'undefined' && USER_TOUR_COMPLETED;
  const isCompletedLs = localStorage.getItem('methotx_dashboard_tour') === 'completed' || localStorage.getItem('ecashbook_dashboard_tour') === 'completed';
  const isSeenSession = sessionStorage.getItem('methotx_dashboard_tour_seen') === 'true';

  if (!isCompletedDb && !isCompletedLs && !isSeenSession) {
    sessionStorage.setItem('methotx_dashboard_tour_seen', 'true');
    
    const subModal = $('#subscriptionModal');
    const expModal = $('#expiredModal');
    const todayStr = new Date().toDateString();
    
    const willShowSub = subModal.length && (typeof SUBSCRIPTION_ACCESS_TYPE !== 'undefined' && SUBSCRIPTION_ACCESS_TYPE === 'trial' && typeof SUBSCRIPTION_TRIAL_DAYS !== 'undefined' && SUBSCRIPTION_TRIAL_DAYS > 0 && localStorage.getItem('subscription_popup_last') !== todayStr);
    const willShowExp = expModal.length && (typeof SUBSCRIPTION_ACCESS_TYPE !== 'undefined' && SUBSCRIPTION_ACCESS_TYPE === 'expired' && localStorage.getItem('subscription_popup_last') !== todayStr);

    if (willShowSub) {
      subModal.on('hidden.bs.modal', function () {
        setTimeout(startIntroTour, 500);
      });
    } else if (willShowExp) {
      expModal.on('hidden.bs.modal', function () {
        setTimeout(startIntroTour, 500);
      });
    } else {
      setTimeout(startIntroTour, 2000);
    }
  }

  // Button click trigger
  $('#start-tour-btn').on('click', function(e) {
    e.preventDefault();
    startIntroTour();
  });
});
