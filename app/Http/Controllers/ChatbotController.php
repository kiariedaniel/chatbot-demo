<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function flow(Request $request)
{
    $step = $request->input('step', 'start');

    // If user typed a sentence instead of a step
    if (!ctype_alpha($step)) {
        return $this->freeTextReply($step);
    }

    // Load flow JSON
    $data = json_decode(file_get_contents(resource_path('data/chatbot.json')), true);

    if (!isset($data[$step])) {
        return $this->freeTextReply($step);
    }

    return response()->json($data[$step]);
}

public function freeTextReply($question)
{
    $question = strtolower($question);

    $qa = json_decode(file_get_contents(resource_path('data/questions.json')), true);

    // 1️⃣ Exact match first
    foreach ($qa as $q => $answer) {
        if (strpos($question, $q) !== false) {
            return $this->formatAnswer($answer);
        }
    }

    // 2️⃣ Fuzzy match (similar_text)
    $bestMatch = null;
    $highestScore = 0;

    foreach ($qa as $q => $answer) {
        similar_text($question, $q, $percent);

        if ($percent > $highestScore) {
            $highestScore = $percent;
            $bestMatch = $answer;
        }
    }

    // If fuzzy score above threshold (40% is good)
    if ($highestScore > 40) {
        return $this->formatAnswer($bestMatch);
    }

    // 3️⃣ AI-style fallback (closest keyword detection)
    foreach ($qa as $q => $answer) {
        $words = explode(" ", $q);
        foreach ($words as $word) {
            if (strlen($word) > 3 && strpos($question, $word) !== false) {
                return $this->formatAnswer($answer);
            }
        }
    }

    // 4️⃣ Nothing matched → fallback
    return response()->json([
        "message" => "I’m not fully sure about that, but try choosing a category below:",
        "options" => [
            ["label" => "Login Issues", "goto" => "login_category"],
            ["label" => "PCN Help", "goto" => "pcn_category"],
            ["label" => "Feasibility", "goto" => "feasibility_category"],
            ["label" => "Dashboard Help", "goto" => "dashboard_category"],
            ["label" => "RBAC Access", "goto" => "rbac_category"]
        ]
    ]);
}

private function formatAnswer($answer)
{
    // If answer contains "||", split to multiple bubbles
    if (strpos($answer, "||") !== false) {
        $parts = explode("||", $answer);

        $messages = [];
        foreach ($parts as $part) {
            $messages[] = [
                "message" => trim($part),
                "options" => []
            ];
        }

        return response()->json([ "multi" => $messages ]);
    }

    // Single bubble
    return response()->json([
        "message" => $answer,
        "options" => [
            ["label" => "Back to Menu", "goto" => "start"]
        ]
    ]);
}

}