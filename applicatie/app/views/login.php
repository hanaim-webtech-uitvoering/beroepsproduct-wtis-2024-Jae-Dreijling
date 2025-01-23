<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link href="/public/css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center my-5">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Login</h2>
                <form method="POST" action="/public/index.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </form>
                <hr>
                <h2 class="text-center mb-4">Register</h2>
                <form method="POST" action="/public/index.php">
                    <div class="mb-3">
                        <label for="reg_username" class="form-label">Username</label>
                        <input type="text" name="reg_username" class="form-control" id="reg_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="reg_email" class="form-label">Email</label>
                        <input type="email" name="reg_email" class="form-control" id="reg_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="reg_password" class="form-label">Password</label>
                        <input type="password" name="reg_password" class="form-control" id="reg_password" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-success w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
