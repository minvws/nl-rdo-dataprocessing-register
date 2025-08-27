# Verwerkings register public website

This directory contains a hugo configuration for building static html files for the public website. The markdown content for this website will be provided by the CMS.

This website has 2 themes: `manon` and `rijkshuisstijl`. See [Rijkshuisstijl](#Rijkshuisstijl) for more information.

# Getting started

## Prerequisites

-   An up-to-date [Docker (Desktop)](https://www.docker.com/products/docker-desktop/) installation

### Run development server

```
docker run -p 1313:1313 \
  -v ${PWD}:/src \
  --rm \
  hugomods/hugo:exts-0.120.4 \
  hugo server --bind 0.0.0.0
```

### Build site

```
docker run \
  -v ${PWD}:/src \
  hugomods/hugo:exts-0.120.4 \
  hugo
```

# Rijkshuisstijl

### Run development server

```
docker run -p 1313:1313 \
  -v ${PWD}:/src \
  --rm \
  hugomods/hugo:exts-0.120.4 \
  hugo server --bind 0.0.0.0 \
  --theme rijkshuisstijl
```

### Build site

```
docker run \
  -v ${PWD}:/src \
  hugomods/hugo:exts-0.120.4 \
  hugo --theme rijkshuisstijl
```
