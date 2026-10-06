# ARCHITECTURE.md

The architecture document for this repository, following the
[architecture.md](https://architecture.md) schema — built so an agent (or a
new colleague) can comprehend the repository from this file alone, and so the
architectural principles in the `software-architecture` and
`software-development` skills (expressed for Laravel in the `laravel` skill)
are visible in how this repo actually works. Fill every section; update it in
the same change that alters the architecture it describes.

This repository is a **module/package starter**: a domain-driven Laravel
package built on
[spatie/laravel-package-tools](https://github.com/spatie/laravel-package-tools),
with Testbench, Pest, PHPStan, Rector, Livewire and Filament wired up so the
module can be developed and tested in isolation.

## 1. Project Structure

Module-first with an explicit hexagonal-flavored layering. PSR-4 root
`Lines\Skeleton\` → `src/`. The `ServiceProvider` is the composition root;
the `App`/`Domain`/`Infrastructure` layers are the architecture axis.

```
laravel-package-skeleton/
├── src/
│   ├── SkeletonServiceProvider.php   # package bootstrap / composition root
│   ├── App/                          # UI layer
│   │   ├── Console/Commands/         # artisan commands
│   │   ├── Filament/                 # Resources, Pages, Schemas, Tables, Plugins
│   │   ├── Livewire/                 # public-facing components
│   │   └── Providers/
│   ├── Domain/                       # domain layer
│   │   ├── Actions/                  # *Action — invokable use cases
│   │   ├── DataTransferObjects/      # *Data — readonly DTOs
│   │   ├── Enums/                    # *Status — string enums / state machines
│   │   └── Models/                   # Eloquent models (no suffix)
│   └── Infrastructure/               # adapters (empty until a real need)
├── database/
│   ├── factories/                    # *Factory
│   ├── migrations/                   # create_*_table
│   └── seeders/                      # DatabaseSeeder
├── resources/views/{components,pages}/  # Blade views (<module>:: namespace)
├── routes/web.php                    # <module>.* named routes
├── stubs/                            # publishable stubs
├── tests/
│   ├── Pest.php, TestCase.php        # Pest + Testbench bindings
│   ├── Unit/Domain/...               # mirrors src/Domain
│   ├── Feature/                      # feature tests
│   └── Browser/                      # Playwright
├── workbench/                        # Testbench host app (models, providers, migrations)
├── testbench.yaml                    # host config: providers, migrations, seeders
├── phpstan.neon.dist                 # level 5, paths src + config
├── rector.php                        # paths src + tests
└── devshell/                         # git submodule: devshell-php
```

**Where the logic lives** (the `laravel` skill's placement table): a use case
is an **action** (`Domain/Actions/*Action`, invokable, DTO in → model out,
composing smaller actions); a readonly **DTO** (`*Data`) crosses the UI
boundary; **models** are lean data + identity; a computed value is calculated
by an action and stored, never an accessor loop; repeated query logic moves
to a `*QueryBuilder`, repeated collection logic to a `*Collection`. The UI
(`App/`) reaches the domain only through an action + DTO.

## 2. High-Level System Diagram

```
        host Laravel app (composer)                  workbench (Testbench host)
        ┌──────────────────────────┐                ┌──────────────────────────┐
        │ requires Lines\Skeleton   │                │ boots the panel,          │
        │ resolves the package into │                │ registers the plugin,     │
        │ vendor/ (path repo in dev)│                │ runs migrations + tests   │
        └────────────┬─────────────┘                └────────────┬─────────────┘
                     │                                            │
                     ▼                                            ▼
        ┌───────────────────────────────────────────────────────────────────┐
        │  src/SkeletonServiceProvider.php   (composition root)             │
        │    config · views · routes · migrations · commands · Livewire ·   │
        │    Filament assets — the one place wiring happens                 │
        └───────────────┬───────────────────┬───────────────────┬──────────┘
                        ▼                   ▼                   ▼
                   ┌─────────┐         ┌──────────┐        ┌────────────────┐
                   │  App/   │ ──────► │ Domain/  │ ◄──────│ Infrastructure/│
                   │  (UI)   │ action  │ (actions,│  impl  │  (adapters,    │
                   │ Filament│ + DTO   │  DTOs,   │  ports │   empty until  │
                   │ Livewire│         │  models) │        │   needed)      │
                   └─────────┘         └──────────┘        └────────────────┘
                        │                   │
                        ▼                   ▼
                   Blade views          Eloquent / DB (sqlite in tests)
```

## 3. Core Components

| Component | Responsibility | Technology |
|---|---|---|
| `SkeletonServiceProvider` | The module's composition root: declares config, views, translations, assets, routes, migrations, commands, Livewire namespace and Filament assets; the only place bindings happen | `Spatie\LaravelPackageTools\PackageServiceProvider` |
| `src/App/` | The UI layer: Filament resources/pages/schemas/tables, Livewire components, artisan commands, providers | Filament 5, Livewire 4 |
| `src/Domain/` | The domain: actions (use cases), readonly DTOs, enums, Eloquent models | PHP 8.2, Eloquent |
| `src/Infrastructure/` | Adapters — empty until a real external seam exists | — |
| `workbench/` | The Testbench host app that runs the module's panel and migrations in isolation | Orchestra Testbench |
| `database/`, `resources/`, `routes/`, `stubs/` | Package-owned migrations, factories, seeders, views, routes, publishable stubs | Laravel |

### Ports & adapters

The `Domain` owns a port only when a **real** seam exists (a second
implementation or an external dependency); `Infrastructure/` then holds the
adapter and the `ServiceProvider` binds it. Until that need appears,
`Infrastructure/` stays empty — no speculative ports or adapters (the lean
guardrail). When a port is added, it states the core need it serves, and the
domain never sees a provider's raw shape: adapters map onto canonical DTOs at
the boundary.

## 4. Data Stores

- **Application database** — owned by the consumer/host; this package ships
  `database/migrations/` and `database/seeders/`.
- **Test/workbench database** — SQLite created by
  `testbench package:create-sqlite-db`; `workbench/storage` is the Testbench
  working directory (git-ignored). Migrations come from
  `workbench/database/migrations` and `database/migrations`
  (`testbench.yaml`).
- **Models use UUID primary keys** (`HasUuids` + `uuid('id')->primary()` +
  `foreignUuid`), per the `laravel` skill.

## 5. External Integrations / APIs

None by default. The package integrates the Laravel framework, Filament and
Livewire, and (for testing) Testbench. A concrete module adds its own
third-party integrations behind ports in `Domain`/`Infrastructure`.

## 6. Deployment & Infrastructure

- **Distribution**: a Composer package. In production the host app requires
  it by version constraint; in development a **path repository** with
  `"symlink": true` points at the local checkout. The package registers
  itself via `composer.json`'s `extra.laravel.providers`
  (`Lines\Skeleton\SkeletonServiceProvider`).
- **Build**: `composer build` (`testbench workbench:build`);
  `composer prepare` discovers the package, creates the SQLite DB and runs
  migrations.
- **CI/CD**: GitHub Actions — `changelog.yaml` and `automatic-updates.yaml`.
  The shared `.github-php` starter supplies the `test.yaml`/`deploy.yaml`
  reusable workflows a consumer copies in.
- **Monitoring/logging**: app-owned.

## 7. Security Considerations

- **Secrets**: `.env` files are git-ignored; Testbench purges `.env` and
  generated public assets (`testbench.yaml`). No credentials in the package.
- **Dependencies**: Dependabot covers Composer, npm and the devshell
  submodule; `composer.lock` is tracked.
- **Static analysis**: PHPStan/Larastan level 5 with a (currently empty)
  baseline — new issues are not silently absorbed.
- No runtime attack surface beyond the package's own routes/commands.

## 8. Development & Testing Environment

- **Local setup**: initialize the `devshell` submodule
  (`git submodule update --init --recursive`), add `use flake ./devshell` to
  `.envrc`, then `direnv allow`.
- **Testing**: Pest 4 on Testbench — `composer test`
  (`testbench package:test --parallel`). Layout mirrors source:
  `tests/Unit/Domain/...` and `tests/Feature/...`.
- **Code quality**: **Pint** (formatting), **PHPStan via Larastan** (level 5,
  `phpstan.neon.dist`; includes `phpstan-baseline.neon`, scans `stubs` and
  the ide-helper files), **Rector** (`src` + `tests`), **Bladestan** for Blade.
  Composer scripts: `test`, `analyse`, `lint`, `format`, `refactor`, `build`,
  `prepare`, `serve`, `ide-helper`.
- **Mechanical gates** (and what each makes impossible):
  - `composer lint` — Pint `--test` plus PHPStan; style and type violations
    fail the build.
  - **Pest on Testbench** — behavioral tests in an isolated host app; a
    broken action/DTO/migration fails.
  - **Rector** — dead code, missing types and naming drift are corrected
    mechanically.
  - **Boundary gate**: **deptrac** is the PHP boundary gate (the language
    equivalent of `eslint-plugin-boundaries`) — the layer contract
    (`App`/UI → `Domain` → `Infrastructure`; `Domain` imports no UI;
    `Infrastructure` implements the domain's ports) is the rule it enforces.
    It is the designated gate for modules built from this skeleton; it is not
    yet wired into this starter's `composer.json`/CI.

## 9. Future Considerations / Roadmap

**Deliberate non-goals:**

- **`Infrastructure/` stays empty until a real adapter need.** No speculative
  ports or adapters.
- **No repositories over Eloquent.** A repository is added only for a real
  storage-swap seam; otherwise embrace the framework.
- **No `Utils/`/`Helpers/` dump.** Generic, framework-grade code goes to a
  `Support/` staging area on its way to its own package.
- **No nested modules.** Modules are Composer packages resolved into
  `vendor/`; the project tree holds only app-specific code.
- **No layer imports from the domain to the UI.** `Domain` never imports
  Filament/Livewire.

**Known debt / open items**: `phpstan.neon.dist` scans a `config` path that
the starter does not yet ship; a consumer that publishes config should keep
it. The PHPStan baseline is intentionally empty — keep it that way unless a
third-party issue truly cannot be fixed.

## 10. Project Identification

Project Name: laravel-package-skeleton

Repository URL: https://github.com/99linesofcode/laravel-package-skeleton

Primary Contact/Team: Jordy Schreuders (99linesofcode)

Date of Last Update: 2026-10-06

## 11. Glossary / Acronyms

- **Module / package** — a bounded context as a Composer package, PSR-4 root
  `Lines\<Module>\` → `src/`.
- **PSR-4** — the PHP autoloading standard.
- **Action** — an invokable use case, `*Action`; the single seam the UI calls.
- **DTO** — a readonly data transfer object, `*Data`.
- **ServiceProvider** — the package bootstrap and composition root.
- **Testbench** — the package that hosts a Laravel app for testing a package;
  `workbench/` is the host app.
- **Pest** — the BDD-style test framework.
- **Larastan** — PHPStan's Laravel extension.
- **Pint** — Laravel's formatter; **Rector** — automated refactoring.
- **Bladestan** — PHPStan for Blade templates.
- **deptrac** — the PHP dependency-boundary analyser; the layer gate.
- **Path repository** — a Composer `type: path` repository that symlinks a
  local package into `vendor/` for development.

## 12. Conventions & Boundaries

The house standards this repository adheres to — the full contract lives in
the `software-architecture` and `laravel` skills; this section records what
is enforced **here**.

- **Folder structure**: PSR-4, `Lines\<Module>\` → `src/`; hexagonal-flavored
  layering `App/` (UI) / `Domain/` / `Infrastructure/`. Domain folders grow
  as needed (`QueryBuilders/`, `Collections/`, `Events/`, `Exceptions/`,
  `Rules/`, `States/` appear when a second class of that role exists). The
  module repo name is singular (`laravel-module-todo`).
- **File naming**: role suffixes — `*Action`, `*Data`, `*Status`, `*Factory`,
  `*ServiceProvider`, `*QueryBuilder`, `*Collection`, `*Event`, `*Rule`.
  Models stay bare. Filament names (`*Resource`, `*Plugin`, `Create*`/`Edit*`/
  `List*`) follow the `filament` skill. One class per file.
- **Entry point**: the `*ServiceProvider` is the composition root — config,
  views, routes, migrations, commands and bindings are declared there; no
  `app()`/`resolve()` inside class bodies.
- **Dependency direction**: `App` (UI) → `Domain` → `Infrastructure`;
  `Domain` imports no Filament/Livewire; UI never calls Eloquent directly.
  Enforced by **deptrac**, the boundary gate.
- **Actions carry the logic**: every user story is an invokable action taking
  a DTO; actions compose actions; constructor injection; `DB::transaction()`
  around multi-write operations.
- **Canonical DTOs**: one readonly DTO per domain concept, extending the
  shared `DataTransferObject` base with a `casts()` map; raw provider shapes
  are mapped at the boundary.
- **Lean models**: UUID keys, data + identity only; no calculations in
  accessors; scopes become `*QueryBuilder`s; collection logic becomes a
  `*Collection`.
- **Testing**: Pest + Testbench; `tests/Unit/Domain/...` and
  `tests/Feature/...` mirror `src/`; factories carry fluent states; the
  workbench is the host.
- **Tooling**: Pint, Larastan/PHPStan (level 5, baseline kept empty), Rector,
  Bladestan — wired through the Composer scripts.
- **Documentation surfaces**: WHY comments at the change site; a change that
  alters the structure or the toolchain updates this file in the same change.
