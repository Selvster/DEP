<div class="action-buttons">
    <!-- Download Button -->
    <button onclick="downloadBackup('{{ $backup['filename'] }}')" 
            class="btn-action btn-view"
            title="تحميل">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        تحميل
    </button>
    
    <!-- Delete Button -->
    <form id="delete-form-{{ $backup['filename'] }}" action="{{ route('backups.destroy') }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="filename" value="{{ $backup['filename'] }}">
        <button type="button" class="btn-action btn-delete" onclick="showDeleteModal('{{ $backup['filename'] }}', '{{ $backup['filename'] }}', 'نسخة احتياطية')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            حذف
        </button>
    </form>
</div>
