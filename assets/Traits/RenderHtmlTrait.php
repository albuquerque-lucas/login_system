<?php

namespace LucasAlbuquerque\LoginSystem\Traits;

trait RenderHtmlTrait
{
    public function renderHtml(string $templatePath, array $data)
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../../' . $templatePath;
        $html = ob_get_clean();

        return $html;
    }
}