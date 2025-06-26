@props([
    'name',
    'label',
    'accept' => 'image/*',
    'maxSize' => 2048, // بالكيلوبايت
    'required' => false,
    'helpText' => null,
    'formId' => null,
])

<div class="filament-forms-field-wrapper">
    <div class="space-y-2">
        <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
            <label for="{{ $name }}" class="filament-forms-field-wrapper-label inline-flex items-center space-x-1 rtl:space-x-reverse">
                <span class="text-sm font-medium leading-4 text-gray-700">{{ $label }}</span>
                @if($required)
                    <span class="text-danger-500">*</span>
                @endif
            </label>
        </div>

        <div class="filament-forms-file-upload-component">
            <div class="flex items-center justify-center w-full">
                <label for="{{ $name }}" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">اضغط للرفع</span> أو اسحب وأفلت</p>
                        <p class="text-xs text-gray-500">{{ $accept == 'image/*' ? 'صور' : $accept }} (الحد الأقصى: {{ $maxSize }} كيلوبايت)</p>
                    </div>
                    <input 
                        id="{{ $name }}" 
                        name="{{ $name }}" 
                        type="file" 
                        class="hidden" 
                        accept="{{ $accept }}"
                        {{ $required ? 'required' : '' }}
                        onchange="validateFile(this, {{ $maxSize }})"
                    />
                </label>
            </div>
            @if($helpText)
                <div class="text-xs text-gray-500 mt-2">{{ $helpText }}</div>
            @endif
        </div>
    </div>
</div>

<script>
    function validateFile(input, maxSize) {
        const file = input.files[0];
        if (!file) return;
        
        // التحقق من حجم الملف
        const fileSize = Math.round(file.size / 1024); // تحويل إلى كيلوبايت
        if (fileSize > maxSize) {
            alert(`حجم الملف كبير جداً (${fileSize} كيلوبايت). الحد الأقصى المسموح به هو ${maxSize} كيلوبايت.`);
            input.value = '';
            return;
        }
        
        // عرض اسم الملف والصورة المصغرة
        const fileName = file.name;
        
        // إزالة أي عناصر سابقة
        const parent = input.parentElement.parentElement;
        const existingLabel = parent.querySelector('.text-sm.text-gray-700.mt-2');
        if (existingLabel) {
            existingLabel.remove();
        }
        const existingPreview = parent.querySelector('.file-preview');
        if (existingPreview) {
            existingPreview.remove();
        }
        
        // إنشاء عنصر معاينة الصورة
        const previewContainer = document.createElement('div');
        previewContainer.className = 'file-preview mt-3 flex flex-col items-center';
        
        // إضافة الصورة المصغرة
        const imgPreview = document.createElement('div');
        imgPreview.className = 'w-32 h-32 border border-gray-300 rounded-lg overflow-hidden';
        
        const img = document.createElement('img');
        img.className = 'w-full h-full object-cover';
        img.alt = 'معاينة الصورة';
        
        // قراءة الملف كـ URL للصورة
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        imgPreview.appendChild(img);
        previewContainer.appendChild(imgPreview);
        
        // إضافة اسم الملف
        const fileLabel = document.createElement('div');
        fileLabel.className = 'text-sm text-gray-700 mt-2';
        fileLabel.textContent = `تم اختيار: ${fileName}`;
        previewContainer.appendChild(fileLabel);
        
        parent.appendChild(previewContainer);
    }
</script>
