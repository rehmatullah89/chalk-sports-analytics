<?php

namespace App\Imports;

use App\Models\Package;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PricesImport implements ToModel, WithHeadingRow
{
    public $data;

    public function __construct()
    {
        $this->data = collect();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $dataList = [];
        if(isset($row['amount']) && isset($row['price_id']) && isset($row['product_name'])) {

            if ($row['interval'] == '' || $row['interval'] == null) {
                $dataList = ['price' => $row['amount'], 'stripe_id' => $row['price_id'], 'identifier' => str_replace(' ', '', $row['product_name'])];
            } else {
                $dataList = ['subscription_price' => $row['amount'], 'subscription_id' => $row['price_id'], 'identifier' => str_replace(' ', '', $row['product_name'])];
            }

            $model = Package::updateOrCreate([
                'name' => $row['product_name'],
            ], $dataList);

            $this->data->push($model);

            return $model;
        }else{
            return Package::first();
        }
    }
}
