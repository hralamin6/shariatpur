<?php

declare(strict_types=1);

it('renders car types page', function () {
    $this->get(route('web.car.types'))->assertSuccessful();
});

it('renders cars page without type filter', function () {
    $this->get(route('web.cars'))->assertSuccessful();
});
