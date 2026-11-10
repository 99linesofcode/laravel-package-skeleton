<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Architecture
|--------------------------------------------------------------------------
|
| The check rules of the `architecture` skill: `direction`, `sideways`,
| `suffix`, `public-interface` and `shape`. Each test is named after the rule
| it checks, so a failure names the rule. Nothing here names a context or
| this repository: the root namespace is the one composer.json maps to src/,
| and the contexts are the folders found under src/Core/. A repository that
| departs from a rule records it in CODING_STANDARDS.md and edits this file.
|
*/

$base = dirname(__DIR__);
$composer = json_decode((string) file_get_contents($base.'/composer.json'), true);
$root = (string) array_search('src/', $composer['autoload']['psr-4'], true);
$root = rtrim($root, '\\');
$source = $base.'/src';

// Whether any folder or class matches a namespace, `*` matching one segment.
// Pest's arch layers fail on a `*` namespace that matches no folder, and a
// folder appears only with its first file.
$exists = function (string $namespace) use ($root, $source): bool {
    $path = $source.str_replace('\\', '/', substr($namespace, strlen($root)));

    return glob($path, GLOB_ONLYDIR) !== [] || glob($path.'.php') !== [];
};

// A namespace as the test names show it: relative to the root namespace.
$relative = fn (string $namespace): string => ltrim(substr($namespace, strlen($root)), '\\');

$folders = fn (string $path): array => array_map(basename(...), glob($path.'/*', GLOB_ONLYDIR) ?: []);

$contexts = array_values(array_diff($folders($source.'/Core'), ['SharedKernel']));

// The composition root: the classes at the source root.
$compositionRoot = array_map(
    fn (string $file): string => $root.'\\'.basename($file, '.php'),
    glob($source.'/*.php') ?: [],
);

// The model foundation and the framework surface the Laravel Environment
// declares for the core: Eloquent and what it returns, the contracts that
// count as ports, and the transaction and after-commit event idioms.
$framework = [
    'Illuminate\Database\Eloquent',
    'Illuminate\Support\Carbon',
    'Illuminate\Support\Collection',
    'collect',
    'now',
    'today',
    'Illuminate\Contracts\Cache',
    'Illuminate\Contracts\Mail',
    'Illuminate\Contracts\Queue',
    'Illuminate\Contracts\Events\ShouldDispatchAfterCommit',
    'Illuminate\Foundation\Events\Dispatchable',
    'Illuminate\Queue\SerializesModels',
    'Illuminate\Support\Facades\DB',
];

// Defines an arch test only when its target and at least one dependency exist.
$denies = function (string $rule, string $target, array $dependencies) use ($exists, $relative): void {
    $dependencies = array_values(array_filter($dependencies, $exists));

    if ($exists($target) && $dependencies !== []) {
        arch("{$rule}: {$relative($target)} uses no ".implode(', ', array_map($relative, $dependencies)))
            ->expect($target)
            ->not->toUse($dependencies);
    }
};

/*
| direction: adapters → application → domain, and downward to the shared
| kernel, Lib and the language, plus the declared model foundation.
*/

$outer = ["{$root}\\Infrastructure", "{$root}\\UserInterface", ...$compositionRoot];

$denies('direction', "{$root}\\Core\\*\\Domain", ["{$root}\\Core\\*\\Application", ...$outer]);
$denies('direction', "{$root}\\Core\\*\\Application", $outer);
$denies('direction', "{$root}\\Core\\SharedKernel", [
    ...array_map(fn (string $context): string => "{$root}\\Core\\{$context}", $contexts),
    ...$outer,
]);
$denies('direction', "{$root}\\Infrastructure", ["{$root}\\UserInterface", ...$compositionRoot]);
$denies('direction', "{$root}\\UserInterface", ["{$root}\\Infrastructure", ...$compositionRoot]);
$denies('direction', "{$root}\\Lib", ["{$root}\\Core", ...$outer]);

if ($exists("{$root}\\Core")) {
    arch('direction: the core uses only the core, Lib and the declared framework surface')
        ->expect("{$root}\\Core")
        ->toOnlyUse(["{$root}\\Lib", ...$framework]);
}

/*
| sideways: a context uses no other context's code. Every context folder on
| disk is checked against its siblings, so a new context needs no edit here.
*/

