<?php

it('finds missing debug statements', function () {
    expect(['dd', 'dump', 'vardump'])
        ->not->toBeUsed();
});

it('does not use the validator facade', function () {
    expect(\Illuminate\Support\Facades\Validator::class)
        ->not->toBeUsed()->ignoring('App\Actions\Fortify');
});
