<?php

declare(strict_types=1);

it('renders beauty parlors page', function () {
    $this->get(route('web.beauty_parlors'))->assertSuccessful();
});

