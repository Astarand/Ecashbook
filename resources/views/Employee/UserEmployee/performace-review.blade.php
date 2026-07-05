@extends('App.Layout')

@section('container')
<div class="pc-content">
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="d-flex justify-content-between align-items-center w-100">
            <ul class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Performance & Review</li>
            </ul>
            <a href="javascript:void(0);" id="start-employee-performance-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
              <u>How does this Page works?</u>
            </a>
          </div>
        </div>
        <div class="col-md-12 d-flex justify-content-between align-items-center">
          <div class="page-header-title">
            <h2 class="mb-0">Performance & Review</h2>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- [ Main Content ] start -->
  <div class="row mt-3">
    <div class="col-sm-12">
      <div class="card card-body table-card" id="performance-table-card">

        <div class="table-responsive">
          <table class="table tbl-product" id="pc-dt-simple">
            <thead>
              <tr style="background-color:#cdcdcd;">
                <th class="text-end">#</th>
                <th>Date of Review</th>
                <th>Month</th>
                <th>Work Performance</th>
                <th>Skill & Knowledge</th>
                <th>Attendance & Punctuality</th>
                <th>Teamwork/Behavior</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($ratings as $index => $rat)
              <tr data-rating-id="{{ $rat->id }}">
                <td class="text-end">{{ $index + 1 }}</td>
                <td>{{ $rat->created_at->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::create()->month($rat->review_month)->format('M') }}-{{ $rat->review_year }}</td>
                <td>
                  <div class="rating-readonly"><span style="color:#f7b731;  font-size:20px;">{!! str_repeat('★', $rat->work_rating) !!}</span></div>
                </td>
                <td>
                  <div class="rating-readonly"><span style="color:#f7b731;  font-size:20px;">{!! str_repeat('★', $rat->skill_rating) !!}</span></div>
                </td>
                <td>
                  <div class="rating-readonly"><span style="color:#f7b731;  font-size:20px;">{!! str_repeat('★', $rat->attendance_rating) !!}</span></div>
                </td>
                <td>
                  <div class="rating-readonly"><span style="color:#f7b731;  font-size:20px;">{!! str_repeat('★', $rat->teamwork_rating) !!}</span></div>
                </td>
                <td>
                  <a href="#" class="avtar avtar-xs btn-link-secondary view-rating-btn" data-id="{{ $rat->id }}" data-bs-toggle="tooltip" title="View">
                    <i class="ti ti-eye f-20"></i>
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
</div>

