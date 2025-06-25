<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>تسجيل مدير جديد</title>
</head>

<body>
    <form method="POST" action="{{ url('admin/register') }}">
        @csrf
        <input type="text" name="name" placeholder="الاسم" required>
        <input type="email" name="email" placeholder="البريد الإلكتروني" required>
        <input type="password" name="password" placeholder="كلمة المرور" required>
        <input type="password" name="password_confirmation" placeholder="تأكيد كلمة المرور" required>
        <button type="submit">تسجيل</button>
    </form>
    @if ($errors->any())
    <div>{{ $errors->first() }}</div>
    @endif
    Have an account? <a href="{{ route('filament.admin.login') }}">Login</a>
</body>

</html>