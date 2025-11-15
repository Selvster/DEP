# Resource Structure Reference

This document outlines the structure that was used for the deleted resources (Individuals, Companies, Delivery Companies). Use this as a reference when building new resources with the same structure.

## File Structure for a Resource (Example: "ResourceName")

### 1. **Models** (`app/Models/`)
- `ResourceName.php` - Main model
- Related models (e.g., `ResourceNameCategory.php`, `ResourceNameDetail.php`)

**Key Model Features:**
- Uses `SoftDeletes` trait for soft deletion
- Uses `LogsActivity` trait from Spatie for activity logging
- Includes relationships (belongsTo, hasMany, etc.)
- Defines fillable fields
- Casts for data types (dates, decimals, etc.)
- `creator()` relationship to User model via `created_by` field

### 2. **Controllers** (`app/Http/Controllers/`)
- `ResourceNameController.php`
- Related controllers (e.g., `ResourceNameCategoriesController.php`)

**Key Controller Features:**
- Index method with DataTables support (AJAX)
- CRUD operations (index, create, store, show, edit, update, destroy)
- Excel upload functionality
- Image upload/removal (if applicable)
- Related data management (child records, notes, etc.)
- Returns JSON responses for AJAX requests
- Returns Blade views for regular requests

### 3. **Migrations** (`database/migrations/`)
- `YYYY_MM_DD_HHMMSS_create_resource_names_table.php`
- Additional migrations for related tables
- Migrations for column additions/modifications

**Key Migration Features:**
- Primary key `id()`
- Foreign key `created_by` linked to users table with `onDelete('set null')`
- Timestamps (`created_at`, `updated_at`)
- Soft deletes (`softDeletes()`)
- Appropriate data types (string, text, date, decimal, integer)
- Indexes for foreign keys

### 4. **Views** (`resources/views/`)

#### Main Resource Views (`resources/views/resource-names/`)
- `index.blade.php` - List view with DataTables
- `create.blade.php` - Creation form
- `edit.blade.php` - Edit form
- `show.blade.php` - Detail view with tabs
- `partials/actions.blade.php` - Action buttons for DataTables

#### Components (`resources/views/components/resource-names/`)
For complex resources with child records:
- `tabs-navigation.blade.php` - Navigation between tabs
- `overview-tab.blade.php` - Main information tab
- `*-tab.blade.php` - Various tabs for related data
- `add-*-modal.blade.php` - Modals for adding child records
- `edit-*-modal.blade.php` - Modals for editing child records
- `delete-*-modal.blade.php` - Modals for confirming deletion
- `*-scripts.blade.php` - JavaScript for handling AJAX operations

### 5. **Routes** (`routes/web.php`)

**Route Structure:**
```php
// Permission-based Routes for ResourceName Management
Route::middleware(['permission:create_resource|edit_resource|delete_resource'])->group(function () {
    // Main resource routes
    Route::get('/resource-names', [ResourceNameController::class, 'index'])->name('resource-names.index')->middleware('permission:create_resource|edit_resource|delete_resource');
    Route::get('/resource-names/create', [ResourceNameController::class, 'create'])->name('resource-names.create')->middleware('permission:create_resource');
    Route::post('/resource-names', [ResourceNameController::class, 'store'])->name('resource-names.store')->middleware('permission:create_resource');
    Route::get('/resource-names/{resourceName}', [ResourceNameController::class, 'show'])->name('resource-names.show')->middleware('permission:create_resource|edit_resource|delete_resource');
    Route::get('/resource-names/{resourceName}/edit', [ResourceNameController::class, 'edit'])->name('resource-names.edit')->middleware('permission:edit_resource');
    Route::put('/resource-names/{resourceName}', [ResourceNameController::class, 'update'])->name('resource-names.update')->middleware('permission:edit_resource');
    Route::delete('/resource-names/{resourceName}', [ResourceNameController::class, 'destroy'])->name('resource-names.destroy')->middleware('permission:delete_resource');
    
    // Excel upload
    Route::post('/resource-names/upload-excel', [ResourceNameController::class, 'uploadExcel'])->name('resource-names.upload-excel')->middleware('permission:create_resource');
    
    // Child resource routes (if applicable)
    Route::post('/resource-names/{resourceName}/child-items', [ResourceNameController::class, 'storeChildItem'])->name('resource-names.child-items.store')->middleware('permission:edit_resource');
    Route::put('/resource-names/{resourceName}/child-items/{childItem}', [ResourceNameController::class, 'updateChildItem'])->name('resource-names.child-items.update')->middleware('permission:edit_resource');
    Route::delete('/resource-names/{resourceName}/child-items/{childItem}', [ResourceNameController::class, 'destroyChildItem'])->name('resource-names.child-items.destroy')->middleware('permission:edit_resource');
});
```

**Controller Import:**
```php
use App\Http\Controllers\ResourceNameController;
```

### 6. **Navigation** (`resources/views/layouts/sidebar.blade.php`)

**Sidebar Link Structure:**
```php
@canany(['create_resource', 'edit_resource', 'delete_resource'])
<div class="space-y-1">
    <a href="{{ route('resource-names.index') }}" 
       class="{{ request()->routeIs('resource-names.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200">
        <svg class="{{ request()->routeIs('resource-names.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }} ml-3 flex-shrink-0 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <!-- SVG path here -->
        </svg>
        Resource Name Label
    </a>
</div>
@endcanany
```

