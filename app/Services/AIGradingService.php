<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIGradingService
{
    private const API_URL = 'https://openrouter.ai/api/v1/chat/completions';
    private const MODEL   = 'openai/gpt-3.5-turbo';

    public function __construct(
        private readonly ?string $apiKey = null
    ) {
        $this->apiKey = config('services.openrouter.key') ?? env('OPENROUTER_KEY');
    }

    public function gradeAnswer(string $questionText, string $studentAnswer, int $maxPoints): array
    {
        if (empty($this->apiKey) || empty(trim($studentAnswer))) {
            return $this->fallbackGrade();
        }

        $prompt = sprintf(
            "Question: %s\n\nStudent's Answer: %s\n\n" .
            "Grade this answer from 0 to %d points. Consider accuracy, completeness, understanding, and clarity. " .
            "Return ONLY a JSON object: {\"score\": number, \"feedback\": \"string\", \"reason\": \"string\", \"correct_answer\": \"string\"}",
            $questionText,
            $studentAnswer,
            $maxPoints
        );

        try {
            $response = Http::withToken($this->apiKey)
                ->post(self::API_URL, [
                    'model'       => self::MODEL,
                    'temperature' => 0.3,
                    'max_tokens'  => 500,
                    'messages'    => [
                        [
                            'role'    => 'system',
                            'content' => 'You are an expert exam grader. Grade student answers fairly and provide constructive feedback. Always include the correct answer in your response.',
                        ],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content', '');

                preg_match('/\{.*\}/s', $text, $matches);

                if (isset($matches[0])) {
                    $result = json_decode($matches[0], true);

                    return [
                        'score'          => min(max((int) ($result['score'] ?? 0), 0), $maxPoints),
                        'feedback'       => $result['feedback']       ?? 'No feedback provided.',
                        'reason'         => $result['reason']         ?? '',
                        'correct_answer' => $result['correct_answer'] ?? 'Not provided',
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::error('AIGradingService error: ' . $e->getMessage());
        }

        return $this->fallbackGrade();
    }

    private function fallbackGrade(): array
    {
        return [
            'score'          => 0,
            'feedback'       => 'Answer submitted – awaiting manual review.',
            'reason'         => 'Automatic grading unavailable.',
            'correct_answer' => 'Not available',
        ];
    }
}
