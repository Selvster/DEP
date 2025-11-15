<?php

namespace App\Http\Controllers;

use App\Models\RequestType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class RequestTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $requestTypes = RequestType::with(['creator'])
                ->select(['id', 'name', 'is_active', 'created_at', 'created_by'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($requestTypes)
                ->addColumn('creator_name', function ($requestType) {
                    return $requestType->creator ? $requestType->creator->name : 'غير محدد';
                })
                ->addColumn('is_active_formatted', function ($requestType) {
                    return $requestType->is_active 
                        ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>'
                        : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">غير نشط</span>';
                })
                ->addColumn('created_at_formatted', function ($requestType) {
                    return $requestType->created_at->format('Y-m-d H:i');
                })
                ->addColumn('actions', function ($requestType) {
                    return view('request-types.partials.actions', compact('requestType'))->render();
                })
                ->rawColumns(['is_active_formatted', 'actions'])
                ->make(true);
        }

        return view('request-types.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('request-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');

        RequestType::create($validated);

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'created',
                'request_type_name' => $validated['name'],
            ])
            ->log('تم إنشاء نوع طلب جديد: ' . $validated['name']);

        return redirect()->route('request-types.index')
            ->with('success', 'تم إضافة نوع الطلب بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestType $requestType): View
    {
        $requestType->load(['creator', 'requests']);
        return view('request-types.show', compact('requestType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestType $requestType): View
    {
        return view('request-types.edit', compact('requestType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestType $requestType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $requestType->update($validated);

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'updated',
                'request_type_name' => $validated['name'],
            ])
            ->log('تم تحديث نوع الطلب: ' . $validated['name']);

        return redirect()->route('request-types.index')
            ->with('success', 'تم تحديث نوع الطلب بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestType $requestType): RedirectResponse
    {
        $requestTypeName = $requestType->name;
        $requestType->delete();

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'deleted',
                'request_type_name' => $requestTypeName,
            ])
            ->log('تم حذف نوع الطلب: ' . $requestTypeName);

        return redirect()->route('request-types.index')
            ->with('success', 'تم حذف نوع الطلب بنجاح');
    }
}

