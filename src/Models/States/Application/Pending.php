<?php

namespace Risky2k1\ApplicationManager\Models\States\Application;

class Pending extends ApplicationState
{
    public static string $name = 'pending';
    public function text(): string
    {
        return 'Chờ duyệt';
    }

    public function class(): string
    {
        return 'badge badge-warning badge-lg';
    }
}
