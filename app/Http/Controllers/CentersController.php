<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class CentersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $centers = Center::with(['creator'])
                ->select(['id', 'name', 'is_active', 'created_at', 'created_by'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($centers)
                ->addColumn('creator_name', function ($center) {
                    return $center->creator ? $center->creator->name : 'غير محدد';
                })
                ->addColumn('is_active_formatted', function ($center) {
                    return $center->is_active 
                        ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>'
                        : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">غير نشط</span>';
                })
                ->addColumn('created_at_formatted', function ($center) {
                    return $center->created_at->format('Y-m-d H:i');
                })
                ->addColumn('actions', function ($center) {
                    return view('centers.partials.actions', compact('center'))->render();
                })
                ->rawColumns(['is_active_formatted', 'actions'])
                ->make(true);
        }

        return view('centers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('centers.create');
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

        Center::create($validated);

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'created',
                'center_name' => $validated['name'],
            ])
            ->log('تم إنشاء مركز جديد: ' . $validated['name']);

        return redirect()->route('centers.index')
            ->with('success', 'تم إضافة المركز بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Center $center): View
    {
        $center->load(['creator', 'requests']);
        return view('centers.show', compact('center'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Center $center): View
    {
        return view('centers.edit', compact('center'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Center $center): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $center->update($validated);

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'updated',
                'center_name' => $validated['name'],
            ])
            ->log('تم تحديث المركز: ' . $validated['name']);

        return redirect()->route('centers.index')
            ->with('success', 'تم تحديث المركز بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Center $center): RedirectResponse
    {
        $centerName = $center->name;
        $center->delete();

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'deleted',
                'center_name' => $centerName,
            ])
            ->log('تم حذف المركز: ' . $centerName);

        return redirect()->route('centers.index')
            ->with('success', 'تم حذف المركز بنجاح');
    }
}

