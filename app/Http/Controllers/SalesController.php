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
    const SALES_PAGINATE = 10; // Puedes ajustar el número de ventas por página según tus necesidades.

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

        $sales = $query->orderBy('date', 'desc')->paginate(self::SALES_PAGINATE);

        $users = User::all();
        $products = Product::all();
        $companies = Company::all();

        return view('sales.list', compact('sales', 'users', 'products', 'companies', 'awards', 'user_id', 'product_id', 'company_id', 'award_id', 'current_season_id'));
    }
        
    public function upload()
    {
        return view('sales.upload', [
            'sales' => new Sales,
        ]);
    }
    public function add()
    {
        // Obtener la fecha actual
        $today = \Carbon\Carbon::today();
        
        // Obtener la temporada actual basada en la fecha
        $currentSeason = Award::where('start', '<=', $today)
                              ->where('end', '>=', $today)
                              ->first();
    
        // Si no se encuentra una temporada activa, asignar null para mostrar mensaje o lógica alternativa en la vista
        $currentSeasonName = $currentSeason ? $currentSeason->name : 'No active season';
        $currentSeasonId = $currentSeason ? $currentSeason->id : null;
    
        // Obtener los datos necesarios para el formulario
        $users = User::all();
        $products = Product::all();
        $companies = Company::all();
        $awards = Award::all();
    
        // Pasar los datos a la vista
        return view('sales.add', compact('users', 'products', 'companies', 'awards', 'currentSeasonName', 'currentSeasonId'));
    }
    

    public function store(Request $request)
    {
        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'company_id' => 'required|exists:companies,id',
            'award_id' => 'required|exists:awards,id',
            'amount' => 'required|numeric',
            'email' => 'required|email|in:' . Auth::user()->email,  // Asegura que el email es el del usuario autenticado
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Crear la venta si la validación pasa
        $sale = Sales::create([
            'user_id' => Auth::id(),  // ID del usuario autenticado
            'product_id' => $request->product_id,
            'company_id' => $request->company_id,
            'award_id' => $request->award_id,
            'amount' => $request->amount,
            'email' => Auth::user()->email,  // Usamos el email del usuario autenticado
            'date' => now(),  // Fecha actual
        ]);
    
        return redirect()->route('dashboard.subagents')->with('success', 'Data imported correctly!');
    }
    

    use \PhpOffice\PhpSpreadsheet\Shared\Date;

public function import(Request $request)
{
    // Verificar si se ha enviado un archivo
    if (!$request->hasFile('file')) {
        return redirect()->back()->with('error', 'Debe seleccionar un archivo para importar.');
    }

    // Obtener el archivo cargado
    $file = $request->file('file');

    // Verificar si el archivo es válido
    if (!$file->isValid()) {
        return redirect()->back()->with('error', 'El archivo seleccionado no es válido.');
    }

    // Cargar los datos del archivo Excel
    $excelData = Excel::toArray([], $file);

    // Obtener el encabezado de la primera fila del archivo Excel
    $header = $excelData[0][0];

    // Definir los nombres de columna esperados
    $expectedColumns = ["date", "agent", "email", "amount", "product", "company", "award"];

    // Verificar si el encabezado contiene todas las columnas requeridas
    foreach ($expectedColumns as $column) {
        if (!in_array(strtolower($column), array_map('strtolower', $header))) {
            return redirect()->back()->with('error', 'El archivo no contiene la columna ' . $column . '.');
        }
    }

    // Obtener los datos del archivo Excel (ignoramos el encabezado)
    $excelRows = array_slice($excelData[0], 1);

    // Comparar los datos del Excel con la base de datos
    $comparisonResults = [];
    foreach ($excelRows as $row) {
        // Aquí asumimos que el archivo Excel contiene las columnas: date, agent, email, amount, product, company, award
        $saleData = [
            'date' => Date::excelToDateTimeObject($row[0])->format('Y-m-d'),  // Convertimos la fecha de Excel a formato Y-m-d
            'agent' => $row[1],
            'email' => $row[2],
            'amount' => $row[3],
            'product' => $row[4],
            'company' => $row[5],
            'award' => $row[6],
        ];

        // Buscar si existe una venta con estos datos
        $existingSale = Sales::whereDate('date', $saleData['date']) // Asegúrate de usar 'whereDate' para comparar solo la fecha
                            ->where('amount', $saleData['amount'])
                            ->whereHas('product', function ($query) use ($saleData) {
                                $query->where('name', $saleData['product']);
                            })
                            ->whereHas('company', function ($query) use ($saleData) {
                                $query->where('name', $saleData['company']);
                            })
                            ->whereHas('award', function ($query) use ($saleData) {
                                $query->where('name', $saleData['award']);
                            })
                            ->whereHas('agent', function ($query) use ($saleData) {
                                $query->where('email', $saleData['email']); // Filtramos por el email del agente
                            })
                            ->first();

        if ($existingSale) {
            // Si encontramos la venta, actualizamos los campos que han cambiado
            $existingSale->update([
                'amount' => $saleData['amount'],
                'product' => $saleData['product'],
                'company' => $saleData['company'],
                'award' => $saleData['award'],
            ]);

            $comparisonResults[] = [
                'status' => 'updated',
                'excel' => $saleData,
                'db' => $existingSale->toArray(),
            ];
        } else {
            // Si no se encuentra la venta, creamos una nueva
            $user = User::where('email', $saleData['email'])->first(); // Obtener el usuario por su email
            if ($user) {
                $saleData['user_id'] = $user->id; // Asignamos el ID del usuario al campo 'user_id'
                Sales::create($saleData);
                $comparisonResults[] = [
                    'status' => 'new',
                    'excel' => $saleData,
                    'db' => null,
                ];
            } else {
                $comparisonResults[] = [
                    'status' => 'no_user',
                    'excel' => $saleData,
                    'db' => null,
                ];
            }
        }
    }

    // Pasar los resultados de la comparación a la vista
    return view('sales.comparison', compact('comparisonResults'));
}


    public function edit($id)
    {
        $sale = Sales::find($id);
        $users = User::all();
        $companies = Company::all();
        $products = Product::all();
        $awards = Award::all();
        return view('sales.edit', compact('sale', 'users', 'companies', 'products', 'awards'));
    }

    public function update($id, UpdateSalesRequest $request)
    {
        $sale = Sales::find($id);
      //  dd($request->all());
        $sale->user_id = $request->input('user');

        $sale->product_id = $request->input('product');

        $sale->company_id = $request->input('company');

        $sale->award_id = $request->input('award');

        if ($request->filled('amount')) {
            $sale->amount = $request->input('amount');
        }

        if ($request->filled('date')) {
            $sale->date = $request->input('date');
        }

        $sale->save();

        return redirect()->route('sales.list')->with('success', 'Sale updated successfully.');
    }

    public function export()
    {
        return Excel::download(new SalesTemplate, 'sales.xlsx');
    }

}
