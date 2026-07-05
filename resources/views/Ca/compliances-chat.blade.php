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
                        <div class="chat-content">
                            <div class="d-flex align-items-center mb-3">
                                <ul class="list-inline me-auto mb-0">
                                    <li class="list-inline-item">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 mx-3">
                                                <h6 class="mb-0">Message to {{ isset($quotes->caName)?$quotes->caName:$quotes->compName }}</h6>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="col-auto">
                                    <a href="{{ url('/ca-compliances-list') }}" class="btn btn-primary">
                                        <span data-toggle="tooltip" data-placement="top" title="Back">BACK</span>
                                    </a>
                                </div>
                            </div>
                            <div class="card bg-body shadow-none mb-0">
                                <div class="scroll-block customScrollBar chat-message">
                                    <!-- Chat Messages Section -->
                                    <div class="chatConversationSection">
                                        @foreach($quotes->messages as $message)

                                        <?php
                                        //for same user
										$userId = currentOwnerId();
										
                                        if ($message->from_user_id == $userId) {
                                            if ($message->attached != "") {
                                                $ext = pathinfo($message->attached, PATHINFO_EXTENSION);
                                                $filepath = asset('/uploads/chat/' . $message->attached);
                                                if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png') {
                                        ?>
                                                    <div class="message-out">
                                                        <div class="d-flex align-items-end flex-column">
                                                            <p class="mb-1 text-muted"><small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small></p>
                                                            <div class="message d-flex align-items-end flex-column">
                                                                <div class="d-flex align-items-center mb-1 chat-msg">
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <div class="msg-content bg-primary">
                                                                            <a href="<?php echo $filepath; ?>" target="_blank"><img src="<?php echo $filepath; ?>" alt="" class="img-fluid"></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                } else {
                                                ?>
                                                    <div class="message-out">
                                                        <div class="d-flex align-items-end flex-column">
                                                            <p class="mb-1 text-muted"><small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small> <i class="ph-duotone ph-user-circle"></i></p>
                                                            <div class="message d-flex align-items-end flex-column">
                                                                <div class="d-flex align-items-center mb-1 chat-msg">
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <div class="msg-content bg-primary">
                                                                            <div class="fileAttechmentInner">
                                                                                <div class="file-icon">
                                                                                    <?php
                                                                                    $icon = '';
                                                                                    switch (strtolower($ext)) {
                                                                                        case 'pdf':
                                                                                            $icon = '<i class="ti ti-file-text"></i>';
                                                                                            break;
                                                                                        case 'doc':
                                                                                        case 'docx':
                                                                                            $icon = '<i class="ti ti-file-text"></i>';
                                                                                            break;
                                                                                        case 'xls':
                                                                                        case 'xlsx':
                                                                                            $icon = '<i class="ti ti-file-spreadsheet"></i>';
                                                                                            break;
                                                                                        case 'ppt':
                                                                                        case 'pptx':
                                                                                            $icon = '<i class="ti ti-presentation"></i>';
                                                                                            break;
                                                                                        case 'zip':
                                                                                        case 'rar':
                                                                                            $icon = '<i class="ti ti-file-zip"></i>';
                                                                                            break;
                                                                                        case 'txt':
                                                                                            $icon = '<i class="ti ti-file-text"></i>';
                                                                                            break;
                                                                                        default:
                                                                                            $icon = '<i class="ti ti-file"></i>';
                                                                                    }
                                                                                    echo $icon;
                                                                                    ?>
                                                                                </div>
                                                                                <div class="file-details">
                                                                                    <div class="file-name"><?php echo $message->attached; ?></div>
                                                                                </div>
                                                                                <a href="<?php echo $filepath; ?>" target="_blank" class="download-btn">
                                                                                    <i class="ti ti-download"></i> Download
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            }

                                            if ($message->chat_message != "") {
                                                ?>
                                                <div class="message-out">
                                                    <div class="d-flex align-items-end flex-column">
                                                        <p class="mb-1 text-muted"><small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small> <i class="ph-duotone ph-user-circle"></i></p>
                                                        <div class="message d-flex align-items-end flex-column">
                                                            <div class="d-flex align-items-center mb-1 chat-msg">
                                                                <div class="flex-grow-1 ms-3">
                                                                    <div class="msg-content bg-primary">
                                                                        <p class="mb-0">{{ $message->chat_message }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            //for different user
                                            if ($message->attached != "") {
                                                $ext = pathinfo($message->attached, PATHINFO_EXTENSION);
                                                $filepath = asset('/uploads/chat/' . $message->attached);
                                                if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png') {
                                                ?>
                                                    <div class="message-in">
                                                        <div class="d-flex">
                                                            <div class="flex-grow-1 mx-3">
                                                                <div class="d-flex align-items-start flex-column">
                                                                    <p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small></p>
                                                                    <div class="message d-flex align-items-start flex-column">
                                                                        <div class="d-flex align-items-center mb-1 chat-msg">
                                                                            <div class="flex-grow-1 me-3">
                                                                                <div class="msg-content card mb-0">
                                                                                    <a href="<?php echo $filepath; ?>" target="_blank"><img src="<?php echo $filepath; ?>" alt="" class="img-fluid"></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                } else {
                                                ?>
                                                    <div class="message-in">
                                                        <div class="d-flex">
                                                            <div class="flex-grow-1 mx-3">
                                                                <div class="d-flex align-items-start flex-column">
                                                                    <p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small></p>
                                                                    <div class="message d-flex align-items-start flex-column">
                                                                        <div class="d-flex align-items-center mb-1 chat-msg">
                                                                            <div class="flex-grow-1 me-3">
                                                                                <div class="msg-content card mb-0">
                                                                                    <div class="fileAttechmentInner">
                                                                                        <div class="file-icon">
                                                                                            <?php
                                                                                            $icon = '';
                                                                                            switch (strtolower($ext)) {
                                                                                                case 'pdf':
                                                                                                    $icon = '<i class="ti ti-file-text"></i>';
                                                                                                    break;
                                                                                                case 'doc':
                                                                                                case 'docx':
                                                                                                    $icon = '<i class="ti ti-file-text"></i>';
                                                                                                    break;
                                                                                                case 'xls':
                                                                                                case 'xlsx':
                                                                                                    $icon = '<i class="ti ti-file-spreadsheet"></i>';
                                                                                                    break;
                                                                                                case 'ppt':
                                                                                                case 'pptx':
                                                                                                    $icon = '<i class="ti ti-presentation"></i>';
                                                                                                    break;
                                                                                                case 'zip':
                                                                                                case 'rar':
                                                                                                    $icon = '<i class="ti ti-file-zip"></i>';
                                                                                                    break;
                                                                                                case 'txt':
                                                                                                    $icon = '<i class="ti ti-file-text"></i>';
                                                                                                    break;
                                                                                                default:
                                                                                                    $icon = '<i class="ti ti-file"></i>';
                                                                                            }
                                                                                            echo $icon;
                                                                                            ?>
                                                                                        </div>
                                                                                        <div class="file-details">
                                                                                            <div class="file-name"><?php echo $message->attached; ?></div>
                                                                                        </div>
                                                                                        <a href="<?php echo $filepath; ?>" target="_blank" class="download-btn">
                                                                                            <i class="ti ti-download"></i> Download
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
                                                <?php
                                                }
                                            }

                                            if ($message->chat_message != "") {
                                                ?>
                                                <div class="message-in">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 mx-3">
                                                            <div class="d-flex align-items-start flex-column">
                                                                <p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>{{ date('d-m-Y h:i A', strtotime($message->timestamp)) }}</small></p>
                                                                <div class="message d-flex align-items-start flex-column">
                                                                    <div class="d-flex align-items-center mb-1 chat-msg">
                                                                        <div class="flex-grow-1 me-3">
                                                                            <div class="msg-content card mb-0">
                                                                                <p class="mb-0">{{ $message->chat_message }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                        @endforeach
                                    </div>
                                    <!-- End Chat Messages Section -->
                                </div>
                            </div>
                            <div class="card-footer border-top pt-2 px-3 pb-0">
                                <div class="fileAttechment" id="file_prev_attachment_section" style="display: none;"></div>
                                <form method="POST" action="javascript:void(0);" accept-charset="UTF-8" class="chat-form" id="chat-form" autocomplete="off" novalidate="novalidate">
                                    @csrf
                                    <input type="hidden" name="base_url" id="base_url" value="{{ url('/') }}">
                                    @if(isset($quotes->caid))
                                    <input type="hidden" name="to_user_id" id="to_user_id" value="{{ $quotes->caid }}">
                                    @else
                                    <input type="hidden" name="to_user_id" id="to_user_id" value="{{ $quotes->uid }}">
                                    @endif
                                    <input type="hidden" name="from_user_id" id="from_user_id" value="{{ $userId }}">
                                    <input type="hidden" name="c_qid" id="c_qid" value="{{ $quotes->id }}">

                                    <?php if ((Auth::user()->u_type == 1 || Auth::user()->u_type == 4) || (!empty($quotes->messages) && Auth::user()->u_type == 2)) { ?>
                                        <div class="input-group align-items-center">
                                            <ul class="list-inline me-auto mb-0">
                                                <li class="list-inline-item">
                                                    <a href="javascript:;" onclick="fetch_file()" class="avtar avtar-xs btn-link-secondary">
                                                        <i class="ti ti-paperclip f-18"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                            <input type="text" name="chat_message" id="chat-widget-message-text" class="form-control shadow-none border-0 bg-transparent" placeholder="Type a Message" />
                                            <input type="hidden" name="message_file" id="chat-widget-message-file" value="">
                                            <ul class="list-inline ms-auto mb-0">
                                                <li class="list-inline-item">
                                                    <button type="button" id="chat-btn" class="avtar avtar-s rounded-circlen btn btn-primary">
                                                        <i class="ti ti-send f-18"></i>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>

                                    <div class="message-container"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->

        <div style="display:none;">
            <form method="POST" action="javascript:void(0);" accept-charset="UTF-8" class="form-file-upload" id="form-file-upload" enctype="multipart/form-data">
                @csrf
                <input name="attachment_file" id="attachment_file" style="display:none" onchange="preview_file(this.files)" type="file">
            </form>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

@endsection

@section('page-script')
<script>
    // Ensure the chat initialize once the page is fully loaded
    $(document).ready(function() {
        // Handle click manually if needed
        $('#chat-btn').on('click', function() {
            console.log("Button clicked via inline script");
        });
    });
</script>
@endsection