@props([
    'action' => '',
    'accept' => '.xlsx,.xls,.csv',
    'buttonText' => 'رفع ملف اكسيل',
    'buttonClass' => 'btn-primary',
    'modalTitle' => 'رفع ملف اكسيل',
    'maxFileSize' => '10MB',
    'columnMapping' => [],
    'sampleFile' => 'sample_1.xlsx'
])

<!-- Excel Upload Button -->
<button type="button" class="{{ $buttonClass }}" onclick="openExcelUploadModal()">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
    </svg>
    {{ $buttonText }}
</button>

<!-- Excel Upload Modal -->
<div id="excelUploadModal" class="custom-modal" style="display: none;">
    <div class="custom-modal-overlay" onclick="closeExcelUploadModal()"></div>
    <div class="custom-modal-content" dir="rtl" style="max-width: 600px;">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">{{ $modalTitle }}</h5>
            <button type="button" class="custom-modal-close" onclick="closeExcelUploadModal()">&times;</button>
        </div>
        <div class="custom-modal-body">
            <div class="space-y-4">
                <!-- File Upload Area -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors" 
                     id="dropZone" 
                     ondrop="handleDrop(event)" 
                     ondragover="handleDragOver(event)" 
                     ondragenter="handleDragEnter(event)" 
                     ondragleave="handleDragLeave(event)">
                    
                    <div id="dropZoneContent">
                        <svg class="mx-auto h-9 w-9 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="mt-4">
                            <label for="excelFile" class="cursor-pointer">
                                <span class="mt-2 block text-sm font-medium text-gray-900">
                                    اسحب ملف Excel هنا أو انقر للاختيار
                                </span>
                                <span class="mt-1 block text-xs text-gray-500">
                                    يدعم ملفات {{ $accept }} (حد أقصى {{ $maxFileSize }})
                                </span>
                            </label>
                            <input type="file" 
                                   id="excelFile" 
                                   name="excelFile" 
                                   accept="{{ $accept }}"
                                   class="hidden" 
                                   onchange="handleFileSelect(event)">
                        </div>
                    </div>
                    
                    <!-- Selected File Display -->
                    <div id="selectedFile" class="hidden mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                                <span id="fileName" class="text-sm font-medium text-blue-900"></span>
                                <span id="fileSize" class="text-xs text-blue-700 mr-2"></span>
                            </div>
                            <button type="button" onclick="removeSelectedFile()" class="text-blue-600 hover:text-blue-800">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div id="uploadProgress" class="hidden">
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                        <span>جاري الرفع...</span>
                        <span id="progressPercent">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Error Message -->
                <div id="uploadError" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span id="errorMessage" class="text-sm text-red-700"></span>
                    </div>
                </div>

                <!-- Success Message -->
                <div id="uploadSuccess" class="hidden p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span id="successMessage" class="text-sm text-green-700"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="custom-btn custom-btn-secondary" onclick="closeExcelUploadModal()">إلغاء</button>
            <button type="button" class="custom-btn custom-btn-info" onclick="downloadSampleFile()">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                تحميل نموذج
            </button>
            <button type="button" id="uploadBtn" class="custom-btn custom-btn-primary" onclick="uploadExcelFile()" disabled>
                <span id="uploadBtnText">رفع الملف</span>
                <svg id="uploadSpinner" class="hidden animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
let selectedFile = null;
const uploadAction = '{{ $action }}';
const columnMapping = @json($columnMapping);
const sampleFile = '{{ $sampleFile }}';

function openExcelUploadModal() {
    document.getElementById('excelUploadModal').style.display = 'flex';
    resetUploadState();
}

function closeExcelUploadModal() {
    document.getElementById('excelUploadModal').style.display = 'none';
    resetUploadState();
}

function resetUploadState() {
    selectedFile = null;
    document.getElementById('excelFile').value = '';
    document.getElementById('selectedFile').classList.add('hidden');
    document.getElementById('uploadProgress').classList.add('hidden');
    document.getElementById('uploadError').classList.add('hidden');
    document.getElementById('uploadSuccess').classList.add('hidden');
    document.getElementById('uploadBtn').disabled = true;
    document.getElementById('uploadBtnText').textContent = 'رفع الملف';
    document.getElementById('uploadSpinner').classList.add('hidden');
}

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        processSelectedFile(file);
    }
}

