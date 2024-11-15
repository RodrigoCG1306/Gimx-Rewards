<?php

namespace App\Charts;

use App\Models\Award;
use App\Models\Product;
use App\Models\Sales;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgentProductSalesChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): ?\ArielMejiaDev\LarapexCharts\PieChart
    {
        $userId = Auth::id();

        $currentSeason = $this->getCurrentSeason(); // Obtener la temporada actual

        // Verificar si se encontró una temporada actual válida
        if (!$currentSeason) {
            // Puedes mostrar un mensaje de error o realizar alguna otra acción adecuada
            return null;
        }
        $productIds = Sales::where('user_id', $userId)
                            ->where('award_id', $currentSeason->id) // Filtrar por el ID de la temporada actual
                            ->distinct()
                            ->pluck('product_id');

        $productNames = $this->getProductNames($productIds);
        $productSales = $this->getTotalSalesByProductIds($productIds, $userId, $currentSeason->start, $currentSeason->end);

        $totalSales = array_sum($productSales);
        $productPercentages = [];

        foreach ($productSales as $sales) {
            $percentage = round(($sales / $totalSales) * 100, 2);
            $productPercentages[] = $percentage . '%';
        }

        $labels = $this->getLabels($productNames, $productPercentages);

        return $this->chart->pieChart()
            ->setTitle('Ventas Totales por Producto')
            ->addData($productSales)
            ->setLabels($labels);
    }

    protected function getProductNames($productIds)
    {
        $productNames = [];

        foreach ($productIds as $productId) {
            $product = Product::find($productId);

            if ($product) {
                $productNames[] = $product->name;
            } else {
                $productNames[] = 'Producto Desconocido';
            }
        }

        return $productNames;
    }

    protected function getTotalSalesByProductIds($productIds, $userId, $startDate, $endDate)
    {
        $productSales = [];

        foreach ($productIds as $productId) {
            $sales = Sales::where('product_id', $productId)
                          ->where('user_id', $userId)
                          ->whereBetween('date', [$startDate, $endDate])
                          ->sum('amount');
            $productSales[] = (int)$sales;
        }

        return $productSales;
    }

    protected function getLabels($productNames, $productPercentages)
    {
        $labels = [];

        foreach ($productNames as $key => $name) {
            $labels[] = $name . ' (' . $productPercentages[$key] . ')';
        }

        return $labels;
    }

    protected function getCurrentSeason()
    {
        $today = Carbon::today();
        return Award::where('start', '<=', $today)
                    ->where('end', '>=', $today)
                    ->first();
    }
}
