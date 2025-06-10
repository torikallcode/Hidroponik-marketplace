<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Profil Saya</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #c1d8c3;
      margin: 0;
      padding: 0;
    }

    .profile-card {
      max-width: 400px;
      margin: 80px auto;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      padding: 30px;
      border: 1px solid #e0e0e0;
    }

    .profile-card h2 {
      text-align: center;
      color: #333;
      font-size: 24px;
      margin-bottom: 20px;
    }

    .profile-info p {
      font-size: 16px;
      color: #555;
      margin: 10px 0;
    }

    .profile-info span {
      font-weight: 600;
      color: #222;
    }
  </style>
</head>

<body>

  <?php
  echo "
<div class='profile-card'>
  <h2>👤 Profil Saya</h2>
  <div class='profile-info'>
    <p><span>Nama:</span> " . htmlspecialchars($seller_name) . "</p>
    <p><span>Email:</span> " . htmlspecialchars($_SESSION['email']) . "</p>
  </div>
</div>
";
  ?>

</body>

</html>