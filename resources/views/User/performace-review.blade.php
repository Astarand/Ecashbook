@extends('App.Layout')

@section('container')
<div class="pc-content">
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Employee Master</a></li>
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

        <!-- Header with Add Rating button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Employee Performance List</h5>
          <button class="btn btn-primary" id="addRatingBtn">
            <i class="ti ti-plus"></i> Add Rating
          </button>
        </div>

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
                <td>
                    {{ \Carbon\Carbon::create()->month($rat->review_month)->format('M') }}-{{ $rat->review_year }}
                </td>

                <td>
                  <div class="rating-readonly"><span style="color:#f5a623; font-size: 20px;">{!! str_repeat('★', $rat->work_rating) !!}</span></div>
                </td>
                <td>
                  <div class="rating-readonly"><span style="color:#f5a623; font-size: 20px;">{!! str_repeat('★', $rat->skill_rating) !!}</span></div>
                </td>
                <td>
                  <div class="rating-readonly"><span style="color:#f5a623; font-size: 20px;">{!! str_repeat('★', $rat->attendance_rating) !!}</span></div>
                </td>
                <td>
                  <div class="rating-readonly"><span style="color:#f5a623; font-size: 20px;">{!! str_repeat('★', $rat->teamwork_rating) !!}</span></div>
                </td>
                <td>
                  <button class="btn btn-sm btn-warning edit-rating-btn" data-id="{{ $rat->id }}">
                    <i class="ti ti-edit"></i> Edit
                  </button>
                  <button class="btn btn-sm btn-danger delete-rating-btn" data-id="{{ $rat->id }}">
                    <i class="ti ti-trash"></i> Delete
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
            <label for="reviewMonth">Select Month</label>
            <select class="form-select" name="month" id="reviewMonth" required>
              <option value="">-- Select Month --</option>
              @foreach ([
                '01' => 'January', '02' => 'February', '03' => 'March',
                '04' => 'April', '05' => 'May', '06' => 'June',
                '07' => 'July', '08' => 'August', '09' => 'September',
                '10' => 'October', '11' => 'November', '12' => 'December'
              ] as $num => $name)
                <option value="{{ $num }}">{{ $name }}</option>
              @endforeach
            </select>
          </div>
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
            <textarea class="form-control" name="review" rows="3" placeholder="Enter review comments..."></textarea>
          </div>
          <input type="hidden" name="empId" value="{{ $empId }}">
          <input type="hidden" name="ratingId" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="saveRating">Save Rating</button>
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
        for(let i=1;i<=5;i++){
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
        let stars = parseInt(div.dataset.rating || 0);
        let weight = parseFloat(div.dataset.weight);
        total += stars * weight;
      });
      document.getElementById('totalPercentage').value = total + '%';
    }

    // Click on stars
    document.addEventListener('click', function(e){
      if(e.target.classList.contains('star')){
        let div = e.target.closest('.rating');
        div.dataset.rating = e.target.dataset.value;
        div.querySelectorAll('.star').forEach(star => {
          star.classList.toggle('active', star.dataset.value <= e.target.dataset.value);
        });
        updateTotalPercentage();
      }
    });

    // Open Add Rating Modal
    // Open Add Rating Modal
document.getElementById('addRatingBtn').addEventListener('click', function() {
  document.getElementById('modalTitle').textContent = 'Add Employee Rating';
  document.querySelector('input[name="ratingId"]').value = '';
  initStars();
  document.getElementById('totalPercentage').value = '0%';
  document.querySelector('#ratingForm textarea[name="review"]').value = '';

  // Reset month dropdown
  const monthSelect = document.querySelector('select[name="month"]');
  monthSelect.value = '';
  monthSelect.disabled = false; // enable for new entry

  new bootstrap.Modal(document.getElementById('ratingModal')).show();
});

