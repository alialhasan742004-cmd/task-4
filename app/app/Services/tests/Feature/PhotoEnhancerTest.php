<?php

namespace Tests\Feature;

use App\Services\PhotoEnhancer;
use App\Services\MockAiProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoEnhancerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('public');
    }

    
    public function test_enhancement_succeeds_and_keeps_original_photo()
    {
        
        putenv('AI_MOCK_SUCCESS=true');

        $file = UploadedFile::fake()->image('product.jpg', 600, 600);
        $enhancer = new PhotoEnhancer(new MockAiProvider());

        $result = $enhancer->process($file);

        
        $this->assertTrue($result['success']);
        $this->assertNotNull($result['enhanced_path']);
        Storage::disk('public')->assertExists($result['original_path']);
    }

   
    public function test_provider_failure_returns_original_photo_gracefully()
    {
        
        putenv('AI_MOCK_SUCCESS=false');

        $file = UploadedFile::fake()->image('product.jpg', 600, 600);
        $enhancer = new PhotoEnhancer(new MockAiProvider());

        $result = $enhancer->process($file);
      
        $this->assertFalse($result['success']);
        $this->assertEquals($result['original_path'], $result['enhanced_path']);
        Storage::disk('public')->assertExists($result['original_path']);
    }

    public function test_original_file_hash_remains_unchanged()
    {
        $file = UploadedFile::fake()->image('product.jpg', 600, 600);
        $originalHash = md5_file($file->getRealPath());

        $enhancer = new PhotoEnhancer(new MockAiProvider());
        $result = $enhancer->process($file);

        
        $this->assertEquals($originalHash, $result['original_hash']);
    }
}
