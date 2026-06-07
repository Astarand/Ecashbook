@extends('App.Layout')

@section('container')
<div class="pc-content">
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Performance & Review</li>
          </ul>
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
      <div class="card card-body table-card">

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
                  <button class="btn btn-sm btn-primary view-rating-btn" data-id="{{ $rat->id }}">
                    <i class="ti ti-eye"></i> View
                  </button>
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
