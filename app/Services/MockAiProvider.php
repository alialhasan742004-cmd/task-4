<?php

namespace App\Services;

use App\Contracts\AiProvider;

class MockAiProvider implements AiProvider
{
    public function enhancePhoto($file): array
    {
        $isSuccessful = filter_var(env('AI_MOCK_SUCCESS', true), FILTER_VALIDATE_BOOLEAN);

        if ($isSuccessful) {
            return [
                'success' => true,
                'path' => 'enhanced/mock_enhanced_' . time() . '.jpg',
                'message' => 'تم تحسين الصورة بنجاح بواسطة الذكاء الاصطناعي الوهمي.'
            ];
        }

        return [
            'success' => false,
            'message' => 'فشلت خدمة الذكاء الاصطناعي مؤقتاً.'
        ];
    }
}