function handleDrop(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const dropZone = document.getElementById('dropZone');
    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        const file = files[0];
        processSelectedFile(file);
        // Update the file input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById('excelFile').files = dataTransfer.files;
    }
}

function handleDragOver(event) {
    event.preventDefault();
}

function handleDragEnter(event) {
    event.preventDefault();
    const dropZone = document.getElementById('dropZone');
    dropZone.classList.add('border-blue-400', 'bg-blue-50');
}

function handleDragLeave(event) {
    event.preventDefault();
    const dropZone = document.getElementById('dropZone');
    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
}

function processSelectedFile(file) {
    // Validate file type
    const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'];
    if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
        showError('نوع الملف غير مدعوم. يرجى اختيار ملف Excel أو CSV.');
        return;
    }

    // Validate file size (10MB max)
    const maxSize = 10 * 1024 * 1024;
    if (file.size > maxSize) {
        showError('حجم الملف كبير جداً. الحد الأقصى المسموح هو 10 ميجابايت.');
        return;
    }

    selectedFile = file;
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = formatFileSize(file.size);
    document.getElementById('selectedFile').classList.remove('hidden');
    document.getElementById('uploadBtn').disabled = false;
    hideError();
    hideSuccess();
}

function removeSelectedFile() {
    selectedFile = null;
    document.getElementById('excelFile').value = '';
    document.getElementById('selectedFile').classList.add('hidden');
    document.getElementById('uploadBtn').disabled = true;
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function uploadExcelFile() {
    if (!selectedFile) {
        showError('يرجى اختيار ملف للرفع.');
        return;
    }

    const formData = new FormData();
    formData.append('excel_file', selectedFile);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // Show progress
    document.getElementById('uploadProgress').classList.remove('hidden');
    document.getElementById('uploadBtn').disabled = true;
    document.getElementById('uploadBtnText').textContent = 'جاري الرفع...';
    document.getElementById('uploadSpinner').classList.remove('hidden');
    hideError();
    hideSuccess();

    // Add column mapping to form data
    if (Object.keys(columnMapping).length > 0) {
        formData.append('column_mapping', JSON.stringify(columnMapping));
    }

    // Upload file
    fetch(uploadAction, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideProgress();
        
        if (data.success) {
            showSuccess(data.message || 'تم رفع الملف بنجاح!');
            // Refresh the page or table after successful upload
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showError(data.message || 'حدث خطأ أثناء رفع الملف.');
        }
    })
    .catch(error => {
        hideProgress();
        console.error('Error:', error);
        showError('حدث خطأ أثناء رفع الملف. يرجى المحاولة مرة أخرى.');
    })
    .finally(() => {
        document.getElementById('uploadBtn').disabled = false;
        document.getElementById('uploadBtnText').textContent = 'رفع الملف';
        document.getElementById('uploadSpinner').classList.add('hidden');
    });
}

function showError(message) {
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('uploadError').classList.remove('hidden');
}

function hideError() {
    document.getElementById('uploadError').classList.add('hidden');
}

function showSuccess(message) {
    document.getElementById('successMessage').textContent = message;
    document.getElementById('uploadSuccess').classList.remove('hidden');
}

function hideSuccess() {
    document.getElementById('uploadSuccess').classList.add('hidden');
}

function hideProgress() {
    document.getElementById('uploadProgress').classList.add('hidden');
}

function downloadSampleFile() {
    // Create a temporary link element to trigger download
    const link = document.createElement('a');
    link.href = '/' + sampleFile;
    link.download = sampleFile;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<style>
.custom-btn-primary {
    background-color: #2563eb;
    color: white;
}

.custom-btn-primary:hover {
    background-color: #1d4ed8;
}

.custom-btn-primary:disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
}

.custom-btn-info {
    background-color: #0891b2;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.custom-btn-info:hover {
    background-color: #0e7490;
}
</style>
