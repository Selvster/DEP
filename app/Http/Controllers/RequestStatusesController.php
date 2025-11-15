<?php

namespace App\Http\Controllers;

use App\Models\RequestStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class RequestStatusesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $requestStatuses = RequestStatus::with(['creator'])
                ->select(['id', 'name', 'color', 'is_active', 'created_at', 'created_by'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($requestStatuses)
                ->addColumn('creator_name', function ($requestStatus) {
                    return $requestStatus->creator ? $requestStatus->creator->name : 'غير محدد';
                })
                ->addColumn('color_display', function ($requestStatus) {
                    return '<div class="flex items-center"><div class="w-6 h-6 rounded border border-gray-300" style="background-color: ' . $requestStatus->color . '"></div><span class="mr-2">' . $requestStatus->color . '</span></div>';
                })
                ->addColumn('is_active_formatted', function ($requestStatus) {
                    return $requestStatus->is_active 
                        ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>'
                        : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">غير نشط</span>';
                })
                ->addColumn('created_at_formatted', function ($requestStatus) {
                    return $requestStatus->created_at->format('Y-m-d H:i');
                })
                ->addColumn('actions', function ($requestStatus) {
                    return view('request-statuses.partials.actions', compact('requestStatus'))->render();
                })
                ->rawColumns(['color_display', 'is_active_formatted', 'actions'])
                ->make(true);
        }

        return view('request-statuses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('request-statuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');

        RequestStatus::create($validated);

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'created',
                'status_name' => $validated['name'],
            ])
            ->log('تم إنشاء حالة طلب جديدة: ' . $validated['name']);

        return redirect()->route('request-statuses.index')
            ->with('success', 'تم إضافة حالة الطلب بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestStatus $requestStatus): View
    {
        $requestStatus->load(['creator', 'requests']);
        return view('request-statuses.show', compact('requestStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestStatus $requestStatus): View
    {
        return view('request-statuses.edit', compact('requestStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestStatus $requestStatus): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $requestStatus->update($validated);

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'updated',
                'status_name' => $validated['name'],
            ])
            ->log('تم تحديث حالة الطلب: ' . $validated['name']);

        return redirect()->route('request-statuses.index')
            ->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestStatus $requestStatus): RedirectResponse
    {
        $statusName = $requestStatus->name;
        $requestStatus->delete();

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'deleted',
                'status_name' => $statusName,
            ])
            ->log('تم حذف حالة الطلب: ' . $statusName);

        return redirect()->route('request-statuses.index')
            ->with('success', 'تم حذف حالة الطلب بنجاح');
    }
}

