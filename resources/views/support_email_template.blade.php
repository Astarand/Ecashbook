<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Support Request - E-Cashbook</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
body {
    margin: 0;
    padding: 0;
    background: #f5f7fb;
    font-family: 'Poppins', Arial, sans-serif;
    color: #333;
}
.container {
    max-width: 600px;
    margin: 30px auto;
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}
.colored-bar {
    height: 6px;
    background: linear-gradient(90deg, #673de6, #50b4f2);
}
.header {
    padding: 25px;
    text-align: center;
}
.logo {
    max-width: 170px;
}
.main-heading {
    text-align: center;
    font-size: 24px;
    color: #313363;
    margin: 10px 0 5px;
}
.sub-text {
    text-align: center;
    font-size: 14px;
    color: #6b6f91;
    margin-bottom: 25px;
}
.content {
    padding: 0 30px 30px;
}
.info-box {
    background: #f7f9ff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
}
.info-row {
    display: flex;
    margin-bottom: 10px;
}
.info-label {
    width: 90px;
    font-weight: 500;
    color: #313363;
}
.info-value {
    flex: 1;
    color: #555;
}
.message-box {
    background: #ffffff;
    border: 1px solid #e4e6f0;
    border-radius: 8px;
    padding: 15px;
    font-size: 14px;
    line-height: 1.6;
    color: #444;
}
.footer {
    background: #f7f9fc;
    padding: 20px;
    text-align: center;
    font-size: 13px;
    color: #6b6f91;
    border-top: 1px solid #e6e8f2;
}
</style>
</head>

<body>

<div class="container">
    <div class="colored-bar"></div>

    <div class="header">
        <img src="{{ asset('assets/images/logo.png') }}" class="logo" alt="E-Cashbook">
    </div>

    <h2 class="main-heading">New Support Request</h2>
    <p class="sub-text">A user has contacted the E-Cashbook support team</p>

    <div class="content">

        <div class="info-box">
            <div class="info-row">
                <div class="info-label">Name</div>
                <div class="info-value">{{ $name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Subject</div>
                <div class="info-value">{{ $subject }}</div>
            </div>
        </div>

        <p style="font-weight:500;color:#313363;margin-bottom:10px;">Message</p>

        <div class="message-box">
            {{ $messageText }}
        </div>

        <p style="margin-top:30px;color:#555;">
            Regards,<br>
            <strong>E-Cashbook System</strong>
        </p>

    </div>

    <div class="footer">
        This email was generated from the E-Cashbook support system.<br>
        Please reply directly to continue the conversation.
    </div>

</div>

</body>
</html>
