//-------- E-Cashbook Interactive Tour Guide --------//
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
    localStorage.setItem('ecashbook_dashboard_tour', 'completed');
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
          title: 'Welcome to E-Cashbook',
          intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-rocket" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Let\'s take a quick interactive tour to get familiar with the core features of your financial dashboard.</p></div>'
        },
        {
          element: '.tour-search',
          title: 'Universal Search',
          intro: 'Quickly search and find any menu, setting, or feature instantly across the application.'
        },
        {
          element: '.tour-assign-ca',
          title: 'Assign CA Firm',
          intro: 'Link and assign your account to a registered Chartered Accountant (CA) firm for seamless audit & tax filings.'
        },
        {
          element: '.tour-add-sales',
          title: 'Record Sales',
          intro: 'Create new sales invoices, track customer billings, and log business revenues here.'
        },
        {
          element: '.tour-add-purchases',
          title: 'Record Purchases',
          intro: 'Easily record vendor purchase bills, credit notes, and business procurement expenses.'
        },
        {
          element: '#slet_financial_year',
          title: 'Financial Year Selector',
          intro: 'Switch between financial years to filter all data, charts, and reports on the dashboard.'
        },
        {
          element: '.tour-receivables',
          title: 'Total Receivables',
          intro: 'Monitor your pending customer payments, including current and overdue receivables.'
        },
        {
          element: '.tour-payables',
          title: 'Total Payables',
          intro: 'Keep track of what you owe to vendors and suppliers to manage your cash outflow efficiently.'
        },
        {
          element: '.tour-turnover',
          title: 'Turnover Analysis',
          intro: 'Visualize your monthly and quarterly sales turnover with interactive analytical charts.'
        },
        {
          element: '.tour-income-expenses',
          title: 'Income & Expenses',
          intro: 'Track and compare your business income, operating expenses, and net profit margins over time.'
        },
        {
          element: '.tour-cashflow',
          title: 'Cashflow Summary',
          intro: 'Analyze cash inflows and outflows to evaluate net cash changes on a monthly or quarterly basis.'
        },
        {
          element: '.tour-assets',
          title: 'Asset Summary',
          intro: 'Monitor the total current value of your business assets and access the complete asset ledger.'
        },
        {
          element: '.tour-liabilities',
          title: 'Liabilities Summary',
          intro: 'Keep track of outstanding business liabilities and debts that require repayment.'
        },
        {
          element: '.tour-gst',
          title: 'GST Summary',
          intro: 'Real-time GST input tax credit (ITC) receivables and tax payables calculated based on invoices.'
        },
        {
          element: '.tour-attendance',
          title: 'Employee Attendance',
          intro: 'Review daily employee attendance stats, including present, late, on-time, and absent counts.'
        },
        {
          element: '.tour-compliances',
          title: 'Compliance & Communication',
          intro: 'Track GSTR-1, TDS compliance deadlines, and directly chat with your assigned CA firm.'
        }
      ],
      showBullets: true,
      showProgress: true,
      helperElementPadding: 5,
      exitOnOverlayClick: false,
      doneLabel: 'Done',
      nextLabel: 'Next',
      prevLabel: 'Prev',
      skipLabel: 'Skip'
    }).start().oncomplete(function() {
      markTourCompleted();
    }).onexit(function() {
      markTourCompleted();
    });
  }

  // Auto start tour on first visit (checks database, localstorage, and session states)
  const isCompletedDb = typeof USER_TOUR_COMPLETED !== 'undefined' && USER_TOUR_COMPLETED;
  const isCompletedLs = localStorage.getItem('ecashbook_dashboard_tour') === 'completed';
  const isSeenSession = sessionStorage.getItem('ecashbook_dashboard_tour_seen') === 'true';

  if (!isCompletedDb && !isCompletedLs && !isSeenSession) {
    sessionStorage.setItem('ecashbook_dashboard_tour_seen', 'true');
    
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
