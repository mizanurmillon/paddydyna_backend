<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Stripe Connect Success</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <style>
    body {
      background: radial-gradient(circle at center, #c7f9cc, #38b000);
    }

    .card {
      background: white;
      border-radius: 1.5rem;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: scale(1.015);
    }

    .btn-primary {
      background: linear-gradient(to right, #16a34a, #4ade80);
      color: white;
      font-weight: 600;
      padding: 0.75rem 1.5rem;
      border-radius: 9999px;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background: linear-gradient(to right, #15803d, #22c55e);
      transform: scale(1.05);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center font-sans text-gray-800">
  <div class="card p-10 max-w-lg w-full text-center">
    <!-- Success Animation -->
    <lottie-player
      src="https://assets9.lottiefiles.com/packages/lf20_jbrw3hcz.json"
      background="transparent"
      speed="1"
      style="width: 200px; height: 200px; margin: auto"
      autoplay>
    </lottie-player>

    <!-- Success Title -->
    <h1 class="text-3xl font-extrabold text-green-700 mt-4">Stripe Account Connected!</h1>

    <!-- Success Message -->
    <p class="text-gray-600 mt-2">Your Stripe account has been successfully connected. You're now ready to receive payments.</p>

  </div>
</body>
</html>
