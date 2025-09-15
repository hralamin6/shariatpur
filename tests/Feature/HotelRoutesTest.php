<?php

declare(strict_types=1);

it('renders hotels page', function () {
    $this->get(route('web.hotels'))->assertSuccessful();
});

