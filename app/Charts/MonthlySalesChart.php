<?php

namespace App\Charts;

use App\Models\Product;
use App\Models\Sales;
use App\Models\Award;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MonthlySalesChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $selectedSeasonId = request('season_id');
        $currentSeason = $this->getCurrentSeason($selectedSeasonId);
        $monthlySales = $this->getMonthlySales($currentSeason);
        $months = $this->getAllMonths();
        $chartData = $this->getChartData($monthlySales, $months);

        $chart = $this->chart->lineChart()
            ->setTitle($currentSeason ? 'Sales during ' . $currentSeason->name : 'No Current Season Data')
            ->setSubtitle('Monthly Sales')
            ->setXAxis($months);

        // Verifica si hay datos
        if (!empty($chartData)) {
            foreach ($chartData as $productName => $salesData) {
                $chart->addData($productName, $salesData);
            }
        } else {
            // Agregar datos en caso de no haber ventas
            $chart->addData('Sin Datos', array_fill(0, 12, 0));
        }

        return $chart;
    }


    protected function getMonthlySales($currentSeason)
    {
        // Si no hay temporada actual o temporada seleccionada, devolver una colección vacía de ventas
        if (!$currentSeason) {
            return collect();
        }

        // Obtener el ID de la temporada actual o seleccionada
        $currentSeasonId = $currentSeason->id;

        // Obtener las ventas filtradas por el ID de la temporada actual o seleccionada
        return Sales::selectRaw('SUM(amount) as total_sales')
            ->selectRaw("DATE_FORMAT(date, '%M') as month")
            ->selectRaw('product_id')
            ->whereHas('award', function ($query) use ($currentSeasonId) {
                $query->where('id', $currentSeasonId);
            })
            ->groupBy('month', 'product_id')
            ->get();
    }

    protected function getAllMonths()
    {
        $months = collect();
        $currentMonth = now()->startOfYear();

        for ($i = 0; $i < 12; $i++) {
            $months->push($currentMonth->format('F'));
            $currentMonth->addMonth();
        }

        return $months->toArray();
    }

    protected function getChartData($monthlySales, $months)
    {
        $chartData = [];

        // Obtener todos los product_id únicos de las ventas
        $productIds = $monthlySales->pluck('product_id')->unique()->toArray();

        foreach ($productIds as $productId) {
            // Obtener el nombre del producto a partir del product_id utilizando el modelo Product
            $productName = Product::find($productId)->name;

            // Inicializar los datos de ventas para cada producto en 0
            $salesData = array_fill(0, 12, 0);

            // Actualizar los datos de ventas con los valores reales de la colección de ventas
            foreach ($monthlySales as $sale) {
                if ($sale->product_id === $productId) {
                    $monthIndex = array_search($sale->month, $months);
                    $salesData[$monthIndex] = (int) $sale->total_sales;
                }
            }

            // Agregar los datos de ventas del producto al arreglo del gráfico
            $chartData[$productName] = $salesData;
        }

        return $chartData;
    }

    protected function getCurrentSeason($selectedSeasonId = null)
    {
        $today = Carbon::today();

        if ($selectedSeasonId) {
            return Award::where('id', $selectedSeasonId)
                ->where('start', '<=', $today)
                ->where('end', '>=', $today)
                ->first();
        }

        return Award::where('start', '<=', $today)
            ->where('end', '>=', $today)
            ->first();
    }

    public function datasetNotEmpty()
    {
        return !empty($this->chart->dataset);
    }
}
