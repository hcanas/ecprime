<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return auth()->check();
    }

    public function view(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->email === $purchaseOrder->quotation->customer->email
            or ($user->role !== 'affiliate' and $purchaseOrder->status !== 'pending');
    }

    public function update(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->role !== 'affiliate' and $purchaseOrder->status === 'pending';
    }
}
