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
      width: 400px;
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

    .login-option {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }

    .profile-box {
      display: flex;
      flex-direction: row;
      width: 100%;
      justify-content: space-between;
      border: 1px solid #ddd; /* Border added */
      border-radius: 4px; /* Border radius added for a smoother look */
      padding: 10px; /* Padding added for better spacing */
      box-sizing: border-box; /* Include padding in box sizing */
    }

    .login-option label {
      margin-bottom: 8px;
    }

    .login-option input {
      margin-top: 2px;
    }

    .user-icon {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background-color: #4caf50;
      display: inline-block;
      margin-right: 10px;
    }
  </style>
</head>
<body>

<div class="login-container">
  <h1>Login</h1>

  <div class="login-option">
    <label>Account Type:</label>
    <div class="profile-box">
      <div class="user-icon"></div>
      <label for="userOption">User Login:</label>
      <input type="radio" id="userOption" name="accountType" value="user" checked>
    </div>
  </div>

  <form id="loginForm">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="button" onclick="login()">Login</button>
  </form>
</div>

<script>
  function login() {
    var accountType = document.querySelector('input[name="accountType"]:checked').value;
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    // Here you would typically send the login credentials to a server for validation
    // and handle the authentication process on the server side.

    // For demonstration purposes, we'll just log the values to the console.
    console.log("Account Type: " + accountType);
    console.log("Username: " + username);
    console.log("Password: " + password);

    // You can add further logic based on the account type, e.g., redirecting to different pages.
  }
</script>

</body>
</html>
