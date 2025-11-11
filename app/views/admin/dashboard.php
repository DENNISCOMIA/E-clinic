<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>eClinic Admin - Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex">

  <!-- Sidebar -->
  <aside class="w-80 bg-sky-400 min-h-screen fixed flex flex-col justify-between px-6 py-8 border-r-4 border-indigo-200">
    <div>
      <div class="flex justify-center mb-6">
        <img src="<?= base_url() ?>/public/pic.img/logo_with__text-removebg-preview.png" alt="eClinic Logo" class="h-40 w-40 object-contain">
      </div>
      <nav class="space-y-6">
        <a href="<?= site_url('admin/dashboard') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">Admin Dashboard</a>
        <a href="<?= site_url('admin/appointments') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">Appointment Management</a>
        <a href="<?= site_url('admin/findings') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">Findings & Prescription</a>
        <a href="<?= site_url('admin/records') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">Patients Records</a>
      </nav>
    </div>
    <div class="mb-2">
      <a href="<?= site_url('auth/logout') ?>" class="bg-white text-sky-400 text-lg font-bold py-2 px-20 rounded-full w-full hover:bg-sky-50">
        Log Out
      </a>
    </div>
  </aside>

  <!-- Main -->
  <main class="ml-80 flex-1 p-10">
    <h1 class="text-4xl font-bold text-sky-400 mb-8 text-center">Admin Dashboard</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
      <div class="bg-white p-6 rounded-lg shadow text-center hover:shadow-md transition">
        <h2 class="text-lg font-semibold text-gray-600 mb-2">Total Appointments</h2>
        <p class="text-3xl font-bold text-sky-400"><?= $totalAppointments ?></p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow text-center hover:shadow-md transition">
        <h2 class="text-lg font-semibold text-gray-600 mb-2">Total Registered</h2>
        <p class="text-3xl font-bold text-sky-400"><?= $totalPatients ?></p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow text-center hover:shadow-md transition">
        <h2 class="text-lg font-semibold text-gray-600 mb-2">Upcoming Today</h2>
        <p class="text-3xl font-bold text-sky-400"><?= $todaysAppointments ?></p>
      </div>
    </div>

    <!-- Today's Accepted Appointments Table -->
    <div class="bg-white shadow rounded p-6 mb-10">
      <h2 class="text-xl font-bold text-gray-700 mb-4 text-center">Today's Accepted Appointments (<?= date("F j, Y") ?>)</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 text-sm">
          <thead>
            <tr class="bg-gray-100 text-gray-700">
              <th class="p-3 border">Patient Name</th>
              <th class="p-3 border">Purpose</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($todayAppointmentsList)): ?>
              <?php foreach ($todayAppointmentsList as $appt): ?>
                <tr class="hover:bg-gray-50">
                  <td class="p-3 border"><?= htmlspecialchars($appt['fullname']) ?></td>
                  <td class="p-3 border"><?= htmlspecialchars($appt['purpose']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="2" class="p-4 text-center text-gray-500">No accepted appointments for today.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Chart -->
    <div class="bg-white p-8 rounded-lg shadow">
      <h2 class="text-xl font-bold text-gray-700 mb-4 text-center">Monthly Appointments (<?= date("Y") ?>)</h2>
      <canvas id="appointmentsChart" height="100"></canvas>
    </div>
  </main>
</div>

<!-- Chart.js Script -->
<script>
const ctx = document.getElementById('appointmentsChart').getContext('2d');
const appointmentsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [{
            label: 'Appointments',
            data: <?= json_encode($monthlyAppointments) ?>,
            backgroundColor: 'rgba(56, 189, 248, 0.6)',
            borderColor: 'rgba(56, 189, 248, 1)',
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});
</script>

</body>
</html>
