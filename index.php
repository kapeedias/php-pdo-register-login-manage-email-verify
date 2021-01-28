<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Sai Deepak and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.79.0">
    <title>Login</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="text-center">
    <main class="form-signin">
      <form method="POST" enctype="multipart/form-data" action="index.php">
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
        <label for="inputEmail" class="visually-hidden">Email address</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="visually-hidden">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">App by <a href="https://github.com/kapeedias/" target="_blank">kapeedias</a></p>
      </form>
    </main>
  </body>
</html>

