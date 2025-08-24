<?php
namespace App\Services;

use App\Models\Order;
use App\Models\User;  // ADD THIS LINE - This was missing!
use App\Models\UserCourse;
use App\Models\ProductKey;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentReceiptService
{
    /**
     * Handle receipt upload for an order.
     *
     * @param UploadedFile $file
     * @param Order $order
     * @return string
     */
    public function handleReceiptUpload(UploadedFile $file, Order $order)
    {
        // Generate unique filename
        $filename = $this->generateFilename($file, 'order', $order->id);
        
        // Store file in private storage
        $path = $file->storeAs(
            'payment_receipts/orders/' . date('Y/m'),
            $filename,
            'private'
        );
        
        // Update order with receipt info
        $order->update([
            'payment_receipt' => $path,
            'payment_receipt_uploaded_at' => now()
        ]);
        
        return $path;
    }
    
    /**
     * Verify payment for an order.
     *
     * @param Order $order
     * @param int $adminId
     * @param string|null $notes
     * @return Order
     */
    public function verifyPayment(Order $order, $adminId, $notes = null)
    {
        $order->update([
            'payment_status' => 'completed',
            'payment_verified_by' => $adminId,
            'payment_verified_at' => now(),
            'admin_notes' => $notes
        ]);
        
        // Complete the order and grant access
        app(OrderService::class)->completeOrder($order);
        
        // Notify user
        $this->notifyUserPaymentStatus($order, 'verified');
        
        return $order;
    }
    
    
    /**
     * Reject payment for an order.
     *
     * @param Order $order
     * @param int $adminId
     * @param string $reason
     * @return Order
     */
    public function rejectPayment(Order $order, $adminId, $reason)
    {
        $order->update([
            'payment_status' => 'failed',
            'payment_verified_by' => $adminId,
            'payment_verified_at' => now(),
            'admin_notes' => $reason
        ]);
        
        // Notify user
        $this->notifyUserPaymentStatus($order, 'rejected', $reason);
        
        return $order;
    }
    
    /**
     * Get payment receipt file response.
     *
     * @param string $path
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getReceiptResponse($path)
    {
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'Receipt not found');
        }

        $fullPath = Storage::disk('private')->path($path);
        return response()->file($fullPath);
    }
    
    /**
     * Delete payment receipt.
     *
     * @param string $path
     * @return bool
     */
    public function deleteReceipt($path)
    {
        return Storage::disk('private')->delete($path);
    }
    
    /**
     * Generate unique filename for receipt.
     *
     * @param UploadedFile $file
     * @param string $type
     * @param int $id
     * @return string
     */
    protected function generateFilename(UploadedFile $file, $type, $id)
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = Str::random(6);
        
        return "{$type}_{$id}_{$timestamp}_{$random}.{$extension}";
    }
    
    /**
     * Notify user about payment status (placeholder for future implementation).
     *
     * @param Order $order
     * @param string $status
     * @param string|null $reason
     * @return void
     */
    protected function notifyUserPaymentStatus(Order $order, $status, $reason = null)
    {
        // TODO: Implement email notification
        // For now, this is a placeholder
        Log::info("Payment {$status} for order {$order->order_number}", [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'reason' => $reason
        ]);
    }
    
    
    /**
     * Get bank details for display.
     *
     * @return array
     */
    public function getBankDetails()
    {
        return [
            'bank_name' => env('BANK_NAME', 'Commercial Bank of Ceylon'),
            'account_name' => env('BANK_ACCOUNT_NAME', 'Cash Mind Pvt Ltd'),
            'account_number' => env('BANK_ACCOUNT_NUMBER', '1234567890'),
            'branch' => env('BANK_BRANCH', 'Colombo'),
            'swift_code' => env('BANK_SWIFT_CODE', ''),
        ];
    }
    
    /**
     * Validate receipt file.
     *
     * @param UploadedFile $file
     * @return bool
     */
    public function validateReceiptFile(UploadedFile $file)
    {
        // Check file size (5MB max)
        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new \Exception('File size must not exceed 5MB');
        }
        
        // Check file type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('File must be JPG, PNG, or PDF format');
        }
        
        return true;
    }
}