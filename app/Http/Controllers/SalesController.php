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
use Maatwebsite\Excel\Facades\Excel;

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
        
    public function add()
    {
        return view('sales.add', [
            'sales' => new Sales,
        ]);
    }

    public function import(Request $request)
    {
        // Check if a file has been sent
        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'You must select a file to import.');
        }
    
        // Get the uploaded file from the request
        $file = $request->file('file');
    
        // Check if the file is valid (optional, depending on your needs)
        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'The selected file is not valid.');
        }
    
        // Load the Excel file into an array
        $excelData = Excel::toArray([], $file);
    
        // Get the header from the first row of the Excel data
        $header = $excelData[0][0];
    
        // Define the expected column names
        $expectedColumns = ["date", "agent", "amount", "product", "company", "award"];
    
        // Verify if the header contains all the required columns
        foreach ($expectedColumns as $column) {
            if (!in_array(strtolower($column), array_map('strtolower', $header))) {
                // If any required column is missing, redirect back with an error message
                return redirect()->back()->with('error', 'The file does not contain the column ' . $column . '.');
            }
        }
    
        // Perform the import only if everything is in order
        Excel::import(new SalesImport, $file);
    
        // Redirect to the '/sales' route with a success message
        return redirect('/sales')->with('success', 'Data imported correctly!');
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
