<?php
namespace App\Utils;

class Components
{
    public static function statsCard($title = 'title', $subtitle = 'subtitle', $iconName = 'fa-info'): string
    {
        return '<div class="card border-left-primary shadow h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">' . $title . '</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">' . $subtitle . '</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas ' . $iconName . ' fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>';
    }
}