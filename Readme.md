[![Build Status](https://travis-ci.org/CPS-IT/setup-helper.svg?branch=master)](https://travis-ci.org/CPS-IT/setup-helper)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=CPS-IT_setup-helper&metric=alert_status)](https://sonarcloud.io/dashboard?id=CPS-IT_setup-helper)

Setup Helper
============

Helps setting up project bundles based on configuration and templates.

This is a composer plugin. It performs predefined tasks on composer after `update` or `install` command.

### Usage
```bash
composer require cpsit/setup-helper
```

### Configuration

Add a key `setup-helper` to to the `extra` section of your `composer.json`.
**Note**: Paths must be relative to your composer root directory or absolute.

#### Copy files or folder
```json
{
  "extra": {
    "setup-helper": [
      {
        "copy": {
          "path/to/file/or/folder": "path/to/target",
          "other/file": "other/target"
        }
      }
    ]
  }
}
```

#### Unlink files or folders
```json
{
  "extra": {
    "setup-helper": [
      {
        "unlink": [
          "path/to/file/or/folder",
          "other file"
        ]
      }
    ]
  }
}
```

* there is **no confirmation request**. Any existing file or folder will be removed!


#### Move files or folders
```json
{
  "extra": {
    "setup-helper": [
      {
        "move": {
          "path/to/old/file/or/folder": "path/to/new/folder",
          "other/file": "new/path"
        }
      }
    ]
  }
}
```
**Note**: _Move_ does not rename a file or folder. If required combine it with a _Rename_ task. 

#### Rename files or folders
```json
{
  "extra": {
    "setup-helper": [
      {
        "rename": {
          "path/to/old/file/or/folder": "newName",
          "other/file": "otherName"
        }
      }
    ]
  }
}
```
The source path is relative to the current working directory. This should always be the composer root directory, if the Installer is called via composer plugin API as expected.

#### Make directory
```json
{
  "extra": {
    "setup-helper": [
      {
        "makeDirectory": [
          "path/to/new/folder"
        ]
      }
    ]
  }
}
```
The directory path is relative to the current working directory. This should always be the composer root directory, if the Installer is called via composer plugin API as expected.
Any missing directory will be created recursively.

#### Symlink from source to target
```json
{
  "extra": {
    "setup-helper": [
      {
        "symlink": {
          "path/to/source/file": "target",
          "file": "even/deeper/path/to/target"
        }
      }
    ]
  }
}
```
The source path is relative to the current working directory. This should always be the composer root directory, if the Installer is called via composer plugin API as expected. On existing source or target no symlink is created.


#### Replace 

* Replace a string with another string:
```json
{
  "extra": {
    "setup-helper": [
      {
        "replace": [
          {
            "path": "path/to/file",
            "search": "string-to-replace", 
            "replace": "replacement string"
           }           
        ]
      }
    ]
  }
}
```

* Replace a string with a string given as answer to a question (interactively)
```json
{
  "extra": {
    "setup-helper": [
      {
        "replace": [
           {
             "path": "path/to/file",
             "search": "string-or-pattern-to-replace", 
             "ask": "Question to ask for (Answer replaces pattern)"
           }           
        ]
      }
    ]
  }
}
```

The `path` key respects Ant-like globbing.

Syntax:

* `?` matches any character
* `*` matches zero or more characters, except `/`
* `/**/` matches zero or more directory names
* `[abc]` matches a single character `a`, `b` or `c`
* `[a-c]` matches a single character `a`, `b` or `c`
* `[^abc]` matches any character but `a`, `b` or `c`
* `[^a-c]` matches any character but `a`, `b` or `c`
* `{ab,cd}` matches `ab` or `cd`

E.g. `"path": /path/to/dir/*.css` will select all files ending in `.css` in that directory.

See documentation of [glob library](https://github.com/webmozart/glob) for details.

### Caveats

Currently we rely on a the fork [cpsit/glob](https://github.com/CPS-IT/glob) of `webmozart/glob` since the original doesn't allow 
recent PHP versions. 
