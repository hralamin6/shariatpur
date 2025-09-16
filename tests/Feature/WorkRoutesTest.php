<?php

declare(strict_types=1);

it('renders works page', function () {
    $this->get(route('web.works'))->assertSuccessful();
});

