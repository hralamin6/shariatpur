<?php

declare(strict_types=1);

it('renders news categories page', function () {
    $this->get(route('web.news.categories'))->assertSuccessful();
});

it('renders news page without category filter', function () {
    $this->get(route('web.news'))->assertSuccessful();
});