<!-- Rating Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTitle">Add Employee Rating</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="ratingForm" data-employee-id="">
          <div class="mb-3">
            <label>Work Performance (60%)</label>
            <div class="rating" data-weight="12" data-type="work"></div>
          </div>
          <div class="mb-3">
            <label>Skill & Knowledge (15%)</label>
            <div class="rating" data-weight="3" data-type="skill"></div>
          </div>
          <div class="mb-3">
            <label>Attendance & Punctuality (10%)</label>
            <div class="rating" data-weight="2" data-type="attendance"></div>
          </div>
          <div class="mb-3">
            <label>Teamwork / Behavior (15%)</label>
            <div class="rating" data-weight="3" data-type="teamwork"></div>
          </div>

          <div class="mt-3">
            <label>Total Percentage</label>
            <input type="text" id="totalPercentage" class="form-control" readonly value="0%">
          </div>
          <div class="mt-3">
            <label>Review</label>
            <textarea class="form-control" name="review" rows="3" placeholder="Enter review comments..." readonly></textarea>
          </div>
          <input type="hidden" name="empId" value="{{ $empId }}">
          <input type="hidden" name="ratingId" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {

  // Initialize stars for modal
  function initStars() {
    document.querySelectorAll('.rating').forEach(div => {
      div.innerHTML = ''; // clear existing
      for (let i = 1; i <= 5; i++) {
        let star = document.createElement('span');
        star.classList.add('star');
        star.dataset.value = i;
        star.textContent = '★';
        div.appendChild(star);
      }
      div.dataset.rating = 0;
    });
  }

  initStars();

  function updateTotalPercentage() {
    let total = 0;
    document.querySelectorAll('.rating').forEach(div => {
      let stars = parseInt(div.dataset.rating || 0, 10);
      let weight = parseFloat(div.dataset.weight || 0);
      total += stars * weight;
    });
    // if you want it capped at 100:
    if (total > 100) total = 100;
    document.getElementById('totalPercentage').value = total + '%';
  }

  // Click on stars (delegated)
  document.addEventListener('click', function(e) {
    if (e.target.classList && e.target.classList.contains('star')) {
      let div = e.target.closest('.rating');
      div.dataset.rating = e.target.dataset.value;
      div.querySelectorAll('.star').forEach(star => {
        star.classList.toggle('active', parseInt(star.dataset.value, 10) <= parseInt(e.target.dataset.value, 10));
      });
      updateTotalPercentage();
    }
  });

  // Safely attach Add Rating button if exists
  const addBtn = document.getElementById('addRatingBtn');
  if (addBtn) {
    addBtn.addEventListener('click', function() {
      document.getElementById('modalTitle').textContent = 'Add Employee Rating';
      document.querySelector('input[name="ratingId"]').value = '';
      initStars();
      document.getElementById('totalPercentage').value = '0%';
      document.querySelector('#ratingForm textarea[name="review"]').value = '';
      new bootstrap.Modal(document.getElementById('ratingModal')).show();
    });
  }

  // Open View/Edit Rating Modal (delegated) - works when clicking button or inner <i>
  document.addEventListener('click', function(e) {
    const btn = e.target.closest && e.target.closest('.view-rating-btn');
    if (!btn) return;

    const ratingId = btn.dataset.id;
    if (!ratingId) {
      console.error('No rating id found on button');
      return;
    }

    document.getElementById('modalTitle').textContent = 'View Employee Rating';

    fetch(`/useremployee/rating/${ratingId}/view`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
      }
    })
    .then(res => {
      if (!res.ok) throw new Error('Network response was not ok');
      return res.json();
    })
    .then(res => {
      if (res.success) {
        const rating = res.data;

        // Set rating ID for update
        document.querySelector('input[name="ratingId"]').value = ratingId;

        // Set star ratings (ensure numeric)
        document.querySelector('.rating[data-type="work"]').dataset.rating = parseInt(rating.work_rating || 0, 10);
        document.querySelector('.rating[data-type="skill"]').dataset.rating = parseInt(rating.skill_rating || 0, 10);
        document.querySelector('.rating[data-type="attendance"]').dataset.rating = parseInt(rating.attendance_rating || 0, 10);
        document.querySelector('.rating[data-type="teamwork"]').dataset.rating = parseInt(rating.teamwork_rating || 0, 10);

        // Update visual stars
        document.querySelectorAll('.rating').forEach(div => {
          const val = parseInt(div.dataset.rating || 0, 10);
          div.querySelectorAll('.star').forEach(star => {
            star.classList.toggle('active', parseInt(star.dataset.value, 10) <= val);
          });
        });

        // Set review text
        document.querySelector('#ratingForm textarea[name="review"]').value = rating.review || '';

        updateTotalPercentage();

        new bootstrap.Modal(document.getElementById('ratingModal')).show();
      } else {
        console.error('Failed to load rating:', res);
        alert(res.message || 'Failed to load rating data');
      }
    })
    .catch(err => {
      console.error('Fetch error:', err);
      alert('Something went wrong while loading rating data.');
    });
  });

  function startEmployeePerformanceTour() {
      if (typeof introJs !== 'function') return;

      introJs().setOptions({
          steps: [
              {
                  title: 'Performance & Review Guide',
                  intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-star" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Track and review monthly performance scores and feedback provided by your manager.</p></div>'
              },
              {
                  element: '#performance-table-card',
                  title: 'Performance Review History Table',
                  intro: 'List of all monthly performance evaluations, featuring detailed star ratings for Work Performance, Skill & Knowledge, Attendance, and Teamwork/Behavior.'
              },
              {
                  element: '.view-rating-btn',
                  title: 'View Remarks & Score',
                  intro: 'Click the view icon to open the detailed rating card showing the calculated percentage score and manager remarks.',
                  skipIfNoElement: true
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
      }).start();
  }

  $(document).ready(function() {
      $('#start-employee-performance-tour').on('click', function(e) {
          e.preventDefault();
          startEmployeePerformanceTour();
      });
  });

});
</script>


<style>
  .rating .star {
    cursor: pointer;
    font-size: 22px;
    color: #ccc;
    margin-right: 4px;
  }

  .rating .star.active {
    color: #f7b731;
  }
</style>
@endsection
