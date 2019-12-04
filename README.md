

# Using docker container for testing

## create container image
```
docker build -t niisan/php .
```

## run container

```
docker run --rm -it -v `pwd`:/var/www niisan/php
```