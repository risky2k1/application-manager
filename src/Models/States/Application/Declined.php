<?php

namespace Risky2k1\ApplicationManager\Models\States\Application;

class Declined extends ApplicationState
{
    public static string $name = 'declined';
    public function text(): string
    {
        return 'Không duyệt';
    }

    public function class(): string
    {
        return 'badge badge-danger badge-lg';
    }
}
