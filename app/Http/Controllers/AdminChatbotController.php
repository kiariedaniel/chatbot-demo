<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminChatbotController extends Controller
{
    private function getData()
    {
        return json_decode(file_get_contents(resource_path('data/chatbot.json')), true);
    }

    private function saveData($data)
    {
        file_put_contents(resource_path('data/chatbot.json'), json_encode($data, JSON_PRETTY_PRINT));
    }

    public function index()
    {
        $data = $this->getData();
        return view('admin.index', compact('data'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $data = $this->getData();

        $data[$request->key] = [
            "message" => $request->message,
            "options" => json_decode($request->options, true)
        ];

        $this->saveData($data);

        return redirect('/admin/chatbot')->with('success', 'Chatbot step added!');
    }

    public function edit($key)
    {
        $data = $this->getData();
        return view('admin.edit', [
            "key" => $key,
            "step" => $data[$key]
        ]);
    }

    public function update(Request $request, $key)
    {
        $data = $this->getData();

        $data[$key] = [
            "message" => $request->message,
            "options" => json_decode($request->options, true)
        ];

        $this->saveData($data);

        return redirect('/admin/chatbot')->with('success', 'Step updated!');
    }

    public function delete($key)
    {
        $data = $this->getData();
        unset($data[$key]);

        $this->saveData($data);

        return redirect('/admin/chatbot')->with('success', 'Step deleted!');
    }
}
