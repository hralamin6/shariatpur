<?php

declare(strict_types=1);

it('renders restaurants page', function () {
    $this->get(route('web.restaurants'))->assertSuccessful();
});

