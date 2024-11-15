<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    const PRODUCT_PAGINATE = 9;

    public function list()
    {
        $products = Product::paginate(self::PRODUCT_PAGINATE);
        return view('products.list', compact('products'));
    }

    public function add()
    {
        return view('products.add', [
            'product' => new Product,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->all());

        return redirect()->route('products.list')
            ->with('info', 'Product added');
    }

    public function edit($id, Product $product)
    {
        $product = Product::find($id);
        return view('products.edit', compact('product'));
    }

    public function update($id, UpdateProductRequest $request)
    {
        $product              = Product::find($id);
        $product->name        = $request->input('name');
        $product->description = $request->input('description');
        $product->save();

        return redirect()->route('products.list')->with('succes', 'user updated succesfully.');
    }
}