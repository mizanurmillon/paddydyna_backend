<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Account Connection Cancelled</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Lottie Player CDN -->
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

  <style>
    body {
      background: radial-gradient(circle at center, #fee2e2, #dc2626);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
      background: white;
      border-radius: 1.5rem;
      box-shadow: 0 15px 30px rgba(220, 38, 38, 0.3);
      padding: 3rem 2.5rem;
      max-width: 480px;
      margin: auto;
      text-align: center;
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: scale(1.03);
    }
    .btn-danger {
      background: linear-gradient(to right, #b91c1c, #f87171);
      color: white;
      font-weight: 600;
      padding: 0.75rem 1.5rem;
      border-radius: 9999px;
      transition: 0.3s ease;
      display: inline-block;
    }
    .btn-danger:hover {
      background: linear-gradient(to right, #7f1d1d, #dc2626);
      transform: scale(1.05);
    }
    .btn-secondary {
      border: 2px solid #dc2626;
      color: #7f1d1d;
      padding: 0.75rem 1.5rem;
      border-radius: 9999px;
      transition: 0.3s ease;
      display: inline-block;
    }
    .btn-secondary:hover {
      background-color: rgba(220, 38, 38, 0.15);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 text-gray-900">

  <div class="card">
    <!-- Cancel Animation -->
    <lottie-player
      src="https://assets10.lottiefiles.com/private_files/lf30_e3pteeho.json"
      background="transparent"
      speed="1"
      style="width: 220px; height: 220px; margin: auto"
      autoplay>
    </lottie-player>

    <!-- Title -->
    <h1 class="text-4xl font-extrabold text-red-700 mt-6">Account Connection Cancelled</h1>

    <!-- Message -->
    <p class="mt-3 text-gray-700 text-lg">
      You have cancelled the Stripe account connection process. If this was a mistake, you can try again.
    </p>

  </div>

</body>
</html>
