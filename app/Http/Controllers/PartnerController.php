<?php

namespace App\Http\Controllers;

use App\Models\Partner;

class PartnerController extends Controller
{
    public function show(Partner $partner)
    {
        abort_unless($partner->published, 404);

        return view('partners.show', ['partner' => $partner]);
    }
}
