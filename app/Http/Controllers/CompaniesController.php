<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;

class CompaniesController extends Controller
{
    const COMPANIES_PAGINATE = 9;

    public function list()
    {
        $companies = Company::paginate(self::COMPANIES_PAGINATE);
        return view('companies.list', compact('companies'));
    }

    public function add()
    {
        return view('companies.add', [
            'company' => new Company(),
        ]);
    }

    public function store(StoreCompanyRequest $request)
    {
        Company::create($request->all());

        return redirect()->route('companies.list')
            ->with('info', 'Company succesfully added!');
    }

    public function edit($id, Company $company)
    {
        $company = Company::find($id);
        return view('companies.edit', compact('company'));
    }

    public function update($id, UpdateCompanyRequest $request)
    {
        $company = Company::find($id);
        $company->name = $request->input('name');
        $company->save();

        return redirect()->route('companies.list')->with('succes', 'Company Updated Correctly');
    }
}
