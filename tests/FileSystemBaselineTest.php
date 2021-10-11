<?php

use Eightfold\FileSystem\Item;

beforeEach(function () {
    expect(
        Item::create(__DIR__)->append('data', '.write')
            ->didDelete()
    )->toBeTrue();
});

test('File system can create file', function() {
    $sut = Item::create(__DIR__)->append('data', '.write', 'file.md')
        ->save('content');

    expect(
        $sut->doesExist()
    )->toBeTrue();

    expect(
        $sut->content()
    )->toBe(
        'content'
    );
});

test('File system has expected content', function() {
    $result = Item::create(__DIR__)->append('data')->content();

    $list = [];
    foreach ($result as $item) {
        $list[] = $item->thePath();
    }

    expect(
        $list
    )->toBe([
        __DIR__ ."/data/inner-folder",
        __DIR__ ."/data/link.md",
        __DIR__ ."/data/table.md",
    ]);
});

test('File system can get mime type', function() {
    expect(
        Item::create(__FILE__)->theMimeType()
    )->toBe(
        'text/x-php'
    );

    expect(
        Item::create(__DIR__)->theMimeType()
    )->toBe(
        'directory'
    );
});

test('File system can check path existence', function() {
    expect(
        Item::create(__FILE__)->doesExist()
    )->toBeTrue();

    expect(
        Item::create(__DIR__)->doesExist()
    )->toBeTrue();

    expect(
        Item::create(__DIR__)->append('data', '.write')->doesExist()
    )->toBeFalse();
});

test('File system can check file versus folder', function() {
    expect(
        Item::create(__FILE__)->isFile()
    )->toBeTrue();

    expect(
        Item::create(__DIR__)->isFile()
    )->toBeFalse();

    expect(
        Item::create(__FILE__)->isFolder()
    )->toBeFalse();

    expect(
        Item::create(__DIR__)->isFolder()
    )->toBeTrue();
});

test('File system can go up levels', function() {
    $dir   = __DIR__;
    $parts = explode('/', $dir);
    array_pop($parts);
    array_pop($parts);
    $path  = implode('/', $parts);

    expect(
        Item::create(__DIR__)->up(2)->thePath()
    )->toBe(
        $path
    );
});
