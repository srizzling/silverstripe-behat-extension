## User Docs to upgrade to Behat 3

* There is a behat.yml change that is required to make behat work.

Parameter %silverstripe.paths.base% doesn't exist anymore and has been replaced with ```%paths.base%```

```
screenshot_path: %paths.base%/_artifacts/screenshots
``` 