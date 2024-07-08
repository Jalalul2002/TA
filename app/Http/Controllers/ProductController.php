<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::with('user')->search($search)->paginate(10);
        return view('barang', compact('products', 'search'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product')->with('success', 'Barang deleted successfully');
    }

    public function add()
    {
        return view('add-barang');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_name' => ['required', 'string', 'max:255']
        ]);

        Product::create([
            'product_name' => $request->product_name,
            'product_description' => $request->product_description,
            'created_by' => Auth::id(),
        ]);

        return redirect(route('product', absolute: false));
    }
}
