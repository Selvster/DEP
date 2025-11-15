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
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(HttpRequest $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $requests = Request::with(['center', 'requestType', 'status', 'creator'])
                ->select(['id', 'tracking_number', 'name', 'national_id', 'phone', 'center_id', 'request_type_id', 'status_id', 'created_at', 'created_by'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($requests)
                ->addColumn('center_name', function ($req) {
                    return $req->center ? $req->center->name : 'غير محدد';
                })
                ->addColumn('request_type_name', function ($req) {
                    return $req->requestType ? $req->requestType->name : 'غير محدد';
                })
                ->addColumn('status_name', function ($req) {
                    if ($req->status) {
                        return '<span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: ' . $req->status->color . '20; color: ' . $req->status->color . '">' . $req->status->name . '</span>';
                    }
                    return 'غير محدد';
                })
                ->addColumn('created_at_formatted', function ($req) {
                    return $req->created_at->format('Y-m-d H:i');
                })
                ->addColumn('actions', function ($req) {
                    return view('requests.partials.actions', compact('req'))->render();
                })
                ->rawColumns(['status_name', 'actions'])
                ->make(true);
        }

        return view('requests.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $centers = Center::where('is_active', true)->get();
        $requestTypes = RequestType::where('is_active', true)->get();
        $requestStatuses = RequestStatus::where('is_active', true)->get();
        
        return view('requests.create', compact('centers', 'requestTypes', 'requestStatuses'));
    }

    /**
     * Store a newly created resource in storage.
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
            'status_id' => 'required|exists:request_statuses,id',
            'description' => 'required|string',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $validated['created_by'] = auth()->id();
        
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
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'created',
                'tracking_number' => $req->tracking_number,
                'requester_name' => $validated['name'],
            ])
            ->log('تم إنشاء طلب جديد: ' . $req->tracking_number);

        return redirect()->route('requests.index')
            ->with('success', 'تم إضافة الطلب بنجاح. رقم التتبع: ' . $req->tracking_number);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $req): View
    {
        $req->load(['center', 'requestType', 'status', 'creator']);
        return view('requests.show', compact('req'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $req): View
    {
        $centers = Center::where('is_active', true)->get();
        $requestTypes = RequestType::where('is_active', true)->get();
        $requestStatuses = RequestStatus::where('is_active', true)->get();
        
        return view('requests.edit', compact('req', 'centers', 'requestTypes', 'requestStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HttpRequest $request, Request $req): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:14',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'center_id' => 'required|exists:centers,id',
            'request_type_id' => 'required|exists:request_types,id',
            'status_id' => 'required|exists:request_statuses,id',
            'description' => 'required|string',
            'rejection_reason' => 'nullable|string',
            'response_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle response document upload
        if ($request->hasFile('response_document')) {
            // Delete old response document if exists
            if ($req->response_document) {
                Storage::disk('public')->delete($req->response_document);
            }
            $validated['response_document'] = $request->file('response_document')->store('requests/responses', 'public');
        }

        $req->update($validated);

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'updated',
                'tracking_number' => $req->tracking_number,
                'requester_name' => $validated['name'],
            ])
            ->log('تم تحديث الطلب: ' . $req->tracking_number);

        return redirect()->route('requests.index')
            ->with('success', 'تم تحديث الطلب بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $req): RedirectResponse
    {
        $trackingNumber = $req->tracking_number;
        
        // Delete associated documents
        if ($req->documents) {
            foreach ($req->documents as $document) {
                Storage::disk('public')->delete($document);
            }
        }
        
        if ($req->response_document) {
            Storage::disk('public')->delete($req->response_document);
        }
        
        $req->delete();

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'deleted',
                'tracking_number' => $trackingNumber,
            ])
            ->log('تم حذف الطلب: ' . $trackingNumber);

        return redirect()->route('requests.index')
            ->with('success', 'تم حذف الطلب بنجاح');
    }
}

