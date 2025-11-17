<!DOCTYPE html>
<html>
<head>
    <title>Edit Step</title>
</head>
<body>

<h2>Edit Step: {{ $key }}</h2>

<form method="POST" action="/admin/chatbot/update/{{ $key }}">
    @csrf

    <label>Message:</label><br>
    <textarea name="message" required>{{ $step['message'] }}</textarea><br><br>

    <label>Options (JSON):</label><br>
    <textarea name="options" required>{{ json_encode($step['options'], JSON_PRETTY_PRINT) }}</textarea><br><br>

    <button type="submit">Update</button>
</form>

</body>
</html>
