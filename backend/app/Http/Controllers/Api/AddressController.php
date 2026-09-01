<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()
            ->orderBy('is_primary', 'desc')
            ->latest()
            ->get();

        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_primary' => 'boolean',
            'notes' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $isFirst = $user->addresses()->count() === 0;
        $isPrimary = $request->boolean('is_primary') || $isFirst;

        if ($isPrimary) {
            $user->addresses()->update(['is_primary' => false]);
        }

        $address = $user->addresses()->create([
            ...$validated,
            'is_primary' => $isPrimary,
        ]);

        return response()->json([
            'message' => 'Alamat pengiriman berhasil ditambahkan.',
            'address' => $address,
        ], 201);
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'label' => 'sometimes|required|string|max:50',
            'recipient_name' => 'sometimes|required|string|max:100',
            'phone' => 'sometimes|required|string|max:20',
            'address_line' => 'sometimes|required|string',
            'city' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:10',
            'is_primary' => 'boolean',
            'notes' => 'nullable|string|max:255',
        ]);

        if ($request->boolean('is_primary')) {
            $request->user()->addresses()->where('id', '!=', $address->id)->update(['is_primary' => false]);
        }

        $address->update($validated);

        return response()->json([
            'message' => 'Alamat pengiriman berhasil diperbarui.',
            'address' => $address,
        ]);
    }

    public function destroy(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $wasPrimary = $address->is_primary;
        $address->delete();

        // If deleted address was primary, set another address as primary if available
        if ($wasPrimary) {
            $next = $request->user()->addresses()->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        return response()->json([
            'message' => 'Alamat berhasil dihapus.',
        ]);
    }

    public function setPrimary(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->user()->addresses()->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        return response()->json([
            'message' => 'Alamat berhasil diatur sebagai alamat utama.',
            'address' => $address,
        ]);
    }
}
