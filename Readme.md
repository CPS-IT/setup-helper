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
      "url": "git@gitlab.cps-projects.de:DevOps/project-builder.git"
    }
  ]
}
```
```bash
composer require fr/project-builder
```

### Configuration

Add a key `project-builder` to to the `extra` section of your `composer.json`.

#### Unlink files or folders
```json
{
  "extra": {
    "project-builder": [
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
    "project-builder": [
      {
        "move": {
          "path/to/old/file/or/folder": "path/to/new/file/or/folder",
          "other/file": "new/path"
        }
      }
    ]
  }
}
```

### Upcoming
* replace placeholder in templates
