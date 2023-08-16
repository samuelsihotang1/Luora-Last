<?php

namespace App\Charts;

use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class UsersChart
{
  protected $chart;

  public function __construct(LarapexChart $chart)
  {
    $this->chart = $chart;
  }

  public function build(): \ArielMejiaDev\LarapexCharts\LineChart
  {
    $dayLabels = [];
    $data = [];

    for ($i = 6; $i >= 0; $i--) {
      $dayDate = date('Y-m-d', strtotime('-' . $i . ' days'));

      $dayName = date('l', strtotime($dayDate));
      $dayLabels[] = $dayName;
      if ($i == 0) {
        $total = User::count();
      } else {
        $currentDate = date('Y-m-d', strtotime('-' . ($i - 1) . ' days'));
        $total = User::where('created_at', '<', $currentDate)->count();
      }
      $data[] = $total;
    }

    $dayLabels = array_map(function ($day) {
      $daysInIndonesian = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
      ];

      return $daysInIndonesian[$day];
    }, $dayLabels);

    return $this->chart->lineChart()
      ->addData('Total Users', $data)
      ->setHeight(250)
      ->setFontFamily('Nunito')
      ->setXAxis($dayLabels)
      ->setColors(['#FFC107', '#303F9F'])
      ->setMarkers(['#FF5722', '#E040FB'], 7, 10);
  }
}
