var currentURL = window.location.href;

//Start for scroll down
let updateTicketScroll = () => {
    let element = document.querySelector(".customScrollBar.chat-message");
    if (element) {
        element.scrollTop = element.scrollHeight;
    }
};
//End for scroll down

// Function to format file size in KB, MB, etc.
function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";

    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB", "TB"];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
}

// Function to get document type and icon based on extension
function getDocumentTypeInfo(extension) {
    extension = extension.toLowerCase();
    let type, icon, fileType;

    switch (extension) {
        case "pdf":
            type = "pdf-preview";
            icon = '<i class="ti ti-file-text text-danger"></i>';
            fileType = "Adobe Acrobat Document";
            break;
        case "doc":
        case "docx":
            type = "doc-preview";
            icon = '<i class="ti ti-file-text text-primary"></i>';
            fileType = "Microsoft Word Document";
            break;
        case "xls":
        case "xlsx":
            type = "xls-preview";
            icon = '<i class="ti ti-file-spreadsheet text-success"></i>';
            fileType = "Microsoft Excel Document";
            break;
        case "ppt":
        case "pptx":
            type = "ppt-preview";
            icon = '<i class="ti ti-presentation text-warning"></i>';
            fileType = "Microsoft PowerPoint Document";
            break;
        case "zip":
        case "rar":
            type = "zip-preview";
            icon = '<i class="ti ti-file-zip text-warning"></i>';
            fileType = "Archive File";
            break;
        case "txt":
            type = "txt-preview";
            icon = '<i class="ti ti-file-text text-info"></i>';
            fileType = "Text Document";
            break;
        default:
            type = "default-preview";
            icon = '<i class="ti ti-file text-secondary"></i>';
            fileType = "Document";
    }

    return { type, icon, fileType };
}

// Function to create preview for different file types
function createTicketFilePreview(file, filepath, fileName) {
    const ext = fileName.split(".").pop().toLowerCase();
    const fileSize = formatFileSize(file ? file.size : 0);

    // For images
    if (["jpg", "jpeg", "png", "gif"].includes(ext)) {
        if (file) {
            // Create preview for new uploaded image
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const timestamp = new Date().toLocaleTimeString([], {
                        hour: "2-digit",
                        minute: "2-digit",
                    });
                    const preview = `
                        <div class="image-message-preview">
                            <img src="${e.target.result}" alt="Image Preview" class="img-fluid">
                            <div class="image-timestamp">${timestamp}</div>
                        </div>
                    `;
                    resolve(preview);
                };
                reader.readAsDataURL(file);
            });
        } else {
            // For existing image in chat
            const timestamp = new Date().toLocaleTimeString([], {
                hour: "2-digit",
                minute: "2-digit",
            });
            return Promise.resolve(`
                <div class="image-message-preview">
                    <img src="${filepath}" alt="Image" class="img-fluid">
                    <div class="image-timestamp">${timestamp}</div>
                </div>
            `);
        }
    }

    // For PDF files
    else if (ext === "pdf") {
        return Promise.resolve(`
            <div class="pdf-preview">
                <div class="pdf-info">
                    <div class="pdf-icon-small">
                        <i class="ti ti-file-text text-danger"></i>
                    </div>
                    <div class="pdf-name">${fileName}</div>
                    <div class="pdf-size">${fileSize}</div>
                </div>
                <div class="pdf-actions">
                    <a href="${filepath}" target="_blank" class="pdf-action">Open</a>
                    <a href="${filepath}" download="${fileName}" class="pdf-action">Save as...</a>
                </div>
            </div>
        `);
    }

    // For other document types (Word, Excel, PPT, etc.)
    else {
        const docInfo = getDocumentTypeInfo(ext);
        let bgColor = "#0078D7"; // Default blue
        let textColor = "#fff";

        // Set background color based on document type
        if (ext.toLowerCase() === "xlsx" || ext.toLowerCase() === "xls") {
            bgColor = "#217346"; // Excel green
        } else if (
            ext.toLowerCase() === "docx" ||
            ext.toLowerCase() === "doc"
        ) {
            bgColor = "#2B579A"; // Word blue
        } else if (
            ext.toLowerCase() === "pptx" ||
            ext.toLowerCase() === "ppt"
        ) {
            bgColor = "#D24726"; // PowerPoint orange
        }

        return Promise.resolve(`
            <div style="background-color: ${bgColor}; border-radius: 12px; padding: 15px; color: white; max-width: 300px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 16px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px;">${fileName}</div>
                <div style="font-size: 13px; opacity: 0.8;">${docInfo.fileType}</div>
                <div style="display: flex; margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 10px;">
                    <a href="${filepath}" target="_blank" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Open</a>
                    <a href="${filepath}" download="${fileName}" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Save as...</a>
                </div>
            </div>
        `);
    }
}

