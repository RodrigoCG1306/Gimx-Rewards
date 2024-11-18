<?php

namespace App\Http\Controllers;

use App\Exports\SalesTemplate;
use App\Http\Requests\UpdateSalesRequest;
use App\Imports\SalesImport;
use App\Models\Award;
use App\Models\Company;
use App\Models\Product;
use App\Models\Sales;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SalesController extends Controller
{
    const SALES_PAGINATE = 10;
    const EXPECTED_COLUMNS = ["date", "agent", "email", "amount", "product", "company", "award"];

    /**
     * Lista las ventas con filtros opcionales.
     */
    public function list(Request $request)
    {
        $user_id = $request->input('user_id');
        $product_id = $request->input('product_id');
        $company_id = $request->input('company_id');
        $award_id = $request->input('award_id');
    
        $today = Carbon::today();
        $currentSeason = Award::where('start', '<=', $today)
                            ->where('end', '>=', $today)
                            ->first();
        $current_season_id = $currentSeason ? $currentSeason->id : null;
    
        $awards = Award::all();
    
        $query = Sales::query();
    
        if ($user_id) {
            $query->where('user_id', $user_id);
        }
    
        if ($product_id) {
            $query->where('product_id', $product_id);
        }
    
        if ($company_id) {
            $query->where('company_id', $company_id);
        }
    
        if ($award_id) {
            $query->where('award_id', $award_id);
        }
    
        $sales = $query->with(['agent', 'product', 'company', 'award'])
                       ->whereHas('agent', function ($q) {
                           $q->whereNotNull('email');
                       })
                       ->orderBy('date', 'desc')
                       ->paginate(self::SALES_PAGINATE);
    
        $users = User::all();
        $products = Product::all();
        $companies = Company::all();
    
        return view('sales.list', compact('sales', 'users', 'products', 'companies', 'awards', 'user_id', 'product_id', 'company_id', 'award_id', 'current_season_id'));
    }

    /**
     * Muestra el formulario para subir un archivo.
     */
    public function upload()
    {
        return view('sales.upload');
    }

    /**
     * Muestra el formulario para añadir una venta.
     */
    public function add()
    {
        return view('sales.add', [
            'users' => User::all(),
            'products' => Product::all(),
            'companies' => Company::all(),
            'awards' => Award::all(),
            'currentSeasonName' => $this->getCurrentSeasonName(),
            'currentSeasonId' => $this->getCurrentSeasonId(),
        ]);
    }

    /**
     * Almacena una nueva venta.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'company_id' => 'required|exists:companies,id',
            'award_id' => 'required|exists:awards,id',
            'amount' => 'required|numeric',
            'email' => 'required|email|in:' . Auth::user()->email,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Sales::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'company_id' => $request->company_id,
            'award_id' => $request->award_id,
            'amount' => $request->amount,
            'email' => Auth::user()->email,
            'date' => now(),
        ]);

        return redirect()->route('dashboard.subagents')->with('success', 'Data imported correctly!');
    }

    /**
     * Importa ventas desde un archivo Excel.
     */
    public function import(Request $request)
    {
        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return redirect()->back()->with('error', 'Debe seleccionar un archivo válido para importar.');
        }

        $file = $request->file('file');

        try {
            $excelData = $this->parseExcelFile($file);
            $results = $this->compareAndUpdateSales($excelData);

            // Iterar sobre los resultados para obtener los nombres
            foreach ($results as &$result) {
                // Obtener nombre del usuario (suponiendo que 'user_id' está en el array 'data')
                $user = User::find($result['data']['user_id']);
                $result['data']['user_name'] = $user->name;

                // Obtener nombre del producto (suponiendo que 'product_id' está en el array 'data')
                $product = Product::find($result['data']['product_id']);
                $result['data']['product_name'] = $product->name;

                // Obtener nombre de la compañía (suponiendo que 'company_id' está en el array 'data')
                $company = Company::find($result['data']['company_id']);
                $result['data']['company_name'] = $company->name;

                // Obtener nombre del premio (suponiendo que 'award_id' está en el array 'data')
                $award = Award::find($result['data']['award_id']);
                $result['data']['award_name'] = $award->name;
            }

            return view('sales.comparison', compact('results'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    }

    /**
     * Edita una venta existente.
     */
    public function edit($id)
    {
        return view('sales.edit', [
            'sale' => Sales::findOrFail($id),
            'users' => User::pluck('name', 'id'),
            'companies' => Company::pluck('name', 'id'),
            'products' => Product::pluck('name', 'id'),
            'awards' => Award::pluck('name', 'id'),
        ]);
    }

    /**
     * Actualiza una venta existente.
     */
    public function update($id, UpdateSalesRequest $request)
    {
        $sale = Sales::findOrFail($id);
        $sale->fill($request->validated())->save();

        return redirect()->route('sales.list')->with('success', 'Sale updated successfully.');
    }

    /**
     * Exporta una plantilla de ventas.
     */
    public function export()
    {
        return Excel::download(new SalesTemplate, 'sales.xlsx');
    }

    private function getCurrentSeasonName()
    {
        $season = $this->getCurrentSeason();
        return $season ? $season->name : 'No active season';
    }

    private function getCurrentSeasonId()
    {
        $season = $this->getCurrentSeason();
        return $season ? $season->id : null;
    }

    private function getCurrentSeason()
    {
        $today = Carbon::today();
        return Award::where('start', '<=', $today)->where('end', '>=', $today)->first();
    }

    private function parseExcelFile($file)
    {
        $data = Excel::toArray([], $file)[0];

        if (empty($data) || !$this->validateExcelHeader($data[0])) {
            throw new \Exception('El archivo no contiene el formato esperado.');
        }

        return array_slice($data, 1);
    }

    private function validateExcelHeader($header)
    {
        return !array_diff(self::EXPECTED_COLUMNS, array_map('strtolower', $header));
    }

    private function compareAndUpdateSales(array $excelRows)
    {
        $results = [];
        foreach ($excelRows as $row) {
            $saleData = $this->mapRowToSaleData($row);
            // Verificar que el email coincida con el del usuario en la base de datos
            if ($saleData['email'] !== User::find($saleData['user_id'])->email) {
                throw new \Exception("El email de la venta no coincide con el del agente.");
            }
    
            // Buscar la venta existente
            $existingSale = $this->findMatchingSale($saleData);
    
            if ($existingSale) {
                $existingSale->update($saleData);
                $results[] = ['status' => 'updated', 'data' => $saleData];
            } else {
                Sales::create($saleData);
                $results[] = ['status' => 'new', 'data' => $saleData];
            }
        }
    
        return $results;
    }
    

    private function mapRowToSaleData($row)
    {
        // Buscar al usuario por el nombre del agente
        $user = User::where('name', $row[1])->first();
        $product = Product::where('name', $row[4])->first();
        $company = Company::where('name', $row[5])->first();
        $award = Award::where('name', $row[6])->first();

        // Verificar que todos los registros existan
        if (!$user) {
            throw new \Exception("The agent '{$row[1]}' isn't in the database.");
        }

        if (!$product) {
            throw new \Exception("The product '{$row[4]}' isn't in the database.");
        }

        if (!$company) {
            throw new \Exception("The company '{$row[5]}' isn't in the database.");
        }

        if (!$award) {
            throw new \Exception("The award '{$row[6]}' isn't in the database.");
        }

        // Obtener el email del usuario correspondiente
        $email = $user->email;  // Usamos el email del usuario encontrado

        return [
            'user_id' => $user->id, // ID del usuario
            'date' => Date::excelToDateTimeObject($row[0])->format('Y-m-d'),
            'email' => $email,     // Email del agente
            'amount' => $row[3],    // Cantidad
            'product_id' => $product->id, // ID del producto
            'company_id' => $company->id, // ID de la compañía
            'award_id' => $award->id,     // ID del premio
            'user_name' => $user->name,
        ];
    }

    private function findMatchingSale($data)
    {
        return Sales::whereDate('date', $data['date'])
            ->where('amount', $data['amount'])
            ->where('product_id', $data['product_id'])
            ->where('company_id', $data['company_id'])
            ->where('award_id', $data['award_id'])
            ->whereHas('agent', fn($q) => $q->where('email', $data['email']))
            ->first();
    }
}
