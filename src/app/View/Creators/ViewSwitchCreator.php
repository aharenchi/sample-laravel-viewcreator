<?php

namespace App\View\Creators;

use Illuminate\View\View;
use Illuminate\View\Factory as ViewFactory;
use Jenssegers\Agent\Agent;

final class ViewSwitchCreator
{
    private const VIEW_NAME_SUFFIX = '_sp';

    public function __construct(
        private readonly Agent $agent,
        private readonly ViewFactory $viewFactory,
    ) {
    }

    public function create(View $view): void
    {
        $viewName = $view->getName();

        if ($this->agent->isMobile() !== true) {
            // spではない場合は置換する必要がないのでスキップ
            return;
        }

        if (str_contains($viewName, self::VIEW_NAME_SUFFIX)) {
            // sp向けviewテンプレートの指定がある場合は置換する必要がないのでスキップ
            return;
        }

        $viewSpName = $viewName . self::VIEW_NAME_SUFFIX;

        // sp向けviewテンプレートがない場合は何もせず、設定済みのviewテンプレートを表示する
        if ($this->viewFactory->exists($viewSpName)) {
            $finder = $this->viewFactory->getFinder();
            $view->setPath($finder->find($viewSpName));
        }
    }
}