function fetch_file_ticket() {
    $("#attachment_file").trigger("click");
}

function preview_file_ticket(el) {
    var base_url = $("#base_url").val();
    var attachment_file = $("#attachment_file").prop("files")[0];
    var c_qid = $("#c_qid").val();

    if (attachment_file) {
        // Check file size (max 10MB)
        if (attachment_file.size > 10 * 1024 * 1024) {
            $("#reply-form .message-container").html(
                '<div class="err">File size exceeds 10MB limit. Please choose a smaller file.</div>'
            );
            setTimeout(function () {
                $("#reply-form .message-container div").fadeOut(
                    300,
                    function () {
                        $("#reply-form .message-container").html("");
                    }
                );
            }, 3000);
            return;
        }

        // Show loading indicator
        $("#file_prev_attachment_section")
            .css("display", "block")
            .html(
                '<div class="attachment-loading"><span class="spinner"></span> Uploading file...</div>'
            );

        // Create preview before upload
        const fileName = attachment_file.name;

        createTicketFilePreview(attachment_file, null, fileName).then(
            (previewHtml) => {
                $("#file_prev_attachment_section").html(
                    previewHtml +
                        '<span class="remove-file" onclick="remove_ticket_image(event)"><i class="ti ti-x"></i></span>'
                );

                // Upload the file
                var form_data = new FormData();
                form_data.append("attachment_file", attachment_file);
                form_data.append("c_qid", c_qid);
                $("#loader").show();

                $.ajax({
                    url: base_url + "/upload_file_ticket",
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $("#loader").hide();
                        if (response.class == "succ") {
                            $("#chat-widget-message-file").val(
                                response.filename
                            );
                        } else {
                            $("#file_prev_attachment_section")
                                .css("display", "none")
                                .html("");
                            $.each(response, function (idx, obj) {
                                $("#reply-form .message-container").html(
                                    '<div class="err">' + obj + "</div>"
                                );
                            });
                            setTimeout(function () {
                                $("#reply-form .message-container div").fadeOut(
                                    300,
                                    function () {
                                        $("#reply-form .message-container").html("");
                                    }
                                );
                            }, 3000);
                        }
                    },
                    error: function () {
                        $("#loader").hide();
                        $("#file_prev_attachment_section")
                            .css("display", "none")
                            .html("");
                        $("#reply-form .message-container").html(
                            '<div class="err">Upload failed. Please try again.</div>'
                        );
                        setTimeout(function () {
                            $("#reply-form .message-container div").fadeOut(
                                300,
                                function () {
                                    $("#reply-form .message-container").html("");
                                }
                            );
                        }, 3000);
                    },
                });
            }
        );
    }
}

function remove_ticket_image(e) {
    e.preventDefault();
    e.stopPropagation();
    $("#file_prev_attachment_section").html("");
    $("#chat-widget-message-file").val("");
}

// Store input field value before refresh
let messageInputVal = "";
let messageFileVal = "";

// Function to load new messages
function refreshTicketMessages() {
    var base_url = $("#base_url").val();
    var c_qid = $("#c_qid").val();

    // Save current input values
    messageInputVal = $("#chat-widget-message-text").val();
    messageFileVal = $("#chat-widget-message-file").val();

    if (base_url && c_qid) {
        $.ajax({
            url: base_url + "/refresh-messages-ticket",
            method: "POST",
            data: {
                c_qid: c_qid,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $(".ticketConversationSection").html(response.messages);
                    updateTicketScroll();

                    // Restore input values after refresh
                    $("#chat-widget-message-text").val(messageInputVal);
                    $("#chat-widget-message-file").val(messageFileVal);
                }
            },
        });
    }
}

