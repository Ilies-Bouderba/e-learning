<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIGradingService
{
    protected $apiKey;
    protected $apiUrl = 'https://openrouter.ai/api/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_KEY');
    }

    public function gradeAnswer($questionText, $studentAnswer, $maxPoints)
    {
        if (empty($this->apiKey) || empty(trim($studentAnswer))) {
            return $this->manualFallbackGrading($studentAnswer, $maxPoints);
        }

        $prompt = "Question: {$questionText}\n\nStudent's Answer: {$studentAnswer}\n\nGrade this answer from 0 to {$maxPoints} points. Consider accuracy, completeness, understanding of the topic, and clarity. Return ONLY a JSON object with: {\"score\": number, \"feedback\": \"string\", \"reason\": \"string\", \"correct_answer\": \"string\"}";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => 'openai/gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert exam grader. Grade student answers fairly and provide constructive feedback. Always include the correct answer in your response.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];
                preg_match('/\{.*\}/s', $content, $matches);
                if (isset($matches[0])) {
                    $result = json_decode($matches[0], true);
                    return [
                        'score' => min(max($result['score'] ?? 0, 0), $maxPoints),
                        'feedback' => $result['feedback'] ?? 'No feedback provided.',
                        'reason' => $result['reason'] ?? '',
                        'correct_answer' => $result['correct_answer'] ?? 'Not provided',
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('AI Grading Error: ' . $e->getMessage());
        }

        return $this->manualFallbackGrading($studentAnswer, $maxPoints);
    }

    protected function manualFallbackGrading($studentAnswer, $maxPoints)
    {
        // For completely wrong answers, give 0 points
        $score = !empty(trim($studentAnswer)) ? 0 : 0;
        return [
            'score' => $score,
            'feedback' => $score > 0 ? 'Answer submitted.' : 'Incorrect answer.',
            'reason' => 'Manual fallback grading.',
            'correct_answer' => 'Not available',
        ];
    }
}
