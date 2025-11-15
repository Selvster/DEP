<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Center;
use App\Models\RequestType;
use App\Models\RequestStatus;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class PublicRequestsController extends Controller
{
    /**
     * Show the public form for submitting a request.
     */
    public function create(): View
    {
        $centers = Center::where('is_active', true)->get();
        $requestTypes = RequestType::where('is_active', true)->get();
        
        return view('public.requests.create', compact('centers', 'requestTypes'));
    }

    /**
     * Store a newly submitted public request.
     */
    public function store(HttpRequest $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:14',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'center_id' => 'required|exists:centers,id',
            'request_type_id' => 'required|exists:request_types,id',
            'description' => 'required|string',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Get the default status (first active status or create one)
        $defaultStatus = RequestStatus::where('is_active', true)->first();
        if (!$defaultStatus) {
            $defaultStatus = RequestStatus::create([
                'name' => 'جاري المراجعة',
                'color' => '#3B82F6',
                'is_active' => true,
            ]);
        }
        
        $validated['status_id'] = $defaultStatus->id;
        
        // Handle document uploads
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('requests/documents', 'public');
                $documents[] = $path;
            }
        }
        $validated['documents'] = $documents;

        $req = Request::create($validated);

        activity()
            ->withProperties([
                'action' => 'created',
                'tracking_number' => $req->tracking_number,
                'requester_name' => $validated['name'],
                'source' => 'public_form',
            ])
            ->log('تم تقديم طلب جديد من النموذج العام: ' . $req->tracking_number);

        return redirect()->route('public.requests.success', ['tracking_number' => $req->tracking_number]);
    }

    /**
     * Show success page after submitting a request.
     */
    public function success($tracking_number): View
    {
        $request = Request::where('tracking_number', $tracking_number)->firstOrFail();
        return view('public.requests.success', compact('request'));
    }

    /**
     * Show the tracking page.
     */
    public function trackForm(): View
    {
        return view('public.requests.track');
    }

    /**
     * Track a request by tracking number, national ID, or phone.
     */
    public function track(HttpRequest $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'search' => 'required|string',
        ]);

        $search = $validated['search'];

        // Try to find by tracking number, national ID, or phone
        $req = Request::where('tracking_number', $search)
            ->orWhere('national_id', $search)
            ->orWhere('phone', $search)
            ->with(['center', 'requestType', 'status'])
            ->first();

        if (!$req) {
            return redirect()->route('public.requests.track')
                ->with('error', 'لم يتم العثور على طلب بهذه البيانات. يرجى التحقق من رقم التتبع أو الرقم القومي أو رقم الهاتف.');
        }

        return view('public.requests.result', compact('req'));
    }

    /**
     * Download response document.
     */
    public function downloadResponse($tracking_number)
    {
        $request = Request::where('tracking_number', $tracking_number)->firstOrFail();

        if (!$request->response_document) {
            abort(404, 'لم يتم رفع رد على هذا الطلب بعد');
        }

        return response()->download(storage_path('app/public/' . $request->response_document));
    }
}

