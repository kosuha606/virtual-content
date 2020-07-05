<?php

use kosuha606\VirtualAdmin\Test\TestSearchProvider;
use kosuha606\VirtualContent\Domains\Article\Models\ArticleVm;
use kosuha606\VirtualContent\Domains\Page\Models\PageVm;
use kosuha606\VirtualContent\Domains\Text\Models\TextVm;
use kosuha606\VirtualModel\VirtualModelManager;
use kosuha606\VirtualModelHelppack\Test\VirtualTestCase;
use PHPUnit\Framework\TestCase;

class ExampleTest extends VirtualTestCase
{
    public function setUp()
    {
        parent::setUp();
        VirtualModelManager::getInstance()->setProvider(new TestSearchProvider());
    }

    /**
     * @throws Exception
     */
    public function testArticle()
    {
        ArticleVm::create([
            'id' => 1,
            'title' => 'hello',
            'photo' => '/art.png',
            'slug' => 'helo',
            'content' => 'info',
            'created_at' => '2020-02-20',
        ])->save();

        $this->assertEquals(1, count($this->provider->memoryStorage[ArticleVm::class]));
    }

    /**
     * @throws Exception
     */
    public function testPage()
    {
        PageVm::create([
            'id' => 1,
            'title' => 'hello',
            'slug' => 'helo',
            'content' => 'info',
            'created_at' => '2020-02-20',
        ])->save();

        $this->assertEquals(1, count($this->provider->memoryStorage[PageVm::class]));
    }

    /**
     * @throws Exception
     */
    public function testText()
    {
        TextVm::create([
            'id' => 1,
            'description' => 'some descr',
            'code' => 'some code',
            'content' => 'info',
            'created_at' => '2020-02-20',
        ])->save();

        $this->assertEquals(1, count($this->provider->memoryStorage[TextVm::class]));
    }
}