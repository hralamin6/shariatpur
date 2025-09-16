<?php

declare(strict_types=1);

it('renders lands page', function () {
    $this->get(route('web.lands'))->assertSuccessful();
});

