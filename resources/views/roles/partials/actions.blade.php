<div class="custom-dropdown-wrapper" x-data="{ open: false }" style="position: relative; display: inline-block;">
    <button @click="open = !open" @click.away="open = false" type="button" class="custom-dropdown-toggle">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
        </svg>
    </button>
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="custom-dropdown-menu"
         style="display: none;">
        @role('super_admin')
        <a href="{{ route('roles.show', $role) }}" class="custom-dropdown-item">
            <svg class="w-4 h-4 ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            عرض
        </a>
        @endrole
        
        @role('super_admin')
        <a href="{{ route('roles.edit', $role) }}" class="custom-dropdown-item">
            <svg class="w-4 h-4 ml-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            تعديل
        </a>
        @endrole

        @role('super_admin')
        <div class="custom-dropdown-divider"></div>
			@if($role->name !== 'super_admin')
				<form id="delete-form-{{ $role->id }}" action="{{ route('roles.destroy', $role) }}" method="POST" style="margin: 0;">
					@csrf
					@method('DELETE')
					<button type="button" onclick="showDeleteModal('{{ $role->id }}', '{{ $role->name }}', 'دور')" class="custom-dropdown-item text-red-600">
						<svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
						</svg>
						حذف
					</button>
				</form>
			@else
				<span class="btn-action btn-disabled cursor-not-allowed">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
					</svg>
					محمي
				</span>
			@endif
        @endrole
    </div>
</div>

<style>
.custom-dropdown-toggle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.5;
    color: #6c757d;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
}

.custom-dropdown-toggle:hover {
    color: #495057;
    background-color: #f8f9fa;
    border-color: #dae0e5;
}

.custom-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    min-width: 12rem;
    padding: 0.5rem 0;
    margin: 0.125rem 0 0;
    font-size: 0.875rem;
    color: #212529;
    text-align: right;
    direction: rtl;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
}

.custom-dropdown-item {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 0.5rem 1rem;
    clear: both;
    font-weight: 400;
    color: #212529;
    text-align: right;
    text-decoration: none;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.custom-dropdown-item:hover {
    color: #1e2125;
    background-color: #f8f9fa;
}

.custom-dropdown-item.text-red-600 {
    color: #dc3545;
}

.custom-dropdown-item.text-red-600:hover {
    color: #bb2d3b;
    background-color: #f8d7da;
}

.custom-dropdown-divider {
    height: 0;
    margin: 0.5rem 0;
    overflow: hidden;
    border-top: 1px solid #dee2e6;
}
</style>