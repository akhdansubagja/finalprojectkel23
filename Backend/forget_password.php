<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="Login.css">
    <title>Forget Password</title>
    <link rel="icon" href="images/doo.png">
</head>
<body>
    <div class="container">
        <form action="reset_password.php" method="POST">
            <h1>Reset Password</h1>
            <span>Enter your email to receive a password reset link</span>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Send Reset Link</button>
            <a href="../login.html">Back to Sign In</a>
        </form>
    </div>
</body>
</html>
