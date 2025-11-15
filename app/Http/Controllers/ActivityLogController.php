<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    /**
     * Display the activity log.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $activities = Activity::with(['causer', 'subject'])
                ->select(['id', 'log_name', 'description', 'subject_type', 'subject_id', 'causer_type', 'causer_id', 'properties', 'created_at'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($activities)
                ->addColumn('datetime', function ($activity) {
                    return '<div class="text-sm text-gray-900">' . $activity->created_at->format('Y-m-d') . '</div>' .
                           '<div class="text-xs text-gray-500">' . $activity->created_at->format('H:i:s') . '</div>';
                })
                ->addColumn('user', function ($activity) {
                    if ($activity->causer) {
                        return '<div class="text-sm text-gray-900">' . $activity->causer->name . '</div>' .
                               '<div class="text-xs text-gray-500">' . $activity->causer->email . '</div>';
                    }
                    return '<div class="text-sm text-gray-900">نظام</div>';
                })
                ->addColumn('action', function ($activity) {
                    $properties = $activity->properties ?? [];
                    $action = $properties['action'] ?? 'unknown';
                    $actionLabels = [
                        'created' => 'إنشاء',
                        'updated' => 'تحديث',
                        'deleted' => 'حذف',
                        'login' => 'تسجيل دخول',
                        'image_uploaded' => 'رفع صورة',
                        'image_removed' => 'حذف صورة',
                        'family_member_created' => 'إضافة فرد أسرة',
                        'family_member_updated' => 'تحديث فرد أسرة',
                        'family_member_deleted' => 'حذف فرد أسرة',
                        'job_created' => 'إضافة وظيفة',
                        'job_updated' => 'تحديث وظيفة',
                        'job_deleted' => 'حذف وظيفة',
                        'address_created' => 'إضافة عنوان',
                        'address_updated' => 'تحديث عنوان',
                        'address_deleted' => 'حذف عنوان',
                        'note_created' => 'إضافة ملاحظة',
                        'note_updated' => 'تحديث ملاحظة',
                        'note_deleted' => 'حذف ملاحظة',
                        'deleted_all_logs' => 'حذف جميع السجلات',
                        'excel_uploaded' => 'رفع ملف Excel'
                    ];
                    
                    $badgeClass = '';
                    $icon = '';
                    
                    if (in_array($action, ['created', 'family_member_created', 'job_created', 'address_created', 'note_created'])) {
                        $badgeClass = 'bg-gradient-to-r from-green-50 to-green-100 text-green-800 border-green-200';
                        $icon = '<svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path></svg>';
                    } elseif (in_array($action, ['updated', 'family_member_updated', 'job_updated', 'address_updated', 'note_updated'])) {
                        $badgeClass = 'bg-gradient-to-r from-amber-50 to-amber-100 text-amber-800 border-amber-200';
                        $icon = '<svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>';
                    } elseif (in_array($action, ['deleted', 'family_member_deleted', 'job_deleted', 'address_deleted', 'note_deleted', 'deleted_all_logs'])) {
                        $badgeClass = 'bg-gradient-to-r from-red-50 to-red-100 text-red-800 border-red-200';
                        $icon = '<svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                    } elseif ($action === 'login') {
                        $badgeClass = 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 border-blue-200';
                        $icon = '<svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                    } elseif (in_array($action, ['image_uploaded', 'image_removed', 'excel_uploaded'])) {
                        $badgeClass = 'bg-gradient-to-r from-purple-50 to-purple-100 text-purple-800 border-purple-200';
                        $icon = '<svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>';
                    } else {
                        $badgeClass = 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-800 border-gray-200';
                    }
                    
                    return '<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold shadow-sm border ' . $badgeClass . '">' . $icon . ($actionLabels[$action] ?? $action) . '</span>';
                })
                ->addColumn('description', function ($activity) {
                    $properties = $activity->properties ?? [];
                    $description = '<div class="text-sm text-gray-900">' . $activity->description . '</div>';
                    
                    if (!empty($properties)) {
                        $details = '<div class="text-xs text-gray-500 mt-1">';
                        if (isset($properties['ip_address'])) {
                            $details .= 'IP: ' . $properties['ip_address'];
                        }
                        $details .= '</div>';
                        
                        $description .= $details;
                        $propertiesJson = htmlspecialchars(json_encode($properties), ENT_QUOTES, 'UTF-8');
                        $description .= '<div class="mt-2"><button type="button" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200 transition-colors" data-details-modal data-activity-id="' . $activity->id . '" data-properties=\'' . $propertiesJson . '\'>تفاصيل إضافية</button></div>';
                    }
                    
                    return $description;
                })
                ->addColumn('subject', function ($activity) {
                    $subjectType = class_basename($activity->subject_type);
                    $properties = $activity->properties ?? [];
                    
                    $subjectLabels = [
                        'User' => 'مستخدم',
                        'Role' => 'دور',
                        'Permission' => 'صلاحية',
                        'Request' => 'طلب',
                        'Center' => 'مركز',
                        'RequestType' => 'نوع طلب',
                        'RequestStatus' => 'حالة طلب',
                    ];
                    
                    if ($activity->subject || $activity->subject_type) {
                        $typeLabel = $subjectLabels[$subjectType] ?? $subjectType;
                        return '<div class="text-sm text-gray-900">' . $typeLabel . '</div>';
                    }
                    
                    return '<span class="text-gray-400">-</span>';
                })
                ->rawColumns(['datetime', 'user', 'action', 'description', 'subject'])
                ->make(true);
        }

        return view('activity-log.index');
    }


    /**
     * Delete all activity logs
     */
    public function deleteAll(): RedirectResponse
    {
        // Get count before deletion for logging
        $count = \Spatie\Activitylog\Models\Activity::count();
        
        // Delete all activity logs
        \Spatie\Activitylog\Models\Activity::truncate();

        // Log the deletion activity
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'deleted_all_logs',
                'deleted_count' => $count,
            ])
            ->log('تم حذف جميع سجلات النشاط (' . $count . ' سجل)');

        return redirect()->route('activity-log.index')
            ->with('success', 'تم حذف جميع سجلات النشاط بنجاح (' . $count . ' سجل)');
    }
}
