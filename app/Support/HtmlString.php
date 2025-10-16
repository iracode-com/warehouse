<?php

namespace App\Support\HtmlString;

use function Laravel\Prompts\text;

function NoItemWhereFound(): string
{
    return '
        <span style="--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);" class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-success"> <svg style="--c-500:var(--success-500);" class="fi-badge-icon h-4 w-4 h-4 w-4 text-custom-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
        </svg>
        ' . __('No items were found in your database.') . '</span>
    ';
}