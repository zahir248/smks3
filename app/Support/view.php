<?php

declare(strict_types=1);

/**
 * Include a view file in the global namespace (not App\Core).
 *
 * @param array<string, mixed> $vars
 */
function smks3_view_include(string $viewFile, array $vars = []): void
{
    // Do not let extract() clobber $viewFile / $vars (breaks partials when
    // callers pass get_defined_vars() from smks3_view_render).
    unset($vars['viewFile'], $vars['vars'], $vars['withLayout']);
    extract($vars, EXTR_OVERWRITE);
    require $viewFile;
}

/**
 * Render layout + view in one shared scope (header/view/footer share variables).
 *
 * Parameter must NOT be named $data — controllers often pass a $data variable
 * and extract(EXTR_SKIP) would skip it when the parameter already exists.
 *
 * @param array<string, mixed> $vars
 */
function smks3_view_render(string $viewFile, array $vars = [], bool $withLayout = true): void
{
    extract($vars, EXTR_OVERWRITE);

    if ($withLayout) {
        require VIEW_PATH . '/layouts/header.php';
        require $viewFile;
        require VIEW_PATH . '/layouts/footer.php';
        return;
    }

    require $viewFile;
}
