# VIP

> If the app is going to be deployed on VIP you will need to make the following changes.

## Replace wp-content with the VIP Go Skeleton

`rm -rf wp-content`

`git clone https://github.com/Automattic/vip-go-skeleton wp-content`

## Change the vendor location to be a client-mu-plugin

```json
"config": {
    "vendor-dir": "wp-content/client-mu-plugins/vendor"
}
```

## Change the install location of mu-plugins

```json
"wp-content/client-mu-plugins/{$name}/": [
    "type:wordpress-muplugin"
],
```

## Replace install with vip-install

`rm bin/install;`

`mv bin/vip-install bin/install`

> Or remove `bin/install` and just use `bin/vip-install`

## Deactivate plugins that already come with VIP
`bin/docker/wp plugin deactivate advanced-caching`
