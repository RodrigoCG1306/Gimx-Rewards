<?php

namespace App\Charts;

use App\Models\Sales;
use App\Models\Product;
use App\Models\Award;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class ProductSalesChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($selectedSeasonId = null): ?\ArielMejiaDev\LarapexCharts\PieChart
    {
        // Si no se proporciona una temporada seleccionada, obtener la temporada activa actual
        if ($selectedSeasonId === null) {
            $currentSeason = Award::whereDate('start', '<=', Carbon::today())
                                  ->whereDate('end', '>=', Carbon::today())
                                  ->first();

            $selectedSeasonId = $currentSeason ? $currentSeason->id : null;
        }

        if (!$selectedSeasonId) {
            return null;
        }

        $productIds = Sales::whereHas('award', function ($query) use ($selectedSeasonId) {
            $query->where('id', $selectedSeasonId);
        })->distinct()->pluck('product_id');

        $productNames = $this->getProductNames($productIds, $selectedSeasonId); // Pass selectedSeasonId
        $productSales = $this->getTotalSalesByProductIds($productIds, $selectedSeasonId); // Pass selectedSeasonId

        $totalSales = array_sum($productSales);
        $productPercentages = [];

        foreach ($productSales as $sales) {
            $percentage = round(($sales / $totalSales) * 100, 2);
            $productPercentages[] = $percentage . '%';
        }

        $labels = $this->getLabels($productNames, $productPercentages);

        return $this->chart->pieChart()
            ->setTitle('Total Sales by Product')
            ->addData($productSales)
            ->setLabels($labels);
    }

    protected function getProductNames($productIds, $selectedSeasonId)
    {
        $productNames = [];

        foreach ($productIds as $productId) {
            $product = Product::find($productId);

            if ($product) {
                $productNames[] = $product->name;
            } else {
                $productNames[] = 'Unknown Product';
            }
        }

        return $productNames;
    }

    protected function getTotalSalesByProductIds($productIds, $selectedSeasonId)
    {
        $productSales = [];

        foreach ($productIds as $productId) {
            $sales = Sales::where('product_id', $productId)
                          ->whereHas('award', function ($query) use ($selectedSeasonId) {
                              $query->where('id', $selectedSeasonId);
                          })
                          ->sum('amount');
            $productSales[] = (int) $sales;
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
}
