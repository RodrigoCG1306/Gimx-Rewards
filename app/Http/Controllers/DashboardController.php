<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\AgentMonthlySalesChart;
use App\Charts\AgentProductSalesChart;
use App\Charts\MonthlySalesChart;
use App\Charts\ProductSalesChart;
use App\Models\Award;
use App\Models\Company;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    const DASHBOARD_ADMIN_VIEW = "dashboard.index";
    const DASHBOARD_SUBAGENT_VIEW = "dashboard.subagents";

    public function index(Request $request, MonthlySalesChart $monthlyChart, ProductSalesChart $productChart, AgentProductSalesChart $agentProductSalesChart, AgentMonthlySalesChart $agentMonthlySales)
    {
        $lastUpdate = $this->getLastUpdateTimestamp();

        $seasons = $this->getAllSeasons(); // Obtener todas las temporadas disponibles

        $selectedSeasonId = $request->input('season_id'); // Obtener el ID de la temporada seleccionada desde la solicitud

        if (auth()->user()->hasRole('Admin|Agent')) {
            return $this->adminView([
                'monthlyChart'       => $monthlyChart->build(),
                'productChart'       => $productChart->build(),
                'usersWithSales'     => $this->getUsersWithSales($selectedSeasonId), // Pasar el ID de la temporada seleccionada al método
                'lastUpdate'         => $lastUpdate,
                'seasons'            => $seasons, // Pasar las temporadas a la vista
                'selectedSeasonId'   => $selectedSeasonId, // Pasar el ID de la temporada seleccionada a la vista
            ]);
        }

        return $this->subAgents($agentProductSalesChart, $agentMonthlySales, $lastUpdate, $seasons, $selectedSeasonId);
    }

    public function subAgents(AgentProductSalesChart $agentProductSalesChart, AgentMonthlySalesChart $agentMonthlySalesChart, $lastUpdate, $seasons = null, $selectedSeasonId = null) {
        $user = Auth::user();        
    
        $userSales = $user->sales()->get();
    
        if ($userSales->isEmpty()) {
            return $this->subAgentView([
                'agentProductSales'  => $agentProductSalesChart->build(),
                'agentMonthlySales'  => $agentMonthlySalesChart->build(),
                'progressPercentage' => 0,
                'totalSales'         => 0,
                'usersWithSales'     => collect(),
                'remainingSales'     => 0,
                'lastUpdate'         => $lastUpdate,
                'seasons'            => $seasons,
                'selectedSeasonId'   => $selectedSeasonId,
            ]);
        }
    
        $totalSales = $userSales->sum('amount');
        $goal = 80000;
        $progressPercentage = min(100, ($totalSales / $goal) * 100);
        $remainingSales = max(0, $goal - $totalSales);
    
        return $this->subAgentView([
            'agentProductSales'  => $agentProductSalesChart->build(),
            'agentMonthlySales'  => $agentMonthlySalesChart->build(),
            'progressPercentage' => $progressPercentage,
            'totalSales'         => $totalSales,
            'usersWithSales'     => $userSales,
            'remainingSales'     => $remainingSales,
            'lastUpdate'         => $lastUpdate,
            'seasons'            => $seasons,
            'selectedSeasonId'   => $selectedSeasonId,
        ]);
    }

    public function adminView($data)
    {
        return view(self::DASHBOARD_ADMIN_VIEW, $data);
    }

    public function subAgentView($data)
    {
        return view(self::DASHBOARD_SUBAGENT_VIEW, $data);
    }

    private function getLastUpdateTimestamp()
    {
        $lastUpdate = Sales::latest('created_at')->value('created_at');
        return $lastUpdate instanceof Carbon ? $lastUpdate->format('d/m/Y') : null;
    }

    private function getUsersWithSales($selectedSeasonId)
    {
        $query = Sales::select('user_id', \DB::raw('SUM(amount) as total_sales'))
            ->groupBy('user_id')
            ->orderBy('total_sales', 'desc');

        if ($selectedSeasonId) {
            $query->whereHas('award', function ($query) use ($selectedSeasonId) {
                $query->where('id', $selectedSeasonId);
            });
        }

        return $query->paginate(5);
    }

    private function getAllSeasons()
    {
        return Award::all(); // Obtener todas las temporadas disponibles
    }
}
