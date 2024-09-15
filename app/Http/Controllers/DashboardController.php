<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $purchase_orders = DB::table('purchase_orders', 'PO')
            ->leftJoin('purchase_order_items AS POI', 'POI.purchase_order_id', 'PO.id')
            ->select(DB::raw(
                ($request->get('month')
                    ? 'DAY(CONVERT_TZ(IF(PO.delivery_date IS NULL, PO.created_at, PO.delivery_date), "GMT", "+08:00")) AS day,'
                    : 'MONTH(CONVERT_TZ(IF(PO.delivery_date IS NULL, PO.created_at, PO.delivery_date), "GMT", "+08:00")) AS month,'
                ). 'COUNT(DISTINCT(CASE
                    WHEN PO.status = "pending"
                    THEN PO.id
                    ELSE NULL
                END)) AS pending_delivery_count,
                SUM(CASE
                    WHEN PO.status = "pending"
                    THEN POI.quantity * POI.price
                    ELSE 0
                END) AS pending_delivery_amount,
                COUNT(DISTINCT(CASE
                    WHEN PO.status = "delivered"
                    THEN PO.id
                    ELSE NULL
                END)) AS delivered_count,
                SUM(CASE
                    WHEN PO.status = "delivered"
                    THEN POI.quantity * POI.price
                    ELSE 0
                END) AS delivered_amount,
                COUNT(DISTINCT(CASE
                    WHEN PO.status = "cancelled"
                    THEN PO.id
                    ELSE NULL
                END)) AS cancelled_count,
                SUM(CASE
                    WHEN PO.status = "cancelled"
                    THEN POI.quantity * POI.price
                    ELSE 0
                END) AS cancelled_amount'
            ));

        if ($request->get('month')) {
            $purchase_orders->groupBy('day')
                ->orderBy('day')
                ->where(
                    DB::raw('MONTH(CONVERT_TZ(PO.updated_at, "GMT", "+08:00"))'),
                    $request->get('month')
                );
        } else {
            $purchase_orders->groupBy('month')
                ->orderBy('month');
        }

        if ($request->get('year')) {
            $purchase_orders->where(
                DB::raw('YEAR(CONVERT_TZ(PO.updated_at, "GMT", "+08:00"))'),
                $request->get('year')
            );
        }

        return Inertia::render('Dashboard', [
            'purchase_orders' => $purchase_orders->get(),
            'trending_products' => (new Product())->popular(8)->map(fn ($product) => [
                'id' => $product->product_id,
                'image' => Storage::exists('public/images/'.$product->image) ? $product->image : null,
                'name' => $product->name,
            ]),
        ]);
    }
}
