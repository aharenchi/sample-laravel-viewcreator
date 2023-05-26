<?php

declare(strict_types=1);

namespace Test\Unit\View\Creators\ViewSwitchCreator;

use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\View;
use Illuminate\View\ViewFinderInterface;
use Mockery;
use Mockery\MockInterface;
use App\View\Creators\ViewSwitchCreator;
use Jenssegers\Agent\Agent;
use Tests\TestCase;

final class ViewSwitchCreatorCreateTest extends TestCase
{
    public function test_ユーザーエージェントがspである場合sp指定のviewのPathに変更されているべき(): void
    {
        // 1. テストデータの用意と 3.検証
        // Illuminate関数を適切に呼び出せているかを検証する

        $spViewName = 'pages.test.index_sp';
        $spViewPath = '/resources/views/pages/test/index_sp.blade.php';

        $view = Mockery::mock(View::class, function (MockInterface $mock) use ($spViewPath) {
            // view名が取得されているべき。返り値はデフォルトのview名。
            $mock->shouldReceive('getName')
                ->once()
                ->andReturn('pages.test.index');

            // sp版のviewのpathを設定するべき。
            $mock->shouldReceive('setPath')
                ->once()
                ->with($spViewPath);
        });

        $viewFinder = Mockery::mock(ViewFinderInterface::class, function (MockInterface $mock) use ($spViewName, $spViewPath) {
            // sp版のview名でファイルPathの検索が行われているべき。
            $mock->shouldReceive('find')
                ->once()
                ->with($spViewName)
                ->andReturn($spViewPath);
        });
        $viewFactory = Mockery::mock(ViewFactory::class, function (MockInterface $mock) use ($spViewName, $viewFinder) {
            // sp版のview名でファイルの存在チェックが行われているべき。
            $mock->shouldReceive('exists')
                ->once()
                ->with($spViewName)
                ->andReturnTrue();

            // Finderインスタンスが取得されているべき。
            $mock->shouldReceive('getFinder')
                ->once()
                ->andReturn($viewFinder);
        });

        $agent = Mockery::mock(Agent::class, function (MockInterface $mock) {
            // ユーザーエージェントによるsp判定処理が行われているべき。
            $mock->shouldReceive('isMobile')
                ->once()
                ->andReturnTrue();
        });

        // 2. テスト対象の実行
        $viewSwitchCreator = new ViewSwitchCreator(
            agent: $agent,
            viewFactory: $viewFactory
        );
        $viewSwitchCreator->create($view);
    }

    public function test_ユーザーエージェントがspである場合すでにsp指定のview名ならばviewのPathは変更はないべき(): void
    {
        // 1. テストデータの用意と 3.検証
        // Illuminate関数を適切に呼び出せているかを検証する
        $view = Mockery::mock(View::class, function (MockInterface $mock) {
            // view名が取得されているべき。返り値はsp指定のview名。
            $mock->shouldReceive('getName')
                ->once()
                ->andReturn('pages.test.index_sp');

            // viewのpathは設定されないべき。
            $mock->shouldReceive('setPath')
                ->never();
        });

        $viewFinder = Mockery::mock(ViewFinderInterface::class, function (MockInterface $mock) {
            // ファイルPathの検索が行われないべき。
            $mock->shouldReceive('find')
                ->never();
        });
        $viewFactory = Mockery::mock(ViewFactory::class, function (MockInterface $mock) use ($viewFinder) {
            // ファイルの存在チェックが行われないべき。
            $mock->shouldReceive('exists')
                ->never();

            // Finderインスタンスが取得されないべき。
            $mock->shouldReceive('getFinder')
                ->never()
                ->andReturn($viewFinder);
        });

        $agent = Mockery::mock(Agent::class, function (MockInterface $mock) {
            // ユーザーエージェントによるsp判定処理が行われているべき。
            $mock->shouldReceive('isMobile')
                ->once()
                ->andReturnTrue();
        });

        // 2. テスト対象の実行
        $viewSwitchCreator = new ViewSwitchCreator(
            agent: $agent,
            viewFactory: $viewFactory
        );
        $viewSwitchCreator->create($view);
    }

    public function test_ユーザーエージェントがspである場合にspのviewテンプレートがなければviewのPathは変更されないべき(): void
    {
        // 1. テストデータの用意と 3.検証
        // Illuminate関数を適切に呼び出せているかを検証する
        $view = Mockery::mock(View::class, function (MockInterface $mock) {
            // view名が取得されているべき。返り値はデフォルトのview名。
            $mock->shouldReceive('getName')
                ->once()
                ->andReturn('pages.test.index');

            // viewのpathは設定されないべき。
            $mock->shouldReceive('setPath')
                ->never();
        });

        $viewFinder = Mockery::mock(ViewFinderInterface::class, function (MockInterface $mock) {
            // ファイルPathの検索が行われないべき。
            $mock->shouldReceive('find')
                ->never();
        });
        $viewFactory = Mockery::mock(ViewFactory::class, function (MockInterface $mock) use ($viewFinder) {
            // ファイルの存在チェックが行われているべき。
            $mock->shouldReceive('exists')
                ->once()
                ->andReturnFalse();

            // Finderインスタンスが取得されないべき。
            $mock->shouldReceive('getFinder')
                ->never()
                ->andReturn($viewFinder);
        });

        $agent = Mockery::mock(Agent::class, function (MockInterface $mock) {
            // ユーザーエージェントによるsp判定処理が行われているべき。
            $mock->shouldReceive('isMobile')
                ->once()
                ->andReturnTrue();
        });

        // 2. テスト対象の実行
        $viewSwitchCreator = new ViewSwitchCreator(
            agent: $agent,
            viewFactory: $viewFactory
        );
        $viewSwitchCreator->create($view);
    }

    public function test_ユーザーエージェントがspでない場合viewのPathは変更されないべき(): void
    {
        // 1. テストデータの用意と 3.検証
        // Illuminate関数を適切に呼び出せているかを検証する
        $view = Mockery::mock(View::class, function (MockInterface $mock) {
            // view名が取得されているべき。返り値はデフォルトのview名。
            $mock->shouldReceive('getName')
                ->once()
                ->andReturn('pages.test.index');

            // viewのpathは設定されないべき。
            $mock->shouldReceive('setPath')
                ->never();
        });

        $viewFinder = Mockery::mock(ViewFinderInterface::class, function (MockInterface $mock) {
            // ファイルPathの検索が行われないべき。
            $mock->shouldReceive('find')
                ->never();
        });
        $viewFactory = Mockery::mock(ViewFactory::class, function (MockInterface $mock) use ($viewFinder) {
            // ファイルの存在チェックが行われないべき。
            $mock->shouldReceive('exists')
                ->never();

            // Finderインスタンスが取得されないべき。
            $mock->shouldReceive('getFinder')
                ->never()
                ->andReturn($viewFinder);
        });

        $agent = Mockery::mock(Agent::class, function (MockInterface $mock) {
            // ユーザーエージェントによるsp判定処理が行われているべき。
            $mock->shouldReceive('isMobile')
                ->once()
                ->andReturnFalse();
        });

        // 2. テスト対象の実行
        $viewSwitchCreator = new ViewSwitchCreator(
            agent: $agent,
            viewFactory: $viewFactory
        );
        $viewSwitchCreator->create($view);
    }
}
