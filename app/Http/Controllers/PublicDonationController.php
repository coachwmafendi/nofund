<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class PublicDonationController extends Controller
{
    public function show(Campaign $campaign)
    {
        if ($campaign->status->value !== 'active') {
            abort(404);
        }

        return view('campaigns.public-donate', compact('campaign'));
    }
}
