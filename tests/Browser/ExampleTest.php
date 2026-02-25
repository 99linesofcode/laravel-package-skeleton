<?php

it('returns a successful response', function () {
    visit('/')->assertSee('Let\'s get started');
});
