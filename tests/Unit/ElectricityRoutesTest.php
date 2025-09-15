<?php

declare(strict_types=1);

it('registers the electricity offices route', function () {
    $url = route('web.electricity_offices');

    expect($url)->toBeString()->and($url)->toContain('/electricity-offices');
});
