<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $valid = ['partner', 'workation', 'contact'];

        $query = FormSubmission::query()
            ->when($type && in_array($type, $valid), fn ($q) => $q->where('type', $type))
            ->orderByDesc('created_at');

        $submissions = $query->paginate(25)->withQueryString();

        $counts = FormSubmission::query()
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        return view('admin.submissions', [
            'submissions' => $submissions,
            'counts' => $counts,
            'activeType' => in_array($type, $valid) ? $type : null,
        ]);
    }

    public function show(FormSubmission $submission)
    {
        return view('admin.submission-detail', ['submission' => $submission]);
    }

    public function destroy(FormSubmission $submission)
    {
        $submission->delete();

        return redirect()
            ->route('admin.submissions.index')
            ->with('flash', 'Submission deleted.');
    }
}
