<!-- =========================
CHAT MODAL
========================= -->

<link rel="stylesheet"
      href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">

<link rel="stylesheet"
      href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css">

<link rel="stylesheet"
      href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/duotone/style.css">


<div class="modal fade" id="chatModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-end modal-xl">

        <div class="modal-content chat-modal-content">
            <!-- HEADER -->
            <div class="modal-header chat-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="chat-avatar">
                        <i class="ph-duotone ph-chats-circle"></i>
                    </div>

                    <div>
                        <h5 class="mb-0 fw-bold">
                            CA Support Chat
                        </h5>
                        <small class="text-muted">
                            Real-time communication
                        </small>
                    </div>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body p-0">
                <div id="chatMessages" class="chat-messages-wrapper"> </div>
            </div>

            <!-- FOOTER -->
            <div class="chat-footer">
                <!-- FILE BUTTON -->
                <label class="chat-action-btn mb-0">
                    <i class="ph-duotone ph-paperclip"></i>
                    <input type="file" id="chatFile" hidden>
                </label>
				
				<div id="selectedFileName" class="selected-file-name d-none">
					No file selected
				</div>

                <!-- INPUT -->
                <div class="chat-input-wrapper">
                    <input type="text"
                           id="chatMessage"
                           class="chat-input"
                           placeholder="Type your message...">
                </div>

                <!-- SEND -->
                <button type="button"
                        class="chat-send-btn"
                        id="sendMessageBtn">
                    <i class="ph-duotone ph-paper-plane-tilt"></i>
                </button>
            </div>
        </div>
    </div>
</div>



<style>

/* =========================
MODAL
========================= */

.chat-modal-content{

    border:none;

    border-radius:24px;

    overflow:hidden;

    box-shadow:
        0 20px 60px rgba(15,23,42,.12);

    background:#fff;
}


/* =========================
HEADER
========================= */

.chat-header{

    padding:20px 24px;

    background:#ffffff;

    border-bottom:1px solid #eef2f7;
}


/* =========================
AVATAR
========================= */

.chat-avatar{

    width:52px;

    height:52px;

    border-radius:16px;

    background:#eef2ff;

    display:flex;

    align-items:center;

    justify-content:center;
}

.chat-avatar i{

    font-size:28px;

    color:#4f46e5;

    display:flex;

    align-items:center;

    justify-content:center;

    line-height:1;
}


/* =========================
Selected file name
========================= */

.selected-file-name{

    max-width:220px;

    overflow:hidden;

    text-overflow:ellipsis;

    white-space:nowrap;

    font-size:13px;

    color:#4b5563;

    background:#f3f4f6;

    padding:8px 12px;

    border-radius:10px;

    border:1px solid #e5e7eb;
}


/* =========================
MESSAGES AREA
========================= */

.chat-messages-wrapper{

    height:65vh;

    overflow-y:auto;

    padding:24px;

    background:#f8fafc;
}


/* =========================
MESSAGE
========================= */

.chat-message{

    margin-bottom:18px;

    display:flex;
}

.chat-message.ca{

    justify-content:flex-end;
}

.chat-message.company{

    justify-content:flex-start;
}


/* =========================
BUBBLE
========================= */

.chat-bubble{

    max-width:75%;

    padding:14px 18px;

    border-radius:20px;

    font-size:14px;

    position:relative;

    word-break:break-word;
}


/* CA */

.chat-message.ca .chat-bubble{

    background:linear-gradient(
        135deg,
        #6366f1,
        #4f46e5
    );

    color:#fff;

    border-bottom-right-radius:6px;
}


/* COMPANY */

.chat-message.company .chat-bubble{

    background:#ffffff;

    color:#111827;

    border-bottom-left-radius:6px;

    box-shadow:
        0 2px 12px rgba(15,23,42,.05);
}


/* =========================
TIME
========================= */

.chat-time{

    font-size:11px;

    margin-top:8px;

    opacity:.75;
}


/* =========================
FOOTER
========================= */

.chat-footer{

    padding:18px 22px;

    border-top:1px solid #eef2f7;

    background:#ffffff;

    display:flex;

    align-items:center;

    gap:14px;
}


/* =========================
FILE BUTTON
========================= */

.chat-action-btn{

    width:52px;

    height:52px;

    min-width:52px;

    border-radius:16px;

    background:#f3f4f6;

    border:1px solid #e5e7eb;

    display:flex;

    align-items:center;

    justify-content:center;

    cursor:pointer;

    transition:.2s ease;
}

.chat-action-btn i{

    font-size:22px;

    color:#4b5563;

    display:flex;

    align-items:center;

    justify-content:center;

    line-height:1;
}

.chat-action-btn:hover{

    background:#ede9fe;

    border-color:#ddd6fe;
}


/* =========================
INPUT WRAPPER
========================= */

.chat-input-wrapper{

    flex:1;
}


/* =========================
INPUT
========================= */

.chat-input{

    width:100%;

    height:52px;

    border-radius:16px;

    border:1px solid #e5e7eb;

    background:#f9fafb;

    padding:0 18px;

    font-size:14px;

    transition:.2s ease;
}

.chat-input:focus{

    border-color:#4f46e5;

    background:#fff;

    box-shadow:none;

    outline:none;
}


/* =========================
SEND BUTTON
========================= */

.chat-send-btn{

    width:54px;

    height:54px;

    min-width:54px;

    border:none;

    border-radius:18px;

    background:linear-gradient(
        135deg,
        #6366f1,
        #4f46e5
    );

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    transition:.2s ease;

    box-shadow:
        0 10px 20px rgba(79,70,229,.18);
}

.chat-send-btn i{

    font-size:22px;

    display:flex;

    align-items:center;

    justify-content:center;

    line-height:1;
}

.chat-send-btn:hover{

    transform:translateY(-2px);

    box-shadow:
        0 14px 28px rgba(79,70,229,.28);
}


/* =========================
IMAGE PREVIEW
========================= */

.chat-image-preview{

    width:220px;

    border-radius:14px;

    border:1px solid #e5e7eb;

    margin-top:8px;
}


/* =========================
FILE LINK
========================= */

.chat-file-link{

    display:inline-flex;

    align-items:center;

    gap:8px;

    padding:10px 14px;

    border-radius:12px;

    background:#f3f4f6;

    color:#111827;

    text-decoration:none;

    font-size:13px;

    font-weight:600;

    margin-top:8px;
}

.chat-file-link:hover{

    background:#e5e7eb;
}


/* =========================
SCROLLBAR
========================= */

.chat-messages-wrapper::-webkit-scrollbar{

    width:6px;
}

.chat-messages-wrapper::-webkit-scrollbar-thumb{

    background:#d1d5db;

    border-radius:20px;
}


/* =========================
MOBILE
========================= */

@media(max-width:768px){

    .modal-dialog{

        margin:0;
    }

    .chat-modal-content{

        height:100vh;

        border-radius:0;
    }

    .chat-messages-wrapper{

        height:calc(100vh - 160px);

        padding:16px;
    }

    .chat-footer{

        padding:14px;
    }

    .chat-action-btn{

        width:46px;
        height:46px;
        min-width:46px;
    }

    .chat-send-btn{

        width:48px;
        height:48px;
        min-width:48px;
    }

    .chat-input{

        height:48px;
    }

}

</style>



