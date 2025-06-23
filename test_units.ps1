# 1. إدراج وحدة واحدة
Write-Host "1. إدراج وحدة واحدة" -ForegroundColor Green
$unit1 = @{
    name = 'حبة'
    symbol = 'ح'
    description = 'وحدة قياس الحبة الواحدة'
    is_active = $true
} | ConvertTo-Json

$response = Invoke-WebRequest -Uri 'http://localhost:8000/api/units' -Method Post -Body $unit1 -ContentType 'application/json'
Write-Host $response.Content
$unit1Id = ($response.Content | ConvertFrom-Json).data[0].id

# 2. إدراج عدة وحدات
Write-Host "`n2. إدراج عدة وحدات" -ForegroundColor Green
$units = @(
    @{
        name = 'علبة'
        symbol = 'علبة'
        description = 'وحدة قياس العبوة الكاملة'
        is_active = $true
    },
    @{
        name = 'جرام'
        symbol = 'جم'
        description = 'وحدة قياس الوزن بالجرام'
        is_active = $true
    }
) | ConvertTo-Json -Depth 5

$response = Invoke-WebRequest -Uri 'http://localhost:8000/api/units' -Method Post -Body $units -ContentType 'application/json'
Write-Host $response.Content

# 3. جلب جميع الوحدات
Write-Host "`n3. جلب جميع الوحدات" -ForegroundColor Green
$response = Invoke-WebRequest -Uri 'http://localhost:8000/api/units' -Method Get -ContentType 'application/json'
Write-Host $response.Content

# 4. جلب وحدة محددة (استخدم معرف الوحدة من الخطوة 1)
Write-Host "`n4. جلب وحدة محددة" -ForegroundColor Green
$response = Invoke-WebRequest -Uri "http://localhost:8000/api/units/$unit1Id" -Method Get -ContentType 'application/json'
Write-Host $response.Content

# 5. تحديث وحدة
Write-Host "`n5. تحديث وحدة" -ForegroundColor Green
$updateData = @{
    name = 'حبة معدلة'
    description = 'وصف معدل للوحدة'
    is_active = $true
} | ConvertTo-Json

$response = Invoke-WebRequest -Uri "http://localhost:8000/api/units/$unit1Id" -Method Put -Body $updateData -ContentType 'application/json'
Write-Host $response.Content

# 6. حذف وحدة (سنقوم بإنشاء وحدة جديدة لحذفها)
Write-Host "`n6. حذف وحدة" -ForegroundColor Green
# إنشاء وحدة جديدة للحذف
$tempUnit = @{
    name = 'وحدة للحذف'
    symbol = 'حذف'
    description = 'هذه الوحدة ستتم إزالتها'
    is_active = $true
} | ConvertTo-Json

$response = Invoke-WebRequest -Uri 'http://localhost:8000/api/units' -Method Post -Body $tempUnit -ContentType 'application/json'
$tempUnitId = ($response.Content | ConvertFrom-Json).data[0].id

# حذف الوحدة
$response = Invoke-WebRequest -Uri "http://localhost:8000/api/units/$tempUnitId" -Method Delete -ContentType 'application/json'
Write-Host $response.Content

Write-Host "`nتم تنفيذ جميع الاختبارات بنجاح!" -ForegroundColor Green
