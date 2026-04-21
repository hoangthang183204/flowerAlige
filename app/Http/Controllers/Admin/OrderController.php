<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,shipping,completed,cancelled'],
        ]);

        $newStatus = $validated['status'];

        if ($order->status === $newStatus) {
            return redirect()->route('admin.orders.show', $order)->with('info', 'Trạng thái không thay đổi.');
        }

        if (! $order->canTransitionTo($newStatus)) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', "Không thể chuyển trạng thái từ {$order->status} sang {$newStatus}.");
        }

        $order->update([
            'status' => $newStatus,
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
}
