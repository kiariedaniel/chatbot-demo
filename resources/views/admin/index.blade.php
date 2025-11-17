<!DOCTYPE html>
<html>
<head>
    <title>Chatbot Admin</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; }
        a.button {
            background: #128c7e;
            padding: 8px 14px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h2>Chatbot Steps</h2>

<a class="button" href="/admin/chatbot/create">+ New Step</a>

<table>
    <tr>
        <th>Step Key</th>
        <th>Message</th>
        <th>Options</th>
        <th>Actions</th>
    </tr>

    @foreach($data as $key => $step)
    <tr>
        <td>{{ $key }}</td>
        <td>{{ $step['message'] }}</td>
        <td>
            @foreach($step['options'] as $opt)
                {{ $opt['label'] }} → {{ $opt['goto'] }}<br>
            @endforeach
        </td>
        <td>
            <a href="/admin/chatbot/edit/{{ $key }}">Edit</a> |
            <a href="/admin/chatbot/delete/{{ $key }}" style="color:red;"
               onclick="return confirm('Delete step?');">Delete</a>
        </td>
    </tr>
    @endforeach
</table>

</body>
</html>
