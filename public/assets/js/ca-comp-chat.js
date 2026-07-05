let currentConversationId = null;

let chatInterval = null;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/*
|--------------------------------------------------------------------------
| FILE PREVIEW + VALIDATION
|--------------------------------------------------------------------------
*/

$('#chatFile').change(function () {

    let file = this.files[0];

    if (!file) {

        $('#selectedFileName')
            .addClass('d-none')
            .text('');

        return;
    }

    // Max 2MB validation
    let maxSize = 2 * 1024 * 1024;

    if (file.size > maxSize) {
        alert('Maximum file size allowed is 2 MB');
        $('#chatFile').val('');
        $('#selectedFileName')
            .addClass('d-none')
            .text('');

        return;
    }

    // Show file name
    $('#selectedFileName')
        .removeClass('d-none')
        .text(file.name);

});

/*
|--------------------------------------------------------------------------
| START CHAT
|--------------------------------------------------------------------------
*/

$(document).on('click', '.start-chat', function () {

    let companyId = $(this).data('company');

    let caId = $(this).data('ca');

    $.ajax({

        url: '/chat/start',

        type: 'POST',

        data: {

            ca_id: caId,

            company_id: companyId
        },

        success: function (res) {

            currentConversationId = res.conversation_id;

            $('#chatModal').modal('show');

            loadMessages();

            markAsRead();
        }
    });

});


/*
|--------------------------------------------------------------------------
| SEND MESSAGE
|--------------------------------------------------------------------------
*/

$('#sendMessageBtn').click(function () {

    sendMessage();

});


$('#chatMessage').keypress(function (e) {

    if (e.which == 13) {

        sendMessage();
    }

});


function sendMessage()
{

    let message = $('#chatMessage').val();
    let file = $('#chatFile')[0].files[0];
    if (message.trim() == '' && !file) {
        return;
    }

    // 2MB validation again
    if (file) {
        let maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('Maximum file size allowed is 2 MB');
            return;
        }
    }

    let formData = new FormData();
    formData.append(
        'conversation_id',
        currentConversationId
    );

    formData.append(
        'message',
        message
    );

    if (file) {
        formData.append(
            'file',
            file
        );
    }

    $.ajax({

        url: '/chat/send',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            $('#chatMessage').val('');
            $('#chatFile').val('');
            // Clear file name
            $('#selectedFileName')
                .addClass('d-none')
                .text('');

            loadMessages();
        }
    });
}


/*
|--------------------------------------------------------------------------
| LOAD MESSAGES
|--------------------------------------------------------------------------
*/

function loadMessages()
{

    if (!currentConversationId) {
        return;
    }

    $.get(

        '/chat/messages/' + currentConversationId,

        function (res) {

            let html = '';

            $.each(res.messages, function (i, msg) {

                html += `
                
                    <div class="chat-message ${msg.sender_type}">
                    
                        <div class="chat-bubble">
                `;

                // Message
                if (msg.message) {

                    html += `
                        <div class="chat-text">
                            ${msg.message}
                        </div>
                    `;
                }

                // File
                if (msg.file_path) {

                    let extension = msg.file_path.split('.').pop().toLowerCase();

                    // Image Preview
                    if (
                        extension == 'jpg' ||
                        extension == 'jpeg' ||
                        extension == 'png' ||
                        extension == 'gif' ||
                        extension == 'webp'
                    ) {

                        html += `
                        
                            <div class="mt-2">

                                <a href="/${msg.file_path}"
                                   target="_blank">

                                    <img src="/${msg.file_path}"
                                         class="chat-image-preview">

                                </a>

                            </div>
                        `;
                    }
                    else {

                        html += `
                        
                            <div class="mt-2">

                                <a href="/${msg.file_path}"
                                   target="_blank"
                                   class="chat-file-link">

                                   <i class="ph ph-file-text"></i>

                                   View Document

                                </a>

                            </div>
                        `;
                    }
                }

                html += `
                
                            <div class="chat-time">

                                ${formatDate(msg.created_at)}

                            </div>

                        </div>

                    </div>
                `;
            });

            $('#chatMessages').html(html);

            scrollChatBottom();
        }
    );
}


/*
|--------------------------------------------------------------------------
| SCROLL
|--------------------------------------------------------------------------
*/

function scrollChatBottom()
{

    let chatBox = $('#chatMessages');

    chatBox.scrollTop(
        chatBox[0].scrollHeight
    );

}


/*
|--------------------------------------------------------------------------
| AUTO REFRESH
|--------------------------------------------------------------------------
*/

$('#chatModal').on('shown.bs.modal', function () {

    loadMessages();

    chatInterval = setInterval(function () {
        loadMessages();
        markAsRead();
    }, 10000);

});


$('#chatModal').on('hidden.bs.modal', function () {

    clearInterval(chatInterval);

});


/*
|--------------------------------------------------------------------------
| MARK AS READ
|--------------------------------------------------------------------------
*/

function markAsRead()
{
    $.ajax({
        url: '/chat/read/' + currentConversationId,
        type: 'POST'
    });
}


/*
|--------------------------------------------------------------------------
| NOTIFICATION COUNT
|--------------------------------------------------------------------------
*/

function loadUnreadCount()
{
    $.get('/chat/unread-count', function (res) {
        $('#chatNotificationCount').text(
            res.count
        );
    });

}


loadUnreadCount();

setInterval(function () {

    loadUnreadCount();

}, 10000);


/*
|--------------------------------------------------------------------------
| DATE FORMAT
|--------------------------------------------------------------------------
*/

function formatDate(dateString)
{
    let date = new Date(dateString);

    let day = String(date.getDate()).padStart(2, '0');

    let month = String(date.getMonth() + 1).padStart(2, '0');

    let year = date.getFullYear();

    let hours = date.getHours();

    let minutes = String(date.getMinutes()).padStart(2, '0');

    let ampm = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12;

    hours = hours ? hours : 12;

    return `${day}-${month}-${year} ${hours}:${minutes} ${ampm}`;
}


//Company side open chat
$(document).on('click', '.open-company-chat', function () {

    currentConversationId = $(this).data('conversation');

    $('#chatModal').modal('show');

    loadMessages();

    markAsRead();

    startAutoRefresh();

});

let chatTime = null;

function startAutoRefresh()
{
    clearInterval(chatTime);

    chatTime = setInterval(function () {

        if ($('#chatModal').hasClass('show')) {

            loadMessages();

        }

    }, 10000);
}

$('#chatModal').on('hidden.bs.modal', function () {

    clearInterval(chatTime);

});