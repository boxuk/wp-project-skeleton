# Patched Plugins

Any plugins saved locally that have a patch should the patch saved with them. This allows for updating the core plugin and re-applying the patch.

## How to generate patches with git?

Stage any changes, then generate the patch:

```sh
git add patched-plugins/example
git diff > patched-plugins/example-001.patch
```

## How to apply patches with git

Simply:

```sh
git apply /path/to/some-changes.patch
```

And that’s it! The changes are now re-applied.
