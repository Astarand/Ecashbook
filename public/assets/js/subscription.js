document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toDateString();
    const lastShown = localStorage.getItem('subscription_popup_last');

    if (typeof SUBSCRIPTION_ACCESS_TYPE !== 'undefined' && typeof SUBSCRIPTION_TRIAL_DAYS !== 'undefined') {
        // ========== TRIAL ACTIVE ==========
        if (SUBSCRIPTION_ACCESS_TYPE === 'trial' && SUBSCRIPTION_TRIAL_DAYS > 0) {
            if (lastShown !== today) {
                const daysLeftElem = document.getElementById('days-left');
                if (daysLeftElem) {
                    daysLeftElem.innerText = SUBSCRIPTION_TRIAL_DAYS;
                }

                const modalElem = document.getElementById('subscriptionModal');
                if (modalElem) {
                    const modal = new bootstrap.Modal(modalElem, {
                        backdrop: 'static',
                        keyboard: false
                    });
                    modal.show();
                    localStorage.setItem('subscription_popup_last', today);
                }
            }
        }

        // ========== TRIAL EXPIRED ==========
        if (SUBSCRIPTION_ACCESS_TYPE === 'expired') {
            if (lastShown !== today) {
                const modalElem = document.getElementById('expiredModal');
                if (modalElem) {
                    const modal = new bootstrap.Modal(modalElem, {
                        backdrop: 'static',
                        keyboard: false
                    });
                    modal.show();
                    localStorage.setItem('subscription_popup_last', today);
                }
            }
        }
    }
});
