<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>eClinic Profile Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex">
    <!-- Sidebar -->
    <aside class="w-80 bg-sky-400 min-h-screen fixed flex flex-col justify-between px-6 py-8 border-r-4 border-indigo-200">
      <div>
        <!-- Logo -->
        <div class="flex justify-center mb-6">
          <img src="<?= base_url() ?>/public/pic.img/logo_with__text-removebg-preview.png" alt="eClinic Logo" class="h-40 w-40 object-contain">
        </div>

        <!-- Navigation -->
        <nav class="space-y-6">
          <a href="<?= site_url('user/home') ?>" class="text-white font-semibold text-lg block rounded hover:bg-white/20">Home</a>
          <a href="<?= site_url('user/profile') ?>" class="text-white font-semibold text-lg block rounded hover:bg-white/20">Profile Management</a>
          <a href="<?= site_url('user/appointment') ?>" class="text-white font-semibold text-lg block rounded hover:bg-white/20">Appointment</a>
          <a href="<?= site_url('user/history') ?>" class="text-white font-semibold text-lg block rounded hover:bg-white/20">History</a>
        </nav>
      </div>

      <!-- Logout Button -->
      <form action="<?= site_url('auth/logout') ?>" method="GET" class="mt-6">
    <button class="bg-white text-sky-400 font-bold text-lg py-2 px-4 rounded-full w-full hover:bg-sky-50">
        Log Out
    </button>
</form>

    </aside>

    <!-- Main Content -->
    <main class="ml-80 flex-1 p-8">
      <h1 class="text-sky-400 text-3xl font-bold text-center mb-8">Profile Management</h1>

      <?php if (!empty($message)): ?>
          <div class="max-w-4xl mx-auto mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
              <?= htmlspecialchars($message) ?>
          </div>
      <?php endif; ?>

      <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-gray-700 text-2xl font-bold mb-6">Personal Information</h2>
        <hr class="border-gray-200 mb-6">

        <form method="POST">
          <div class="space-y-6">
            <div>
              <label for="fullName" class="block text-gray-600 font-medium mb-2">Full Name</label>
              <input type="text" name="fullName" id="fullName"
                     value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>"
                     class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-400" required>
            </div>

            <div class="flex flex-wrap gap-6">
              <div class="w-full sm:w-1/2 lg:w-5/12">
                <label for="age" class="block text-gray-600 font-medium mb-2">Age</label>
                <input type="number" name="age" id="age"
                       value="<?= htmlspecialchars($profile['age'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-400" required>
              </div>
              <div class="w-full sm:w-1/2 lg:w-5/12">
                <label for="gender" class="block text-gray-600 font-medium mb-2">Gender</label>
                <select name="gender" id="gender" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-400" required>
                  <option value="" disabled>Select your gender</option>
                  <?php
                  $genders = ['male'=>'Male','female'=>'Female','other'=>'Other','prefer-not'=>'Prefer not to say'];
                  foreach($genders as $key=>$label):
                      $selected = (!empty($profile['gender']) && $profile['gender']===$key)? 'selected':'';
                  ?>
                  <option value="<?= $key ?>" <?= $selected ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div>
              <label for="dob" class="block text-gray-600 font-medium mb-2">Date of Birth</label>
              <input type="date" name="dob" id="dob"
                     value="<?= htmlspecialchars($profile['date_of_birth'] ?? '') ?>"
                     class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-400" required>
            </div>

            <div>
              <label for="address" class="block text-gray-600 font-medium mb-2">Address</label>
              <input type="text" name="address" id="address"
                     value="<?= htmlspecialchars($profile['address'] ?? '') ?>"
                     class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-400" required>
            </div>

            <div>
              <label for="phone" class="block text-gray-600 font-medium mb-2">Phone</label>
              <input type="text" name="phone" id="phone"
                     value="<?= htmlspecialchars($profile['phone'] ?? '') ?>"
                     maxlength="11" pattern="09\d{9}" title="Enter an 11-digit contact number starting with 09."
                     class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-400" required>
            </div>

            <div class="flex justify-end">
              <button type="submit" class="bg-sky-400 text-white font-semibold py-2 px-6 rounded-lg hover:bg-sky-500">
                <?= isset($profile['user_id']) ? 'Update' : 'Save' ?>
              </button>
            </div>
          </div>
        </form>
      </div>
    </main>
</div>
</body>
</html>
