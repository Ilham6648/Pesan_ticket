<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\event;
use App\Models\orderdetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = event::all();
        return view('customer.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'items' => 'required|array',
            'items.*' => 'nullable|integer|min:0',
        ]);

        $orderedItems = array_filter($request->items ?? [], fn ($qty) => (int) $qty > 0);

        if (empty($orderedItems)) {
            return redirect()->back()->withErrors(['items' => 'Pilih satu atau lebih item untuk dipesan.']);
        }

        $order = DB::transaction(function () use ($request, $orderedItems) {
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'total_price' => 0,
                'status' => 'pending',
            ]);

            $totalPrice = 0;

            foreach ($orderedItems as $eventId => $quantity) {
                $event = event::findOrFail($eventId);
                $subtotal = $event->harga_per_tiket * $quantity;
                $totalPrice += $subtotal;

                orderdetail::create([
                    'order_id' => $order->id,
                    'event_id' => $event->id,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update([
                'total_price' => $totalPrice,
            ]);

            return $order;
        });

        return redirect()->route('customer.index')->with('success', 'Pesanan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        //
    }

    public function adminDahboard()
    {
        $orders = order::with('orderdetails.event')->latest()->get();
        return view('dashboard', compact('orders'));
    }

    public function dashboard()
    {
        $orders = order::with('orderdetails.event')->latest()->get();
        return view('dashboard', compact('orders'));
    }

    public function updateDashboard(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $order = order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return redirect()->route('dashboard')->with('success', 'Status pesanan #' . $order->id . ' berhasil diperbarui.');
    }
    
}
