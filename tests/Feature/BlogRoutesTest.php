<?php

declare(strict_types=1);

it('renders blog categories page', function () {
    $this->get(route('web.blog.categories'))->assertSuccessful();
});

it('renders blogs page without category filter', function () {
    $this->get(route('web.blogs'))->assertSuccessful();
});
