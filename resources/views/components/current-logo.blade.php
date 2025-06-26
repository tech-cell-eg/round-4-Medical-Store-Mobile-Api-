@props(['url', 'filename', 'size' => null])

<div class="flex flex-col items-start space-y-2 rtl:space-x-reverse">
    @if($url)
        <div class="flex items-center justify-center rounded-lg overflow-hidden border border-gray-300 {{ $size ? 'w-'.$size.' h-'.$size : 'w-32 h-32' }}">
            <img src="{{ $url }}" alt="{{ $filename ?? 'الصورة الحالية' }}" class="object-cover w-full h-full">
        </div>
        @if($filename)
            <div class="text-sm text-gray-500">
                <span>{{ $filename }}</span>
            </div>
        @endif
    @else
        <div class="flex items-center justify-center rounded-lg bg-gray-100 {{ $size ? 'w-'.$size.' h-'.$size : 'w-32 h-32' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
    @endif
</div>
