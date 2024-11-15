<?php

namespace App\Imports;

use App\Models\Award;
use App\Models\Company;
use App\Models\Department;
use App\Models\Product;
use App\Models\Sales;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SalesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    const DATE_FORMAT = 'Y-m-d';

    public function model(array $row)
    {
        $user    = User::where('name', $row['agent'])->first();
        $product = Product::where('name', $row['product'])->first();
        $company = Company::where('name', $row['company'])->first();
        $award   = Award::where('name', $row['award'])->first();

        if (!$user || !$product || !$company || !$award) {
            //Not found register
            return null;
        }

        $date = Date::excelToDateTimeObject($row['date'])->format(self::DATE_FORMAT);
        return new Sales([
            'user_id'    => $user->id,
            'product_id' => $product->id,
            'company_id' => $company->id,
            'award_id'   => $award->id,
            'date'       => $date,
            'amount'     => $row['amount'],
        ]);
    }
}
