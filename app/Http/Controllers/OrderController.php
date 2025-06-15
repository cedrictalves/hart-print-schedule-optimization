<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display the order form view.
     */
    public function display()
    {
        return view('order');
    }

    /**
     * Handle AJAX request to get products by type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductsByType(Request $request)
    {
        $request->validate([
            'type' => 'required|integer|in:1,2,3',
        ]);

        $products = Product::where('type', $request->type)->get();

        return response()->json($products);
    }

    /**
     * Store a new order and its items in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate required fields
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'need_by_date' => 'required|date',
            'type' => 'required|integer|in:1,2,3',
            'products' => 'required|array',
            'products.*' => 'nullable|integer|min:0',
        ]);

        // Check if there is at least one product with quantity > 0
        $hasQuantity = collect($request->products)->filter(fn($qty) => $qty > 0)->isNotEmpty();

        // If no product has a quantity greater than 0, return with an error
        if (! $hasQuantity) {
            return redirect()->back()->withInput()->withErrors([
                'products' => 'You must specify a quantity greater than 0 for at least one product.',
            ]);
        }

        // Use DB transaction to ensure consistency
        DB::transaction(function () use ($request) {
            // Create the order
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'need_by_date' => $request->need_by_date,
                'type' => $request->type,
                'production_date' => now()->addDay(), // Assuming production date is tomorrow
            ]);

            // Filter out products with quantity > 0
            foreach ($request->products as $productId => $quantity) {
                if ($quantity > 0) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => $quantity * 500,
                    ]);
                }
            }
        });

        return redirect('/order')->with('success', 'Order created successfully.');
    }

}
