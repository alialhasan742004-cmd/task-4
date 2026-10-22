<?php

namespace App\Services;

use App\Contracts\AiProvider;

class PhotoEnhancer
{
    protected AiProvider $aiProvider;

    public function __construct(AiProvider $aiProvider)
    {
        $this->aiProvider = $aiProvider;
    }

    public function process($file)
    {
        $originalPath = $file->store('originals', 'public');
        $originalHash = md5_file($file->getRealPath());

        
        $result = $this->aiProvider->enhancePhoto($file);

        
        if ($result['success']) {
            return [
                'success' => true,
                'enhanced_path' => $result['path'],
                'original_path' => $originalPath,
                'original_hash' => $originalHash,
                'message' => $result['message']
            ];
        }

       
        return [
            'success' => false,
            'enhanced_path' => $originalPath,
            'original_path' => $originalPath,
            'original_hash' => $originalHash,
            'message' => 'فشل الذكاء الاصطناعي، وتم الاحتفاظ بالصورة الأصلية بنجاح: ' . $result['message']
        ];
    }
}
