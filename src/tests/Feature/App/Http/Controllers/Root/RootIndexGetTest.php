<?php

namespace Tests\Feature\App\Http\Controllers\Root;

use Tests\TestCase;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class RootIndexGetTest extends TestCase
{
    public const MOBILE_USER_AGENT =
    'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/W.X.Y.Z Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
    public const PC_USER_AGENT =
    'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Chrome/W.X.Y.Z Safari/537.36';

    public function test_ユーザーエージェントがSPである場合SP用の表示になるべき(): void
    {
        // 1. テストデータの用意
        // 無し

        // 2. テスト対象の実行
        $response = $this->get(
            uri: '/',
            headers: [
                'User-Agent' => self::MOBILE_USER_AGENT,
            ]
        );

        // 3. 検証
        $this->assertSame($response->status(), HttpResponse::HTTP_OK, 'http status codeが200になるべき');

        /**
         * @var View $view
         */
        $view = $response->original;
        $actualViewPath = $view->getPath();
        // ViewSwitchCreatorでPathしか変更しないのでview名を評価するassertViewHasは利用できない。またテストの環境によって絶対Pathが異なるためSP用テンプレートが含まれるかで判定する
        $this->assertStringContainsString('index_sp.blade.php', $actualViewPath);
    }

    public function test_ユーザーエージェントがPCである場合デフォルト用の表示になるべき(): void
    {
        // 1. テストデータの用意
        // 無し

        // 2. テスト対象の実行
        $response = $this->get(
            uri: '/',
            headers: [
                'User-Agent' => self::PC_USER_AGENT,
            ]
        );

        // 3. 検証
        $this->assertSame($response->status(), HttpResponse::HTTP_OK, 'http status codeが200になるべき');
        $response->assertViewIs('pages.index');
    }
}
