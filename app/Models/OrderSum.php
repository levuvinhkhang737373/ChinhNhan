<?php
namespace App\Models;

use App\Models\InvoiceOrder;
use App\Models\Member;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSum extends Model
{
    use HasFactory;
    protected $table      = 'order_sum';
    protected $primaryKey = 'order_id';
    public $timestamps    = true;
    protected $fillable   = [
        'order_code', 'MaKH', 'd_name', 'd_address', 'd_phone', 'd_email',
        'total_cart', 'total_price', 'shipping_method', 'payment_method', 'status', 'date_order', 'comment', 'note',
        'display', 'mem_id', 'CouponDiscout', 'userManual',
        'accumulatedPoints', 'accumulatedPoints_1', 'date_order_status1', 'date_order_status2',
        'date_order_status3', 'date_order_status4', 'date_order_status5', 'date_order_status6',
        'date_order_status7', 'list_group_product', 'is_viewed', 'hh_is_viewed',
        'delivery_unit_id', 'hh_status', 'hh_is_transfer',
        'is_receiver_other', 'other_receiver_name', 'other_receiver_phone', 'other_receiver_address',
    ];

    public function deliveryUnit()
    {
        return $this->belongsTo(DeliveryUnit::class, 'delivery_unit_id');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'status', 'status_id');
    }
    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }
    public function orderAddress()
    {
        return $this->belongsTo(OrderAddress::class, 'order_id', 'order_id');
    }
    public function invoiceOrder()
    {
        return $this->belongsTo(InvoiceOrder::class, 'order_id', 'order_id');
    }
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method', 'name');
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'name');
    }
    public function member()
    {
        return $this->belongsTo(Member::class, 'mem_id', 'id');
    }

    protected $appends = ['hh_status_text'];

    public function getHhStatusTextAttribute()
    {
        $status = [
            0 => 'Chờ xử lý',
            1 => 'Đang chuẩn bị hàng',
            2 => 'Chuẩn bị hàng hoàn tất',
            3 => 'Đã bàn giao Chính Nhân',
        ];
        return $status[$this->hh_status] ?? 'Không xác định';
    }
}
