<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;

class QuotationPolicy
{
    public function viewAny(User $user): bool
    {
        return auth()->check();
    }

    public function view(User $user, Quotation $quotation): bool
    {
        if ($user->role === 'affiliate' AND $user->email === $quotation->customer->email) return true;

        if ($user->role === 'regular' AND in_array($quotation->status, ['pending', 'confirmed', 'cancelled'])) {
            return true;
        }

        if ($user->role === 'admin' AND in_array($quotation->status, ['confirmed', 'cancelled'])) return true;

        return false;
    }

    public function update(User $user, Quotation $quotation): bool
    {
        if (!in_array($quotation->status, ['confirmed', 'cancelled'])) {
            if ($user->role === 'regular' AND $quotation->status !== 'pending') return true;

            if ($user->role === 'admin') return true;

            return false;
        }

        return false;
    }

    public function confirm(User $user, Quotation $quotation): bool
    {
        return $user->role !== 'affiliate' AND $quotation->status === 'sent';
    }

    public function sendToEmail(User $user, Quotation $quotation): bool
    {
        return ($user->role === 'regular' AND $quotation->status === 'sent')
            OR ($user->role === 'admin' AND !in_array($quotation->status, ['confirmed', 'cancelled']));
    }
}