// Open Edit Rating Modal
document.addEventListener('click', function(e) {
  if (e.target.closest('.edit-rating-btn')) {
    const btn = e.target.closest('.edit-rating-btn');
    const ratingId = btn.dataset.id;

    document.getElementById('modalTitle').textContent = 'Edit Employee Rating';

    // Fetch rating data
    fetch(`/employee/rating/${ratingId}/edit`, {
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
      }
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        const rating = res.data;

        // Set rating ID for update
        document.querySelector('input[name="ratingId"]').value = ratingId;

        // Set month (and disable it)
        const monthSelect = document.querySelector('select[name="month"]');
        monthSelect.value = rating.review_month || '';
        monthSelect.disabled = true;

        // Set star ratings
        document.querySelector('.rating[data-type="work"]').dataset.rating = rating.work_rating;
        document.querySelector('.rating[data-type="skill"]').dataset.rating = rating.skill_rating;
        document.querySelector('.rating[data-type="attendance"]').dataset.rating = rating.attendance_rating;
        document.querySelector('.rating[data-type="teamwork"]').dataset.rating = rating.teamwork_rating;

        // Update visual stars
        document.querySelectorAll('.rating').forEach(div => {
          const val = parseInt(div.dataset.rating);
          div.querySelectorAll('.star').forEach(star => {
            star.classList.toggle('active', star.dataset.value <= val);
          });
        });

        // Set review text
        document.querySelector('#ratingForm textarea[name="review"]').value = rating.review || '';

        updateTotalPercentage();

        new bootstrap.Modal(document.getElementById('ratingModal')).show();
      } else {
        showToast(res.message || "Failed to load rating data", "error");
      }
    })
    .catch(err => {
      console.log(err);
      showToast("Something went wrong!", "error");
    });
  }
});


    // Open Edit Rating Modal
    document.addEventListener('click', function(e) {
      if (e.target.closest('.edit-rating-btn')) {
        const btn = e.target.closest('.edit-rating-btn');
        const ratingId = btn.dataset.id;

        document.getElementById('modalTitle').textContent = 'Edit Employee Rating';

        // Fetch rating data
        fetch(`/employee/rating/${ratingId}/edit`, {
          method: 'GET',
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          }
        })
        .then(res => res.json())
        .then(res => {
          if (res.success) {
            const rating = res.data;

            // Set rating ID for update
            document.querySelector('input[name="ratingId"]').value = ratingId;

            // Set star ratings
            document.querySelector('.rating[data-type="work"]').dataset.rating = rating.work_rating;
            document.querySelector('.rating[data-type="skill"]').dataset.rating = rating.skill_rating;
            document.querySelector('.rating[data-type="attendance"]').dataset.rating = rating.attendance_rating;
            document.querySelector('.rating[data-type="teamwork"]').dataset.rating = rating.teamwork_rating;

            // Debug log to check values
            console.log('Rating values:', {
              work: rating.work_rating,
              skill: rating.skill_rating,
              attendance: rating.attendance_rating,
              teamwork: rating.teamwork_rating
            });

            // Update visual stars
            document.querySelectorAll('.rating').forEach(div => {
              const val = parseInt(div.dataset.rating);
              div.querySelectorAll('.star').forEach(star => {
                star.classList.toggle('active', star.dataset.value <= val);
              });
            });

            // Set review text
            document.querySelector('#ratingForm textarea[name="review"]').value = rating.review || '';

            updateTotalPercentage();

            new bootstrap.Modal(document.getElementById('ratingModal')).show();
          } else {
            showToast(res.message || "Failed to load rating data", "error");
          }
        })
        .catch(err => {
          console.log(err);
          showToast("Something went wrong!", "error");
        });
      }
    });

    // Delete Rating
    document.addEventListener('click', function(e) {
      if (e.target.closest('.delete-rating-btn')) {
        const btn = e.target.closest('.delete-rating-btn');
        const ratingId = btn.dataset.id;

        Swal.fire({
          title: 'Are you sure?',
          text: 'You want to delete this rating?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            fetch(`/employee/rating/${ratingId}/delete`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
              }
            })
            .then(res => res.json())
            .then(res => {
              if (res.success) {
                showToast(res.message, "success");

                // Remove row from table
                btn.closest('tr').remove();

                // Update row numbers
                document.querySelectorAll('#pc-dt-simple tbody tr').forEach((row, index) => {
                  row.querySelector('td:first-child').textContent = index + 1;
                });
              } else {
                showToast(res.message || "Failed to delete rating", "error");
              }
            })
            .catch(err => {
              console.log(err);
              showToast("Something went wrong!", "error");
            });
          }
        });
      }
    });



    // Save Rating AJAX
    document.getElementById('saveRating').addEventListener('click', function() {
      let form = document.getElementById('ratingForm');
      let empId = form.querySelector('input[name="empId"]').value;
      let ratingId = form.querySelector('input[name="ratingId"]').value;
      const month = document.querySelector('select[name="month"]').value;

      // Read ratings
      const work = parseInt(document.querySelector('.rating[data-type="work"]').dataset.rating || 0);
      const skill = parseInt(document.querySelector('.rating[data-type="skill"]').dataset.rating || 0);
      const attendance = parseInt(document.querySelector('.rating[data-type="attendance"]').dataset.rating || 0);
      const teamwork = parseInt(document.querySelector('.rating[data-type="teamwork"]').dataset.rating || 0);
      const total = parseFloat(document.getElementById('totalPercentage').value);
      const review = form.querySelector('textarea[name="review"]').value;

      // Simple validation
      if (!empId) {
          showToast("Employee ID not found!", "error");
          return;
      }
      if (!month) {
        showToast("Please select a review month!", "error");
        return;
      }

      // Determine if this is an update or create
      const isUpdate = ratingId && ratingId !== '';
      const url = isUpdate ? `/employee/rating/${ratingId}/update` : "{{ route('employee.saveRating') }}";
      const method = isUpdate ? 'PUT' : 'POST';

      fetch(url, {
          method: method,
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          body: JSON.stringify({
              empId: empId,
              month: month,
              work_rating: work,
              skill_rating: skill,
              attendance_rating: attendance,
              teamwork_rating: teamwork,
              total_percentage: total,
              review: review
          })
      })
      .then(res => res.json())
      .then(res => {
          if (res.success) {
              showToast(res.message, "success");

              // Close modal
              bootstrap.Modal.getInstance(document.getElementById('ratingModal')).hide();

              // Reload page after 2 seconds
              setTimeout(() => {
                  location.reload();
              }, 2000);

          } else {
              showToast(res.message || "Failed to save rating", "error");
          }
      })
      .catch(err => {
          console.log(err);
          showToast("Something went wrong!", "error");
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
