<?php

declare(strict_types=1);

it('renders entrepreneurs page', function () {
    $this->get(route('web.entrepreneurs'))->assertSuccessful();
});