$(document).ready(function () {
    console.log("Document ready in message.js");
    var base_url = $("#base_url").val();
    console.log("Base URL:", base_url);
    updateTicketScroll();

    // Initial refresh after 0.5s
    setTimeout(refreshTicketMessages, 10);
    // Auto-refresh chat every 30 seconds (changed from 3 seconds)
    setInterval(refreshTicketMessages, 30000);

    // Submit form on Enter key press in chat input field
    $("#chat-widget-message-text").keypress(function (e) {
        if (e.which == 13) {
            $("#reply-btn").click();
            return false;
        }
    });

    // Handle send button click
    $(document).on("click", "#reply-btn", function (e) {
        e.preventDefault();
        console.log("Send button clicked");

        var to_user_id = $("#to_user_id").val();
        var from_user_id = $("#from_user_id").val();
        var chat_message = $("#chat-widget-message-text").val().trim();
        var message_file = $("#chat-widget-message-file").val();
        var c_qid = $("#c_qid").val();
		var notifyCustomer = $('#notifyCustomer').is(':checked') ? 1 : 0;

        console.log("Message data:", {
            to_user_id: to_user_id,
            from_user_id: from_user_id,
            chat_message: chat_message,
            message_file: message_file,
            c_qid: c_qid,
        });

        // Check if at least one of message text or file is provided
        if (chat_message == "") {
            alert("Please enter message");
            return false; // Don't do anything if both are empty
        }

        var form_data = new FormData();
        form_data.append("to_user_id", to_user_id);
        form_data.append("from_user_id", from_user_id);
        form_data.append("chat_message", chat_message);
        form_data.append("message_file", message_file);
        form_data.append("notifyCustomer", notifyCustomer);
        form_data.append("c_qid", c_qid);

        $("#loader").show();
        console.log("Sending AJAX request to:", base_url + "/insert_chat_ticket");

        $.ajax({
            url: base_url + "/insert_chat_ticket",
            method: "POST",
            data: form_data,
            contentType: false,
            processData: false,
            success: function (response) {
                $("#loader").hide();
                console.log("Response received:", response);
                if (response.class == "succ") {
                    console.log("Message sent successfully");
                    $(".ticketConversationSection").append(response.message);
                    $("#chat-widget-message-text").val("");
                    $("#chat-widget-message-file").val("");
                    messageInputVal = ""; // Clear stored input value
                    messageFileVal = ""; // Clear stored file value
                    $("#file_prev_attachment_section").css("display", "none");
                    $("#file_prev_attachment_section").html("");
                    updateTicketScroll();
                    // Force refresh immediately after sending
                    refreshTicketMessages();
                } else {
                    console.error("Error in response:", response);
                    $("#reply-form .message-container").html(
                        '<div class="err">Could not send message. Please try again.</div>'
                    );
                    setTimeout(function () {
                        $("#reply-form .message-container div").fadeOut(
                            300,
                            function () {
                                $("#reply-form .message-container").html("");
                            }
                        );
                    }, 3000);
                }
            },
            error: function (xhr, status, error) {
                $("#loader").hide();
                console.error("AJAX error:", status, error);
                console.error("Response:", xhr.responseText);
                $("#reply-form .message-container").html(
                    '<div class="err">An error occurred while sending your message. Please try again.</div>'
                );
                setTimeout(function () {
                    $("#reply-form .message-container div").fadeOut(
                        300,
                        function () {
                            $("#reply-form .message-container").html("");
                        }
                    );
                }, 3000);
            },
        });
    });
});

function resolvedTicket() {
    var base_url = $("#base_url").val();
    var c_qid = $("#c_qid").val();
	var to_user_id = $("#to_user_id").val();
    var chat_message = $("#chat-widget-message-text").val().trim();
	var notifyCustomer = $('#notifyCustomer').is(':checked') ? 1 : 0;
    if (base_url && c_qid) {
		$("#loader").show();
        $.ajax({
            url: base_url + "/resolvedTicket",
            method: "POST",
            data: {
                c_qid: c_qid,
                to_user_id: to_user_id,
                chat_message: chat_message,
                notifyCustomer: notifyCustomer,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
				$("#loader").hide();
                if (response.class == "succ") {
                    $("#reply-form .message-container").html(response.messages);
					setTimeout(() => {
						location.reload();
					}, 2000);
                }else{
					$("#reply-form .message-container").html(response.messages);
				}
            },
        });
    }
}

function confirmAndCall() {
    if (confirm("Are you sure you want to close ticket?")) {
        closedTicket();
    }
}

function closedTicket() {
    var base_url = $("#base_url").val();
    var c_qid = $("#c_qid").val();
	var to_user_id = $("#to_user_id").val();
    var chat_message = $("#chat-widget-message-text").val().trim();
	var notifyCustomer = $('#notifyCustomer').is(':checked') ? 1 : 0;
    if (base_url && c_qid) {
		$("#loader").show();
        $.ajax({
            url: base_url + "/closedTicket",
            method: "POST",
            data: {
                c_qid: c_qid,
				to_user_id: to_user_id,
                chat_message: chat_message,
                notifyCustomer: notifyCustomer,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
				$("#loader").hide();
                if (response.class == "succ") {
                    $("#reply-form .message-container").html(response.messages);
					setTimeout(() => {
						location.reload();
					}, 2000);
                }else{
					$("#reply-form .message-container").html(response.messages);
				}
            },
        });
    }
}

//End chat
