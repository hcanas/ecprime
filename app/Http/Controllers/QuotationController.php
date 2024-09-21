<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Mail\QuotationSent;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Throwable;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Quotation::class);

        $data = Quotation::search($request->get('query'), function ($driver, $query, $options) use ($request) {
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

        return Inertia::render('Quotations/Index', [
            'quotations' => $data->paginate($request->get('per_page', 10))
                ->withQueryString()
                ->through(fn ($quotation) => [
                    ...$quotation->toArray(),
                    'customer' => $quotation->customer,
                    'items' => $quotation->items,
                    'amount' => $quotation->items()->where('status', 'available')->sum(DB::raw('quantity * price')),
                    'can' => [
                        'update_quotation' => Auth::user()->can('update', $quotation),
                        'view_quotation' => Auth::user()->can('view', $quotation),
                    ],
                ]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(Quotation $quotation)
    {
        Gate::authorize('view', $quotation);

        return Inertia::render('Quotations/ViewQuotation', [
            'quotation' => [
                ...$quotation->toArray(),
                'purchase_order' => $quotation->purchaseOrder,
                'customer' => $quotation->customer,
                'items' => $quotation->items->map(fn ($item) => [
                    ...$item->toArray(),
                    'image' => Storage::exists('public/images/'.$item->image) ? $item->image : null,
                ]),
                'last_modified_by' => $quotation->lastModifiedBy,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewQuotationRequest $request)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::where('email', $request->validated('email'))->first();

            if ($customer) {
                $customer->fill([
                    'contact_number' => $request->validated('contact_number'),
                    'viber_id' => $request->validated('viber_id'),
                ])->save();
            } else {
                $customer = Customer::create([
                    'email' => $request->validated('email'),
                    'contact_number' => $request->validated('contact_number'),
                    'viber_id' => $request->validated('viber_id'),
                ]);
            }

            $quotation = Quotation::create([
                'reference_number' => 'QR' . date('ymd') . strtoupper(bin2hex(random_bytes(4))),
                'customer_id' => $customer->id,
                'status' => 'draft',
            ]);

            foreach ($request->validated('items') as $item) {
                $product = Product::find($item['id']);

                $quotation->items()->create([
                    'image' => $product->image,
                    'name' => $product->name,
                    'description' => $product->description,
                    'brand' => $item['brand'],
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'measurement_unit' => $product->measurementUnit->name,
                    'status' => $product->status,
                ]);
            }

            DB::commit();

            return back()->with('message', [
                'content' => 'Your request for quotation has been created. Your reference number is '
                    . $quotation->reference_number . '.',
                'type' => 'success',
            ]);
        } catch (Throwable $e) {
            logger($e);
            return back()->with('message', [
                'content' => 'Something went wrong.',
                'type' => 'error',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quotation $quotation)
    {
        Gate::authorize('update', $quotation);

        return Inertia::render('Quotations/EditQuotation', [
            'can' => [
                'update_quotation' => Auth::user()->can('update', $quotation),
            ],
            'quotation' => [
                ...$quotation->toArray(),
                'purchase_order' => $quotation->purchaseOrder,
                'customer' => $quotation->customer,
                'items' => $quotation->items->map(fn ($item) => [
                    ...$item->toArray(),
                    'image' => Storage::exists('public/images/'.$item->image) ? $item->image : null,
                ]),
                'last_modified_by' => $quotation->lastModifiedBy,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuotationRequest $request, Quotation $quotation)
    {
        Gate::authorize('update', $quotation);

        try {
            DB::beginTransaction();

            if ($request->validated('status') === 'sent') {
                Gate::authorize('sendToEmail', $quotation);

                Mail::to($quotation->customer->email)->send(new QuotationSent($quotation));
                $quotation->fill(['status' => 'sent']);
                $message = 'Quotation has been sent.';

                // manually log activity if status has not changed
                if ($quotation->getOriginal('status') === 'sent') $quotation->logActivity();
            } elseif ($request->validated('status') === 'confirmed') {
                Gate::authorize('confirm', $quotation);

                $purchase_order = PurchaseOrder::create([
                    'reference_number' => 'PO' . date('ymd') . strtoupper(bin2hex(random_bytes(4))),
                    'quotation_id' => $quotation->id,
                    'status' => 'pending',
                ]);

                foreach ($quotation->items()->where('status', 'available')->get() as $item) {
                    $purchase_order->items()->create([
                        'image' => $item['image'],
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'brand' => $item['brand'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'measurement_unit' => $item['measurement_unit'],
                    ]);
                }

                $quotation->fill(['status' => 'confirmed']);
                $message = 'Purchase order has been created.';
            } else {
                $quotation->fill(['status' => $request->validated('status')]);
                $message = 'Quotation has been updated.';
            }

            $quotation->save();

            if ($request->validated('items')) {
                foreach ($request->validated('items') as $item) {
                    $quotation->items()->where('id', $item['id'])->update([
                        'brand' => $item['brand'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'measurement_unit' => $item['measurement_unit'],
                        'status' => $item['status'],
                    ]);
                }

                if (!$quotation->wasChanged()) $quotation->touch();
            }

            DB::commit();

            if (in_array($quotation->status, ['pending', 'confirmed', 'cancelled'])) {
                return redirect()->route('quotations.show', ['quotation' => $quotation->id]);
            }

            return back()->with('message', [
                'content' => $message,
                'type' => 'success',
            ]);
        } catch (Throwable $e) {
            logger($e);
            return back()->with('message', [
                'content' => 'Failed to update quotation.',
                'type' => 'error',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotation $quotation)
    {
        //
    }
}
