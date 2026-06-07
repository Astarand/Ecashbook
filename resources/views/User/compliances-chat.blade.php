@extends('App.Layout')

@section('container')
<div class="pc-content compliances-chat-page">
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="chat-wrapper">
                        <div class="offcanvas-xxl offcanvas-start chat-offcanvas" tabindex="-1" id="offcanvas_User_list">
                            <div class="offcanvas-header">
                                <button
                                    class="btn-close"
                                    data-bs-dismiss="offcanvas"
                                    data-bs-target="#offcanvas_User_list"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="chat-content">
                            <div class="d-flex align-items-center mb-3">
                                <ul class="list-inline me-auto mb-0">
                                    <li class="list-inline-item">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="chat-avtar">
                                                    <img class="rounded-circle img-fluid wid-40" src="../assets/images/user/avatar-5.jpg" alt="User image" />
                                                    <i class="chat-badge bg-success"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 mx-3 d-none d-sm-inline-block">
                                                <h6 class="mb-0">Alene</h6>
                                                <span class="text-sm text-muted">ui/ux designer</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card bg-body shadow-none mb-0">
                                <div class="scroll-block chat-message">
                                    <div class="card-body">
                                        <div class="message-out">
                                            <div class="d-flex align-items-end flex-column">
                                                <p class="mb-1 text-muted"><small>9h ago</small></p>
                                                <div class="message d-flex align-items-end flex-column">
                                                    <div class="d-flex align-items-center mb-1 chat-msg">
                                                        <div class="flex-shrink-0">
                                                            <ul class="list-inline ms-auto mb-0 chat-msg-option">
                                                                <li class="list-inline-item">
                                                                    <div class="dropdown">
                                                                        <a
                                                                            class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none"
                                                                            href="#"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="ti ti-dots-vertical f-18"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-back-up"></i> Reply</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-forward-up"></i> Forward</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-copy"></i> Copy</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-trash"></i> Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                        <i class="ti ti-edit-circle f-18"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="msg-content bg-primary">
                                                                <p class="mb-0">Hi...Henny!!</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-1 chat-msg">
                                                        <div class="flex-shrink-0">
                                                            <ul class="list-inline ms-auto mb-0 chat-msg-option">
                                                                <li class="list-inline-item">
                                                                    <div class="dropdown">
                                                                        <a
                                                                            class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none"
                                                                            href="#"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="ti ti-dots-vertical f-18"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-back-up"></i> Reply</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-forward-up"></i> Forward</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-copy"></i> Copy</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-trash"></i> Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                        <i class="ti ti-edit-circle f-18"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="msg-content bg-primary">
                                                                <p class="mb-0">How can i cap you today?</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="message-in">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="chat-avtar">
                                                        <img class="rounded-circle img-fluid wid-40" src="../assets/images/user/avatar-3.jpg" alt="User image" />
                                                        <i class="chat-badge bg-success"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 mx-3">
                                                    <div class="d-flex align-items-start flex-column">
                                                        <p class="mb-1 text-muted">Agilulf Fuxg <small>11:23 AM</small></p>
                                                        <div class="message d-flex align-items-start flex-column">
                                                            <div class="d-flex align-items-center mb-1 chat-msg">
                                                                <div class="flex-grow-1 me-3">
                                                                    <div class="msg-content card mb-0">
                                                                        <p class="mb-0">Hey.. Bill</p>
                                                                    </div>
                                                                </div>
                                                                <div class="flex-shrink-0">
                                                                    <ul class="list-inline ms-auto mb-0 chat-msg-option">
                                                                        <li class="list-inline-item">
                                                                            <div class="dropdown">
                                                                                <a
                                                                                    class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none"
                                                                                    href="#"
                                                                                    data-bs-toggle="dropdown"
                                                                                    aria-haspopup="true"
                                                                                    aria-expanded="false">
                                                                                    <i class="ti ti-dots-vertical f-18"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-arrow-back-up"></i> Reply</a>
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-arrow-forward-up"></i> Forward</a>
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-copy"></i> Copy</a>
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-trash"></i> Delete</a>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        <li class="list-inline-item">
                                                                            <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                                <i class="ti ti-edit-circle f-18"></i>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-1 chat-msg">
                                                                <div class="flex-grow-1 me-3">
                                                                    <div class="msg-content card mb-0">
                                                                        <p class="mb-0">nice to meet you!</p>
                                                                    </div>
                                                                </div>
                                                                <div class="flex-shrink-0">
                                                                    <ul class="list-inline ms-auto mb-0 chat-msg-option">
                                                                        <li class="list-inline-item">
                                                                            <div class="dropdown">
                                                                                <a
                                                                                    class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none"
                                                                                    href="#"
                                                                                    data-bs-toggle="dropdown"
                                                                                    aria-haspopup="true"
                                                                                    aria-expanded="false">
                                                                                    <i class="ti ti-dots-vertical f-18"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-arrow-back-up"></i> Reply</a>
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-arrow-forward-up"></i> Forward</a>
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-copy"></i> Copy</a>
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-trash"></i> Delete</a>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        <li class="list-inline-item">
                                                                            <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                                <i class="ti ti-edit-circle f-18"></i>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="message-out">
                                            <div class="d-flex align-items-end flex-column">
                                                <p class="mb-1 text-muted"><small>9h ago</small></p>
                                                <div class="message d-flex align-items-end flex-column">
                                                    <div class="d-flex align-items-center mb-1 chat-msg">
                                                        <div class="flex-shrink-0">
                                                            <ul class="list-inline ms-auto mb-0 chat-msg-option">
                                                                <li class="list-inline-item">
                                                                    <div class="dropdown">
                                                                        <a
                                                                            class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none"
                                                                            href="#"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="ti ti-dots-vertical f-18"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-back-up"></i> Reply</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-forward-up"></i> Forward</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-copy"></i> Copy</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-trash"></i> Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                        <i class="ti ti-edit-circle f-18"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="msg-content bg-primary">
                                                                <p class="mb-0">Hi...Henny!!</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-1 chat-msg">
                                                        <div class="flex-shrink-0">
                                                            <ul class="list-inline ms-auto mb-0 chat-msg-option">
                                                                <li class="list-inline-item">
                                                                    <div class="dropdown">
                                                                        <a
                                                                            class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none"
                                                                            href="#"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="ti ti-dots-vertical f-18"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-back-up"></i> Reply</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-forward-up"></i> Forward</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-copy"></i> Copy</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-trash"></i> Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                        <i class="ti ti-edit-circle f-18"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="msg-content bg-primary">
                                                                <p class="mb-0">Are u listening me ?</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-1 chat-msg">
                                                        <div class="flex-shrink-0">
                                                            <ul class="list-inline ms-auto mb-0 chat-msg-option">
                                                                <li class="list-inline-item">
                                                                    <div class="dropdown">
                                                                        <a
                                                                            class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none"
                                                                            href="#"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="ti ti-dots-vertical f-18"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-back-up"></i> Reply</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-forward-up"></i> Forward</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-copy"></i> Copy</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-trash"></i> Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                        <i class="ti ti-edit-circle f-18"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="msg-content bg-primary">
                                                                <p class="mb-0">How can i cap you today?</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="message-in">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="chat-avtar">
                                                        <img class="rounded-circle img-fluid wid-40" src="../assets/images/user/avatar-3.jpg" alt="User image" />
                                                        <i class="chat-badge bg-success"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 mx-3">
                                                    <div class="d-flex align-items-start flex-column">
                                                        <p class="mb-1 text-muted">Agilulf Fuxg <small>11:23 AM</small></p>
                                                        <div class="message d-flex align-items-start flex-column">
                                                            <div class="d-flex align-items-center mb-1 chat-msg">
                                                                <div class="flex-grow-1 me-3">
                                                                    <div class="msg-content card mb-0">
                                                                        <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                                                                            the industry's standard dummy text ever since the 1500s.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="flex-shrink-0">
                                                                    <ul class="list-inline ms-auto mb-0 chat-msg-option">
                                                                        <li class="list-inline-item">
                                                                            <div class="dropdown">
                                                                                <a
                                                                                    class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none"
                                                                                    href="#"
                                                                                    data-bs-toggle="dropdown"
                                                                                    aria-haspopup="true"
                                                                                    aria-expanded="false">
                                                                                    <i class="ti ti-dots-vertical f-18"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-arrow-back-up"></i> Reply</a>
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-arrow-forward-up"></i> Forward</a>
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-copy"></i> Copy</a>
                                                                                    <a class="dropdown-item" href="#"><i class="ti ti-trash"></i> Delete</a>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        <li class="list-inline-item">
                                                                            <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                                <i class="ti ti-edit-circle f-18"></i>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="message-out">
                                            <div class="d-flex align-items-end flex-column">
                                                <p class="mb-1 text-muted"><small>9h ago</small></p>
                                                <div class="message d-flex align-items-end flex-column">
                                                    <div class="d-flex align-items-center mb-1 chat-msg">
                                                        <div class="flex-shrink-0">
                                                            <ul class="list-inline ms-auto mb-0 chat-msg-option">
                                                                <li class="list-inline-item">
                                                                    <div class="dropdown">
                                                                        <a
                                                                            class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none"
                                                                            href="#"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="ti ti-dots-vertical f-18"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-back-up"></i> Reply</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-arrow-forward-up"></i> Forward</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-copy"></i> Copy</a>
                                                                            <a class="dropdown-item" href="#"><i class="ti ti-trash"></i> Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                        <i class="ti ti-edit-circle f-18"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="msg-content bg-primary">
                                                                <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                                                    industry's standard dummy text ever since the 1500s.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-top pt-2 px-3 pb-0">
                                <div class="input-group align-items-center">
                                    <ul class="list-inline me-auto mb-0">
                                        <li class="list-inline-item">
                                            <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                <i class="ti ti-paperclip f-18"></i>
                                            </a>
                                        </li>
                                    </ul>
                                    <input type="text" class="form-control shadow-none border-0 bg-transparent" placeholder="Type a Message" />
                                    <ul class="list-inline ms-auto mb-0">
                                        <li class="list-inline-item">
                                            <a href="#" class="avtar avtar-s rounded-circlen btn btn-primary">
                                                <i class="ti ti-send f-18"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="offcanvas-xxl offcanvas-end chat-offcanvas" tabindex="-1" id="offcanvas_User_info">
                            <div class="offcanvas-header">
                                <button
                                    class="btn-close"
                                    data-bs-dismiss="offcanvas"
                                    data-bs-target="#offcanvas_User_info"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body p-0">
                                <div id="chat-user_info" class="collapse collapse-horizontal">
                                    <div class="chat-user_info">
                                        <div class="card bg-transparent shadow-none border-0 mb-0">
                                            <div class="text-center card-body position-relative p-3 pb-0 px-0">
                                                <h5 class="text-start">Profile View</h5>
                                                <div class="position-absolute end-0 top-0 p-2 d-none d-xxl-inline-flex">
                                                    <a
                                                        href="#"
                                                        class="avtar avtar-xs btn-link-danger btn-pc-default"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#chat-user_info">
                                                        <i class="ti ti-x f-16"></i>
                                                    </a>
                                                </div>
                                                <div class="chat-avtar d-inline-flex mx-auto">
                                                    <img class="rounded-circle img-fluid wid-100" src="../assets/images/user/avatar-5.jpg" alt="User image" />
                                                </div>
                                                <h5 class="mb-0">Alene</h5>
                                                <p class="text-muted text-sm">DM on <a href="#" class="link-primary">@williambond</a></p>
                                                <ul class="list-inline ms-auto">
                                                    <li class="list-inline-item">
                                                        <a href="#" class="avtar avtar-s btn-link-secondary">
                                                            <i class="ti ti-phone-call f-18"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a href="#" class="avtar avtar-s btn-link-secondary">
                                                            <i class="ti ti-message-circle f-18"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a href="#" class="avtar avtar-s btn-link-secondary">
                                                            <i class="ti ti-video f-18"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="scroll-block">
                                                <div class="card-body p-0">
                                                    <div class="form-check form-switch d-flex align-items-center justify-content-between p-0">
                                                        <label class="form-check-label h5 mb-0" for="customSwitchemlnot1">Notification</label>
                                                        <input
                                                            class="form-check-input h5 mb-0 position-relative"
                                                            type="checkbox"
                                                            id="customSwitchemlnot1"
                                                            checked="" />
                                                    </div>
                                                    <hr class="my-3 border border-secondary-subtle" />
                                                    <a class="btn border-0 p-0 text-start w-100" data-bs-toggle="collapse" href="#filtercollapse1">
                                                        <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                                        <h5 class="mb-0">Information</h5>
                                                    </a>
                                                    <div class="collapse show" id="filtercollapse1">
                                                        <div class="py-3">
                                                            <div class="d-flex align-items-center justify-content-start mb-2">
                                                                <i class="ph-duotone ph-map-pin"></i>
                                                                <p class="mb-0 text-muted ms-2">32188 Sips Parkways, U.S</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-start mb-2">
                                                                <i class="ph-duotone ph-envelope-open"></i>
                                                                <p class="mb-0 text-muted ms-2">Keefe@codedtheme.com</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-start mb-2">
                                                                <i class="ph-duotone ph-phone-call"></i>
                                                                <p class="mb-0 text-muted ms-2">995-250-1803</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-start mb-2">
                                                                <i class="ph-duotone ph-calendar-blank"></i>
                                                                <p class="mb-0 text-muted ms-2">30, Nov 2021</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-start mb-2">
                                                                <i class="ph-duotone ph-globe-hemisphere-east"></i>
                                                                <p class="mb-0 text-muted ms-2">India</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-start mb-2">
                                                                <i class="ph-duotone ph-briefcase"></i>
                                                                <p class="mb-0 text-muted ms-2"><a href="https://ecashbook.in" class="link-primary">ecashbook.in</a></p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-start">
                                                                <i class="ph-duotone ph-radio-button"></i>
                                                                <p class="mb-0 text-muted ms-2"><span class="badge bg-light-warning text-warning">UI/UX Designer</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="my-3 border border-secondary-subtle" />
                                                    <a class="btn border-0 p-0 text-start w-100" data-bs-toggle="collapse" href="#filtercollapse2">
                                                        <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                                        <h5 class="mb-0">File type</h5>
                                                    </a>
                                                    <div class="collapse show" id="filtercollapse2">
                                                        <div class="py-3">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="flex-shrink-0">
                                                                    <a href="#" class="avtar avtar-s btn-light-success">
                                                                        <i class="ti ti-file-text f-20"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h6 class="mb-0">Document</h6>
                                                                    <span class="text-muted text-sm">123 files, 193MB</span>
                                                                </div>
                                                                <div class="flex-shrink-0">
                                                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                        <i class="ti ti-chevron-right f-16"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="flex-shrink-0">
                                                                    <a href="#" class="avtar avtar-s btn-light-danger">
                                                                        <i class="ti ti-photo f-20"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h6 class="mb-0">Photos</h6>
                                                                    <span class="text-muted text-sm">53 files, 321MB</span>
                                                                </div>
                                                                <div class="flex-shrink-0">
                                                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                        <i class="ti ti-chevron-right f-16"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="flex-shrink-0">
                                                                    <a href="#" class="avtar avtar-s btn-light-primary">
                                                                        <i class="ti ti-id f-20"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h6 class="mb-0">Other</h6>
                                                                    <span class="text-muted text-sm">49 files, 193MB</span>
                                                                </div>
                                                                <div class="flex-shrink-0">
                                                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                                                        <i class="ti ti-chevron-right f-16"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

@endsection
