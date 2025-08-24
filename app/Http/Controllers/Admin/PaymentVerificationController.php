<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaymentReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentVerificationController extends Controller
{
    protected $orderService;
    protected $paymentReceiptService;
    
    public function __construct(OrderService $orderService, PaymentReceiptService $paymentReceiptService)
    {
        $this->orderService = $orderService;
        $this->paymentReceiptService = $paymentReceiptService;
    }
    
    /**
     * Display all pending payment verifications.
     */
    public function index(Request $request)
    {
        // Get pending orders
        $ordersQuery = Order::whereNotNull('payment_receipt')
                           ->where('payment_status', 'pending')
                           ->with('user');
                           
        // Apply date filter if provided
        if ($request->has('date_from')) {
            $dateFrom = Carbon::parse($request->date_from)->startOfDay();
            $ordersQuery->where('payment_receipt_uploaded_at', '>=', $dateFrom);
        }
        
        if ($request->has('date_to')) {
            $dateTo = Carbon::parse($request->date_to)->endOfDay();
            $ordersQuery->where('payment_receipt_uploaded_at', '<=', $dateTo);
        }
        
        $orders = $ordersQuery->latest('payment_receipt_uploaded_at')->get();
        
        // Add type to orders for consistency
        $pendingPayments = $orders->map(function($order) {
            $order->type = 'order';
            return $order;
        });
        
        return view('admin.payment-verifications.index', compact('pendingPayments'));
    }
    
    /**
     * Show payment verification details for an order.
     */
    public function showOrder(Order $order)
    {
        if (!$order->payment_receipt) {
            return redirect()->route('admin.payment-verifications.index')
                ->with('error', 'No payment receipt found for this order.');
        }
        
        return view('admin.payment-verifications.show-order', compact('order'));
    }
    
    /**
     * Verify order payment.
     */
    public function verifyOrder(Request $request, Order $order)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);
        
        try {
            // Use the PaymentReceiptService to verify and complete the order
            $this->paymentReceiptService->verifyPayment(
                $order, 
                Auth::id(), 
                $request->admin_notes
            );
            
            return redirect()->route('admin.payment-verifications.index')
                ->with('success', 'Order payment verified successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to verify payment: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject order payment.
     */
    public function rejectOrder(Request $request, Order $order)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ]);
        
        // Use the PaymentReceiptService to reject the payment
        $this->paymentReceiptService->rejectPayment(
            $order,
            Auth::id(),
            $request->admin_notes
        );
        
        return redirect()->route('admin.payment-verifications.index')
            ->with('success', 'Order payment rejected.');
    }
    
    /**
     * View payment receipt for order.
     */
    public function viewOrderReceipt(Order $order)
    {
        if (!$order->payment_receipt) {
            abort(404, 'No payment receipt found.');
        }

        $fullPath = Storage::disk('private')->path($order->payment_receipt);
        return response()->file($fullPath);
    }

}