### 7. **Permissions & Roles**
Each resource typically has 3 permissions:
- `create_resource` - Permission to create new records
- `edit_resource` - Permission to edit existing records
- `delete_resource` - Permission to delete records

**Setup in AdminSeeder:**
1. Add permissions to the `$permissions` array
2. They will be auto-created and assigned to super_admin role
3. Can be assigned to other roles via the roles management interface

### 8. **Seeders** (`database/seeders/`)
- `ResourceNameSeeder.php` - Seeds initial data for the resource
- Update `DatabaseSeeder.php` to call your seeder
- Update `AdminSeeder.php` to include resource permissions

**Key Seeder Features:**
```php
class ResourceNameSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample data
        ResourceName::create([
            'field' => 'value',
            'created_by' => 1, // Admin user ID
        ]);
    }
}
```

**Permissions in AdminSeeder:**
```php
$permissions = [
    'create_resource',
    'edit_resource',
    'delete_resource',
];
```

### 9. **Activity Logging**
- Models implement `LogsActivity` trait from Spatie
- Activities are logged with custom properties:
  - `action` (created, updated, deleted, etc.)
  - Additional context (IP address, user details, etc.)
- Activity log controller displays activities with proper translations

## Key Technologies Used

1. **Laravel Framework** - Main PHP framework
2. **Yajra DataTables** - For AJAX-powered data tables
3. **Spatie Activity Log** - For logging user activities
4. **Spatie Permission** - For role-based access control
5. **Maatwebsite Excel** - For Excel import functionality
6. **TailwindCSS** - For UI styling
7. **Alpine.js** - For interactive components

## Naming Conventions

1. **Routes**: kebab-case (e.g., `resource-names`)
2. **Controllers**: PascalCase + "Controller" suffix (e.g., `ResourceNameController`)
3. **Models**: PascalCase, singular (e.g., `ResourceName`)
4. **Database Tables**: snake_case, plural (e.g., `resource_names`)
5. **Permissions**: snake_case with underscore (e.g., `create_resource`)

## Common Patterns

### DataTables Pattern
```php
if ($request->ajax()) {
    $data = Model::with(['relations'])
        ->select(['columns'])
        ->orderBy('created_at', 'desc');

    return DataTables::of($data)
        ->addColumn('formatted_column', function ($item) {
            return formatted_value;
        })
        ->addColumn('actions', function ($item) {
            return view('resource.partials.actions', compact('item'))->render();
        })
        ->rawColumns(['actions'])
        ->make(true);
}
```

### Excel Upload Pattern
```php
public function uploadExcel(Request $request)
{
    $request->validate([
        'excel_file' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new ResourceImport, $request->file('excel_file'));
    
    return redirect()->route('resource.index')
        ->with('success', 'تم رفع ملف Excel بنجاح');
}
```

### Soft Delete Pattern
```php
// In Model
use SoftDeletes;

protected $fillable = [...];

// In Migration
$table->softDeletes();

// In Controller (destroy method)
$resource->delete(); // Soft delete
```

## Files That Were Deleted

### Controllers (4 files)
- `app/Http/Controllers/IndividualsController.php`
- `app/Http/Controllers/IndividualsCategoriesController.php`
- `app/Http/Controllers/CompaniesController.php`
- `app/Http/Controllers/DeliveryCompaniesController.php`

### Models (8 files)
- `app/Models/Individual.php`
- `app/Models/IndividualJob.php`
- `app/Models/IndividualAddress.php`
- `app/Models/IndividualFamilyMember.php`
- `app/Models/IndividualsCategory.php`
- `app/Models/IndividualNote.php`
- `app/Models/Company.php`
- `app/Models/DeliveryCompany.php`

### Migrations (13 files)
- All migrations related to individuals, companies, and delivery companies tables

### Views (21+ files across 4 directories)
- `resources/views/individuals/` (entire directory)
- `resources/views/companies/` (entire directory)
- `resources/views/delivery-companies/` (entire directory)
- `resources/views/individuals-categories/` (entire directory)
- `resources/views/components/individuals/` (entire directory)
- `resources/views/activity-log/individual.blade.php`

### Seeders (3 files)
- `database/seeders/IndividualSeeder.php`
- `database/seeders/IndividualsCategorySeeder.php`
- `database/seeders/DeliveryCompanySeeder.php`

### Routes & References Cleaned
- `routes/web.php` - Removed all routes and imports for deleted resources
- `app/Http/Controllers/ActivityLogController.php` - Removed Individual references
- `app/Http/Controllers/DashboardController.php` - Removed Individual/Category references
- `resources/views/layouts/sidebar.blade.php` - Removed navigation links
- `resources/views/dashboard.blade.php` - Removed totalCitizens card
- `database/seeders/AdminSeeder.php` - Removed permissions for deleted resources
- `database/seeders/DatabaseSeeder.php` - Removed seeder calls for deleted resources

---

**Note:** Use this structure as a template when creating new resources. Ensure consistent naming conventions and follow Laravel best practices.

