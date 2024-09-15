<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseOrderRequest;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Throwable;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', PurchaseOrder::class);

        $data = PurchaseOrder::search($request->get('query'), function ($driver, $query, $options) use ($request) {
            $filters = [];

            if ($request->get('status')) {
                $filters[] = 'status = "' . $request->get('status') . '"';
            }

            if (Auth::user()->role === 'affiliate') {
                $filters[] = 'customer.email = "' . Auth::user()->email . '"';
            }

            $options['filter'] = implode(' AND ', $filters);
            $options['sort'] = ['updated_at:desc'];

            return $driver->search($query, $options);
        });

        return Inertia::render('PurchaseOrders/Index', [
            'purchase_orders' => $data->paginate(10)
                ->withQueryString()
                ->through(fn ($purchase_order) => [
                    'id' => $purchase_order->id,
                    'reference_number' => $purchase_order->reference_number,
                    'customer' => $purchase_order->quotation->customer,
                    'quotation_id' => $purchase_order->quotation_id,
                    'quotation_reference_number' => $purchase_order->quotation->reference_number,
                    'items_count' => $purchase_order->items->count(),
                    'amount' => $purchase_order->items()->sum(DB::raw('quantity * price')),
                    'payment_details' => $purchase_order->payment_details,
                    'delivery_date' => $purchase_order->delivery_date,
                    'status' => $purchase_order->status,
                    'can' => [
                        'view_purchase_order' => Auth::user()->can('view', $purchase_order),
                        'update_purchase_order' => Auth::user()->can('update', $purchase_order),
                    ],
                ]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(PurchaseOrder $purchase_order)
    {
        Gate::authorize('view', $purchase_order);

        return Inertia::render('PurchaseOrders/ViewPurchaseOrder', [
            'purchase_order' => [
                ...$purchase_order->toArray(),
                'quotation_reference_number' => $purchase_order->quotation->reference_number,
                'customer' => $purchase_order->quotation->customer,
                'items' => $purchase_order->items->map(fn ($item) => [
                    ...$item->toArray(),
                    'image' => Storage::exists('public/images/'.$item->image) ? $item->image : null,
                ]),
                'last_modified_by' => $purchase_order->lastModifiedBy,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchase_order)
    {
        Gate::authorize('update', $purchase_order);

        return Inertia::render('PurchaseOrders/EditPurchaseOrder', [
            'purchase_order' => [
                ...$purchase_order->toArray(),
                'quotation_reference_number' => $purchase_order->quotation->reference_number,
                'customer' => $purchase_order->quotation->customer,
                'items' => $purchase_order->items->map(fn ($item) => [
                    ...$item->toArray(),
                    'image' => Storage::exists('public/images/'.$item->image) ? $item->image : null,
                ]),
                'last_modified_by' => $purchase_order->lastModifiedBy,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        Gate::authorize('update', $purchaseOrder);

        try {
            DB::beginTransaction();

            $purchaseOrder->fill([
                'payment_details' => $request->validated('payment_details'),
                'delivery_date' => $request->validated('delivery_date'),
                'status' => $request->validated('status'),
            ])->save();

            if ($request->validated('items')) {
                foreach ($request->validated('items') as $item) {
                    $purchaseOrder->items()->where('id', $item['id'])->update([
                        'brand' => $item['brand'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'measurement_unit' => $item['measurement_unit'],
                    ]);
                }

                if (!$purchaseOrder->wasChanged()) $purchaseOrder->logActivity();
            }

            DB::commit();

            if ($purchaseOrder->status !== 'pending') {
                return redirect()->route('purchase_orders.show', ['purchase_order' => $purchaseOrder->id])
                    ->with('message', [
                        'content' => 'Purchase order has been ' . $purchaseOrder->status . '.',
                        'type' => $purchaseOrder->status === 'delivered' ? 'success' : 'error',
                    ]);
            }

            return back()->with('message', [
                'content' => 'Purchase order has been updated.',
                'type' => 'success',
            ]);
        } catch (Throwable $e) {
            logger($e);
            return back()->with('message', [
                'content' => 'Failed to update purchase order.',
                'type' => 'error',
            ]);
        }
    }
}
