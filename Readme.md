Project Builder
===============

Builds projects based on configuration and templates.

This is a composer plugin. It performs predefined tasks on composer after `update` or `install` command.

### Usage
The package is not yet available through packagist.org. Add an entry to the `repositories` section of the `composer.json`:
```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@gitlab.cps-projects.de:DevOps/setup-helper.git"
    }
  ]
}
```
```bash
composer require cpsit/setup-helper
```

### Configuration

Add a key `setup-helper` to to the `extra` section of your `composer.json`.

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

* paths must be relative to your composer root directory or absolute
* there is **no confirmation request**. Any existing file or folder will be removed!


### Move files or folders
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

### Rename files or folders
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

### Make directory
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

### Upcoming
* replace placeholder in templates
