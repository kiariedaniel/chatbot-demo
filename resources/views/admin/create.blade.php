<!DOCTYPE html>
<html>
<head>
    <title>Create Step</title>
</head>
<body>

<h2>Create Chatbot Step</h2>

<form method="POST" action="/admin/chatbot/store">
    @csrf

    <label>Step Key (e.g. about, start, help):</label><br>
    <input type="text" name="key" required><br><br>

    <label>Message:</label><br>
    <textarea name="message" required></textarea><br><br>

    <label>Options (JSON format):</label><br>
    <textarea name="options" required>
[
  {"label":"Back","goto":"start"}
]
    </textarea><br><br>

    <button type="submit">Save</button>
</form>

</body>
</html>
