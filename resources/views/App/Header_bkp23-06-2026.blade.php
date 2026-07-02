<header class="pc-header">
    <style>
        .pc-header .pc-head-link .pc-h-badge {
            position: absolute;
            top: 3px !important;
            right: 3px !important;
            width: 19px !important;
            height: 19px !important;
            min-width: 19px !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 50% !important;
            font-size: 10px !important;
            font-weight: 700 !important;
            border: 2px solid #ffffff !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15) !important;
            line-height: 1 !important;
        }
    </style>
    <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>

            </ul>
        </div>
        <!-- [Mobile Media Block end] -->
        <div class="ms-auto">
            <ul class="list-unstyled">
                <!-- Theme Color Change -->
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ph-duotone ph-sun-dim"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                        <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
                            <i class="ph-duotone ph-moon"></i>
                            <span>Dark</span>
                        </a>
                        <a href="#!" class="dropdown-item" onclick="layout_change('light')">
                            <i class="ph-duotone ph-sun-dim"></i>
                            <span>Light</span>
                        </a>
                        <a href="#!" class="dropdown-item" onclick="layout_change_default()">
                            <i class="ph-duotone ph-cpu"></i>
                            <span>Default</span>
                        </a>
                    </div>
                </li>
                <!-- start User chat to CA -->
				@if(Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
				@php
					$companyId = currentOwnerId();

					$chatNotifications = DB::table('chat_ca_messages')
						->leftJoin(
							'chat_ca_conversations',
							'chat_ca_messages.conversation_id',
							'=',
							'chat_ca_conversations.id'
						)
						->leftJoin(
							'users',
							'chat_ca_messages.sender_id',
							'=',
							'users.id'
						)
						->select(
							'chat_ca_conversations.id as conversation_id',
							'chat_ca_messages.message',
							'chat_ca_messages.created_at',
							'users.name as ca_name',
							DB::raw('COUNT(chat_ca_messages.id) as unread_count')
						)
						->where('chat_ca_conversations.company_id', $companyId)
						->where('chat_ca_messages.sender_type', 'ca')
						->where('chat_ca_messages.is_read', 0)
						->groupBy(
							'chat_ca_conversations.id',
							'chat_ca_messages.message',
							'chat_ca_messages.created_at',
							'users.name'
						)
						->orderBy('chat_ca_messages.id', 'DESC')
						->get();
				@endphp

				<li class="dropdown pc-h-item">
					<a class="pc-head-link dropdown-toggle arrow-none me-0"
					   data-bs-toggle="dropdown"
					   href="#" title="Messages from CA"
					   role="button"> <i class="ph-duotone ph-chat-circle-dots"></i>
						<span class="badge bg-danger pc-h-badge" @if(count($chatNotifications) == 0) style="display: none;" @endif>{{ count($chatNotifications) }}</span>
					</a>

					<div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
						<div class="dropdown-header d-flex align-items-center justify-content-between">
							<h5 class="m-0">CA Messages</h5>
						</div>
						<div class="dropdown-body" style="max-height:400px;overflow-y:auto;">
							@forelse($chatNotifications as $chat)
								<div class="border-bottom p-3 chat-notification-item open-company-chat" style="cursor:pointer;"
									 data-conversation="{{ $chat->conversation_id }}">
									<div class="d-flex align-items-start">
										<div class="flex-grow-1">
											<h6 class="mb-1">
												{{ $chat->ca_name }}
											</h6>
											<p class="mb-1 text-muted small">
												{{ \Illuminate\Support\Str::limit($chat->message, 60) }}
											</p>
											<small class="text-muted">
												{{ \Carbon\Carbon::parse($chat->created_at)->diffForHumans() }}
											</small>
										</div>
										<span class="badge bg-danger">
											{{ $chat->unread_count }}
										</span>
									</div>
								</div>
							@empty
								<div class="text-center p-4 text-muted">
									No unread messages
								</div>
							@endforelse
						</div>
					</div>
				</li>
				@endif
				<!-- end User chat to CA -->
				
                <!-- Notification -->
                <li class="dropdown pc-h-item">
                    @if (Auth::user())
                    <?php
                    $userId = Auth::user()->id;
                    $notifications = Helper::getNotification($userId);
                    ?>
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ph-duotone ph-bell"></i>
                        <span class="badge bg-success pc-h-badge notiCount" @if(!$notifications || count((array)$notifications) == 0) style="display: none;" @endif><?php echo count((array)$notifications); ?></span>
                    </a>
                    @endif
                    <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Notifications</h5>
                        </div>
                        <div class="dropdown-body text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 235px)">
                            <ul class="list-group list-group-flush notification-list w-100">
                                <?php if ($notifications && count((array)$notifications) > 0) {
                                    foreach ($notifications as $k => $notiVal) {
                                ?>
                                        <li class="list-group-item py-2">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0">
                                                    <div class="avtar avtar-s bg-light-primary">
                                                        @if(isset($notiVal->avatar) && $notiVal->avatar)
                                                        <img src="{{ asset($notiVal->avatar) }}" alt="user-image" class="img-fluid rounded-circle">
                                                        @else
                                                        <i class="ph-duotone ph-bell-ringing f-18"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="flex-grow-1 me-2">
                                                            <h6 class="mb-0 fw-medium">{{ $notiVal->noti_title ?? 'Notification' }}</h6>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <span class="text-sm text-muted">{{ $notiVal->created_at }}</span>
                                                        </div>
                                                    </div>
                                                    <p class="text-muted mb-0 mt-1 small">{{ $notiVal->msg }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    <?php }
                                } else { ?>
                                    <li class="list-group-item py-4 text-center empty-notification">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="mb-2">
                                                <i class="ph-duotone ph-bell-slash f-24 text-muted"></i>
                                            </div>
                                            <p class="mb-0 text-muted">No new notifications</p>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="dropdown-footer">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="d-grid"><a href="{{ url('/view-all-notification') }}" class="btn btn-primary">See All Notifications</a></div>
                                </div>
                                <div class="col-6">
                                    <div class="d-grid"><button class="btn btn-outline-secondary clear-noti" onClick="clearNoti(<?php echo $userId ?>)">Mark all as read</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- Profile -->
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                        <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar" />
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Profile</h5>
                        </div>
                        <div class="dropdown-body">
                            <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
                                <ul class="list-group list-group-flush w-100">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="wid-50 rounded-circle" />
                                            </div>
                                            <div class="flex-grow-1 mx-3">
                                                <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                                                <a class="link-primary" href="mailto:{{ Auth::user()->email }}">{{ Auth::user()->email }}</a>
                                            </div>
                                            <span class="badge bg-primary">PRO</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        @if (Auth::user()->u_type==1)
                                        <a href="{{ route('editProfile') }}" class="dropdown-item">
                                            <span class="d-flex align-items-center">
                                                <i class="ph-duotone ph-user-circle"></i>
                                                <span>Edit profile</span>
                                            </span>
                                        </a>
                                        @elseif (Auth::user()->u_type==2)
                                        <a href="{{ route('editProfile') }}" class="dropdown-item">
                                            <span class="d-flex align-items-center">
                                                <i class="ph-duotone ph-user-circle"></i>
                                                <span>Edit profile</span>
                                            </span>
                                        </a>
                                        @else
                                        <a href="{{ route ('editProfile') }}" class="dropdown-item">
                                            <span class="d-flex align-items-center">
                                                <i class="ph-duotone ph-user-circle"></i>
                                                <span>Edit profile</span>
                                            </span>
                                        </a>
                                        @endif
                                        <!--<a href="{{ route ('changepassword')}}" class="dropdown-item">
                                            <span class="d-flex align-items-center">
                                                <i class="ph-duotone ph-key"></i>
                                                <span>Change password</span>
                                            </span>
                                        </a>-->

                                    </li>
									@if (Auth::user()->u_type==2)
                                    <li class="list-group-item">
                                        <a href="{{ route('user.Plans') }}" class="dropdown-item">
                                            <span class="d-flex align-items-center">
                                                <i class="ph-duotone ph-star text-warning"></i>
                                                <span>Change Subscription Plan</span>
                                                <span class="badge bg-light-success border border-success ms-2">NEW</span>
                                            </span>
                                        </a>
                                    </li>
									@endif
                                    <li class="list-group-item">
                                        @if (!Auth::user())
                                        <a href="{{ url('/logout') }}" class="dropdown-item">
                                            <span class="d-flex align-items-center">
                                                <i class="ph-duotone ph-power"></i> <span>Login</span>
                                            </span>
                                        </a>
                                        @else
                                        <a href="{{ url('/logout') }}" class="dropdown-item">
                                            <span class="d-flex align-items-center">
                                                <i class="ph-duotone ph-power"></i> <span>Logout</span>
                                            </span>
                                        </a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>