# HTTPS

As part of the nginx docker container we set up a self-signed certificate. This allows us to access the site locally using HTTPS. We do this, because all our websites are served over HTTPS and so we want to ensure we are doing the same locally.

The issue with self-signed certificates is browsers (quite rightly) deem them unsafe. You should too. **You should only bypass the security warnings of your browser if you are working on a secure network**.

## Using a local certificate authority (CA)

[The general recommended approach for not relying on self-signed certificates is to set up a local CA](https://web.dev/how-to-use-local-https/). There are several tools for doing this, but perhaps the easiest is [mkcert](https://github.com/FiloSottile/mkcert).

Before some basic instructions for setting this up, here is some information/disclaimer:

**Installing a local CA is at your own risk. Although it will only be trusted by you, a local CA will contaminate your trusted CAs. This means, if your local environment is compromised, or mkcert, then your entire HTTPS trust chain can easily be compromised too. It is for this reason we do not set this up by default and cannot recommend this approach.**

If you're comfortable with all that and still want to go ahead, here's how you can do it with mkcert.

### Install mkcert

On Mac:
```bash
brew install mkcert
brew install nss # if you use Firefox
```

For other platforms see: https://github.com/FiloSottile/mkcert#installation

### Add mkcert to your local root CAs

```bash
mkcert -install
```

### Generate a certificate

```bash
mkcert my-project.local -cert-file docker/nginx/local_https_cert.pem -key-file docker/nginx/local_https_key.pem # Change the host accordingly
```

### Update docker config

Add the following volumes to the `nginx` container within `docker-compose.yml`

```yaml
- './docker/nginx/local_https_cert.pem:/etc/pki/tls/certs/local_https_cert.pem:delegated'
- './docker/nginx/local_https_key.key:/etc/pki/tls/private/local_https_key.key:delegated'
```

Update `docker/nginx/conf/app.conf` to point to your certificate and key:

```ini
ssl_certificate /etc/pki/tls/certs/local_https_cert.pem;
ssl_certificate_key /etc/pki/tls/local_https_key.pem;
```

### Rebuild containers

`docker-compose stop; docker-compose build; docker-compose up`

## Any other options?

There are, this page from the chromium site lists a few other things you might want to try also: https://www.chromium.org/Home/chromium-security/deprecating-powerful-features-on-insecure-origins#TOC-Testing-Powerful-Features