foreach ($contexts as $context) {
    $siblings = array_map(
        fn (string $sibling): string => "{$root}\\Core\\{$sibling}",
        array_values(array_diff($contexts, [$context])),
    );

    if ($siblings !== []) {
        arch("sideways: {$context} uses no sibling context")
            ->expect("{$root}\\Core\\{$context}")
            ->not->toUse($siblings);
    }
}

/*
| suffix: application and adapter roles carry their role folder's suffix.
*/

$suffixes = [
    "{$root}\\Core\\*\\Application\\Actions" => 'Action',
    "{$root}\\Core\\*\\Application\\Queries" => 'Query',
    "{$root}\\Core\\*\\Application\\Ports" => 'Port',
    "{$root}\\Core\\*\\Application\\Listeners" => 'Listener',
    "{$root}\\Core\\*\\Application\\DataTransferObjects" => 'Data',
    "{$root}\\Infrastructure" => 'Adapter',
];

foreach ($suffixes as $folder => $suffix) {
    if ($exists($folder)) {
        arch('suffix: '.basename(str_replace('\\', '/', $folder))." classes end in {$suffix}")
            ->expect($folder)
            ->toHaveSuffix($suffix);
    }
}

/*
| public-interface: outside code uses only Core\*\Application and
| Core\*\Domain\Events. Everything else under the source root, bar the
| composition root, is used only inside the package. Outside code is every
| other namespace composer.json autoloads, such as the workbench.
*/

$private = array_map(
    fn (string $folder): string => "{$root}\\{$folder}",
    array_values(array_diff($folders($source), ['Core'])),
);

foreach ($folders($source.'/Core') as $context) {
    foreach (array_diff($folders("{$source}/Core/{$context}"), ['Application']) as $layer) {
        if ($layer !== 'Domain') {
            $private[] = "{$root}\\Core\\{$context}\\{$layer}";

            continue;
        }

        foreach (array_diff($folders("{$source}/Core/{$context}/Domain"), ['Events']) as $role) {
            $private[] = "{$root}\\Core\\{$context}\\Domain\\{$role}";
        }
    }
}

if ($private !== []) {
    arch('public-interface: only Core\*\Application and Core\*\Domain\Events are used outside the package')
        ->expect($private)
        ->toOnlyBeUsedIn($root);
}

/*
| shape: layers first, contexts only inside Core, roles in their folders.
*/

test('shape: the source root holds only the shape\'s folders', function () use ($source): void {
    $entries = fn (string $path): array => array_values(array_filter(
        is_dir($path) ? (scandir($path) ?: []) : [],
        fn (string $entry): bool => ! str_starts_with($entry, '.'),
    ));

    // Lists what under $path is a file, or a folder not in $allowed (null: any folder).
    $offShape = function (string $path, ?array $allowed) use ($entries, $source): array {
        if (! is_dir($path)) {
            return [];
        }

        return array_values(array_map(
            fn (string $entry): string => substr("{$path}/{$entry}", strlen($source) + 1),
            array_filter(
                $entries($path),
                fn (string $entry): bool => ! is_dir("{$path}/{$entry}")
                    || ($allowed !== null && ! in_array($entry, $allowed, true)),
            ),
        ));
    };

    $offenders = [
        ...array_filter(
            $offShape($source, ['Core', 'UserInterface', 'Infrastructure', 'Lib']),
            fn (string $entry): bool => ! str_ends_with($entry, '.php'),
        ),
        ...$offShape($source.'/UserInterface', null),
        ...$offShape($source.'/Infrastructure', null),
        ...$offShape($source.'/Core', null),
    ];

    foreach (array_diff($entries($source.'/Core'), ['SharedKernel']) as $context) {
        $offenders = [
            ...$offenders,
            ...$offShape("{$source}/Core/{$context}", ['Application', 'Domain']),
            ...$offShape("{$source}/Core/{$context}/Application", ['Actions', 'Queries', 'Listeners', 'Ports', 'DataTransferObjects']),
            ...$offShape("{$source}/Core/{$context}/Domain", ['Models', 'Events', 'ValueObjects', 'Services']),
        ];
    }

    expect($offenders)->toBe([]);
});
