//-------- MethotX Interactive Tour Manager --------//

window.MethotXTour = {
  // Get Current Authenticated User ID
  getUserId: function() {
    if (typeof window.CURRENT_USER_ID !== 'undefined' && window.CURRENT_USER_ID) {
      return window.CURRENT_USER_ID;
    }
    return 'default';
  },

  // Check if tour was already completed (by checking DB status or localStorage)
  isCompletedOnDevice: function(pageKey) {
    // 1. If database already marks this user as completed, never auto-show
    if (typeof window.USER_TOUR_COMPLETED !== 'undefined' && window.USER_TOUR_COMPLETED === true) {
      return true;
    }

    // 2. Check device/browser localStorage
    var uid = this.getUserId();
    var deviceKey = 'methotx_tour_' + pageKey + '_u' + uid;
    
    return localStorage.getItem(deviceKey) === 'completed' ||
           localStorage.getItem('methotx_dashboard_tour') === 'completed' ||
           localStorage.getItem('methotx_' + pageKey + '_tour') === 'completed' ||
           localStorage.getItem('ecashbook_dashboard_tour') === 'completed';
  },

  // Mark tour as completed in both localStorage and database
  markCompletedOnDevice: function(pageKey) {
    var uid = this.getUserId();
    var deviceKey = 'methotx_tour_' + pageKey + '_u' + uid;
    localStorage.setItem(deviceKey, 'completed');
    localStorage.setItem('methotx_dashboard_tour', 'completed');
    window.USER_TOUR_COMPLETED = true;

    // Persist to database so this user never sees auto-tour again across logins
    if (typeof window.TOUR_COMPLETE_ROUTE !== 'undefined' && typeof window.CSRF_TOKEN !== 'undefined') {
      $.ajax({
        url: window.TOUR_COMPLETE_ROUTE,
        type: 'POST',
        data: { _token: window.CSRF_TOKEN },
        success: function() {},
        error: function() {}
      });
    }
  },

  // Auto run tour once ONLY for newly registered users who haven't completed it
  autoLaunch: function(pageKey, startFunction, delayMs) {
    // If already completed in DB or localStorage, do NOT auto popup
    if (this.isCompletedOnDevice(pageKey)) {
      return;
    }

    var self = this;
    var waitTime = typeof delayMs === 'number' ? delayMs : 2000;

    $(document).ready(function() {
      // Defer if subscription or expired modal exists
      var subModal = $('#subscriptionModal');
      var expModal = $('#expiredModal');
      var todayStr = new Date().toDateString();

      var willShowSub = subModal.length && (typeof SUBSCRIPTION_ACCESS_TYPE !== 'undefined' && SUBSCRIPTION_ACCESS_TYPE === 'trial' && typeof SUBSCRIPTION_TRIAL_DAYS !== 'undefined' && SUBSCRIPTION_TRIAL_DAYS > 0 && localStorage.getItem('subscription_popup_last') !== todayStr);
      var willShowExp = expModal.length && (typeof SUBSCRIPTION_ACCESS_TYPE !== 'undefined' && SUBSCRIPTION_ACCESS_TYPE === 'expired' && localStorage.getItem('subscription_popup_last') !== todayStr);

      if (willShowSub) {
        subModal.one('hidden.bs.modal', function () {
          setTimeout(function() {
            if (!self.isCompletedOnDevice(pageKey)) {
              self.markCompletedOnDevice(pageKey);
              startFunction();
            }
          }, 600);
        });
      } else if (willShowExp) {
        expModal.one('hidden.bs.modal', function () {
          setTimeout(function() {
            if (!self.isCompletedOnDevice(pageKey)) {
              self.markCompletedOnDevice(pageKey);
              startFunction();
            }
          }, 600);
        });
      } else {
        setTimeout(function() {
          // If another modal is currently active, wait until it closes
          if ($('.modal.show').length > 0) {
            $('.modal.show').one('hidden.bs.modal', function() {
              setTimeout(function() {
                if (!self.isCompletedOnDevice(pageKey)) {
                  self.markCompletedOnDevice(pageKey);
                  startFunction();
                }
              }, 600);
            });
          } else {
            if (!self.isCompletedOnDevice(pageKey)) {
              self.markCompletedOnDevice(pageKey);
              startFunction();
            }
          }
        }, waitTime);
      }
    });
  }
};

// Dashboard Tour Logic
$(document).ready(function() {
  function startDashboardTour() {
    if (typeof introJs !== 'function') return;

    if ($('.modal.show').length > 0) {
      $('.modal.show').one('hidden.bs.modal', function() {
        setTimeout(startDashboardTour, 500);
      });
      return;
    }

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
      MethotXTour.markCompletedOnDevice('dashboard');
    }).onexit(function() {
      MethotXTour.markCompletedOnDevice('dashboard');
    });
  }

  // Expose globally
  window.startDashboardTour = startDashboardTour;

  // Auto-launch only if new user & not completed
  if (document.getElementById('start-tour-btn') || document.querySelector('.tour-search')) {
    MethotXTour.autoLaunch('dashboard', startDashboardTour, 2000);
  }

  // Manual Button click ALWAYS launches tour anytime
  $(document).on('click', '#start-tour-btn', function(e) {
    e.preventDefault();
    startDashboardTour();
  });
});
