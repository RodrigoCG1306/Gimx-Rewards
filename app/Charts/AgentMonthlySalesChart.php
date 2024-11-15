<?php

namespace App\Charts;

use App\Models\Award;
use App\Models\Product;
use App\Models\Sales;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgentMonthlySalesChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($selectedSeasonId = null): ?\ArielMejiaDev\LarapexCharts\LineChart
    {
        $userId = Auth::id();

        // Si no se proporciona una temporada seleccionada, obtener la temporada activa actual
        if ($selectedSeasonId === null) {
            $currentSeason = Award::whereDate('start', '<=', Carbon::today())
                                  ->whereDate('end', '>=', Carbon::today())
                                  ->first();

            $selectedSeasonId = $currentSeason ? $currentSeason->id : null;
        }

        // Si no hay una temporada activa actual y no se proporciona una temporada seleccionada, retornar null
        if (!$selectedSeasonId) {
            // Puedes mostrar un mensaje de error o realizar alguna otra acción adecuada
            return null;
        }

        $monthlySales = $this->getMonthlySales($userId, $selectedSeasonId);

        $productIds = $monthlySales->pluck('product_id')->unique()->toArray(); // Obtener los product_id únicos

        $chart = $this->chart->lineChart()
            ->setTitle('Sales during ' . Award::find($selectedSeasonId)->name) // Usar el nombre de la temporada seleccionada en el título
            ->setSubtitle('Monthly sales for the agent.');

        $months = $this->getAllMonths(); // Declarar la variable $months fuera del bucle

        foreach ($productIds as $productId) {
            $salesData = [];

            foreach ($months as $month) {
                $sales = $monthlySales
                    ->where('product_id', $productId)
                    ->where('month', $month)
                    ->first();

                $salesData[] = $sales ? (int) $sales->total_sales : 0;
            }

            // Obtener el nombre del producto a partir del product_id utilizando el modelo Product
            $productName = Product::find($productId)->name;

            $chart->addData($productName, $salesData); // Agregar datos para cada producto
        }

        return $chart->setXAxis($months);
    }

    protected function getMonthlySales($userId, $selectedSeasonId)
    {
        return Sales::selectRaw('SUM(amount) as total_sales')
            ->selectRaw("DATE_FORMAT(date, '%M') as month")
            ->selectRaw('product_id')
            ->where('user_id', $userId)
            ->where('award_id', $selectedSeasonId) // Filtrar por la temporada seleccionada
            ->groupBy('month', 'product_id') // Agrupar también por product_id
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
}
