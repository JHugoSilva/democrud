<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index() {
        return $this->product->latest()->get();
    }

    public function store(Request $request) {

        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric'
        ]);

        if (!$validated->fails()) {
            $product = $this->product->create($request->all());
            return response()->json(['success' => $product], 201);
        } else {
            return response()->json(['erros' => $validated->errors()->first()]);
        }
    }

    public function show(int $id) {
        $product = $this->product->find($id);
        return response()->json($product, 200);
    }

    public function update(Request $request, int $id) {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric'
        ]);

        $product = $this->product->find($id);

        if (!$product) {
            return response()->json(['error' => 'Produto não localizado'], 404);
        }

        if (!$validated->fails()) {
            $product = $product->update($request->all());
            return response()->json(['success' => $product], 200);
        } else {
            return response()->json(['erros' => $validated->errors()->first()]);
        }
    }

    public function destroy(int $id) {
        $product = $this->product->find($id);
        if (!$product) {
            return response()->json(['error' => 'Produto não localizado'], 404);
        }
        $product->delete();
        return response()->json(['error' => 'Produto excluído'], 200);
    }
}
