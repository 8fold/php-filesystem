# 8fold File System for PHP

File System is for manipulating a local file system.

The primary interface is designed to simplify traversing fully-qualified paths. The secondary interface allows for reading, writing, and listing items based on the path.

Each path is represented by an instance of the `Item` class.

## Installation

{how does one install the product}

## Usage

Let's say we had the following folder structure:

```bash
.
├── 8fold/
│   └── Desktop/
│       ├── app/
│       │   ├── Models/
│       │   │   └── Main.php
│       │   └── Providers
│       ├── data/
│       │   └── target.md
│       ├── routes/
│       │   └── web.php
│       ├── vendor
│       └── .env
└── Josh
```

Let's say we wanted to get the content of the `target.md` file from within the `Main.php` class.

```php
...

use Eightfold\FileSystem\FileSystem;

...

  public function targetContent(): string
  {
	return Item::create(__DIR__)
      ->up(2)->append('data', 'target.md')
      ->content();
  }

...
```

The path given to the create static initializer sets a starting point. We use the `up` and `append` methods to move from that starting point. The `FileSystem` class is relatively immutable, which means the `up` and `append` methods do not modify the initial path stored by the instance; rather, they return a new instance with the new path.

The `content` method returns the content of a file or a list of files, folders, or both within a directory.

Terminal methods begin with verbs and determiners such as `is`, `does`, and `the`; methods considered to be fluent, such as `up` and `append` have no prefix.

## Details

Many of the 8fold sites under development use a simple flat-file system for storing content and data. We wanted something that fit in with the surronding code and, as usual, an interface that we could use throughout our code to reduce coupling directly to Flysystem from the league of extraordinary packages.

## Other

{links or descriptions or license, versioning, and governance}
