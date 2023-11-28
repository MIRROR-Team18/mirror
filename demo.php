<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .login-container label {
      display: block;
      margin-bottom: 8px;
    }

    .login-container input {
      width: 100%;
      padding: 8px;
      margin-bottom: 16px;
      box-sizing: border-box;
    }

    .login-container button {
      background-color: #4caf50;
      color: #fff;
      padding: 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="login-container">
  <h2>Login</h2>
  <form id="loginForm">
    <label for="accountType">Select Account Type:</label>
    <select id="accountType" name="accountType">
      <option value="admin">Admin</option>
      <option value="customer">Customer</option>
    </select>

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="button" onclick="login()">Login</button>
  </form>
</div>

<script>
  function login() {
    var accountType = document.getElementById("accountType").value;
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    
    
    console.log("Username: " + username);
    console.log("Password: " + password);

   
  }
</script>

</body>
</html>
