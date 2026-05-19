<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('order_number', 'like', "%{$request->search}%");
        }

        $orders = $query->paginate(20)->through(fn($o) => [
            'id' => $o->id,
            'order_number' => $o->order_number,
            'customer' => $o->user?->name ?? $o->guest_name ?? 'Guest',
            'status' => $o->status,
            'status_label' => $o->status_label,
            'status_color' => $o->status_color,
            'total' => $o->total,
            'payment_method' => $o->payment_method,
            'payment_status' => $o->payment_status,
            'created_at' => $o->created_at->format('d M Y'),
        ]);

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    public function show(Order $order): Response
    {
        $order->load(['items', 'user']);

        return Inertia::render('Admin/Orders/Show', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_label' => $order->status_label,
                'customer' => $order->user ? [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone,
                ] : [
                    'name' => $order->guest_name,
                    'email' => $order->guest_email,
                    'phone' => $order->guest_phone,
                ],
                'delivery_address' => $order->delivery_address,
                'delivery_city' => $order->delivery_city,
                'subtotal' => $order->subtotal,
                'delivery_charge' => $order->delivery_charge,
                'discount' => $order->discount,
                'total' => $order->total,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'notes' => $order->notes,
                'created_at' => $order->created_at->format('d M Y, h:i A'),
                'items' => $order->items->map(fn($i) => [
                    'id' => $i->id,
                    'product_name' => $i->product_name,
                    'product_image' => $i->product_image,
                    'price' => $i->price,
                    'quantity' => $i->quantity,
                    'total' => $i->total,
                ]),
            ],
        ]);
    }

    public function data(Order $order): \Illuminate\Http\JsonResponse
    {
        $order->load(['items', 'user']);

        return response()->json([
            'id'               => $order->id,
            'order_number'     => $order->order_number,
            'status'           => $order->status,
            'status_label'     => $order->status_label,
            'customer'         => $order->user ? [
                'name'  => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
            ] : [
                'name'  => $order->guest_name,
                'email' => $order->guest_email,
                'phone' => $order->guest_phone,
            ],
            'delivery_address' => $order->delivery_address,
            'delivery_city'    => $order->delivery_city,
            'subtotal'         => $order->subtotal,
            'delivery_charge'  => $order->delivery_charge,
            'discount'         => $order->discount,
            'total'            => $order->total,
            'payment_method'   => $order->payment_method,
            'payment_status'   => $order->payment_status,
            'notes'            => $order->notes,
            'created_at'       => $order->created_at->format('d M Y, h:i A'),
            'items'            => $order->items->map(fn($i) => [
                'id'            => $i->id,
                'product_name'  => $i->product_name,
                'product_image' => $i->product_image,
                'price'         => $i->price,
                'quantity'      => $i->quantity,
                'total'         => $i->total,
            ]),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,out_for_delivery,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated!');
    }
}
