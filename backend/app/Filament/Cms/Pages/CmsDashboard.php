<?php

namespace App\Filament\Cms\Pages;

use App\Filament\Cms\Widgets\CmsOverviewWidget;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class CmsDashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $title = 'CMS Dashboard';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = -2;

    public function getHeaderWidgetsColumns(): int|array
    {
        return 4;
    }

    public function getWidgets(): array
    {
        return [
            CmsOverviewWidget::class,
        ];
    }
}
