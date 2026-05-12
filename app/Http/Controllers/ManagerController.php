<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ManagerController extends Controller
{
    public function orders()
    {
        $orders = Order::orderByDesc('created_at')->get();
        return view('manager.orders', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:new,processing,done',
        ]);

        $order->update($data);
        return back()->with('success', 'Статус заказа обновлён.');
    }

    public function exportOrders(): StreamedResponse
    {
        $orders = Order::orderByDesc('created_at')->get();

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['ID', 'Клиент', 'Телефон', 'Сумма', 'Статус'], ';');
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->customer_name,
                    $order->customer_phone,
                    number_format((float) $order->total, 0, '.', ''),
                    $order->status,
                ], ';');
            }
            fclose($handle);
        }, 'orders-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function feedback()
    {
        $messages = ContactMessage::orderByDesc('created_at')->get();

        return view('manager.feedback', compact('messages'));
    }

    public function replyToContact(Request $request, ContactMessage $contactMessage)
    {
        $data = $request->validate([
            'manager_reply' => 'required|string|max:10000',
        ]);

        $contactMessage->update([
            'manager_reply' => $data['manager_reply'],
            'manager_replied_at' => now(),
        ]);

        return back()->with('success', 'Ответ отправлен клиенту.');
    }
}
