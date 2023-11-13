<?php

namespace Risky2k1\ApplicationManager\Models\States\Application;

class Approved extends ApplicationState
{
    public static string $name = 'approved';
    public function text(): string
    {
        return 'Đã duyệt';
    }

    public function class(): string
    {
        return 'badge badge-success badge-lg';
    }
}
