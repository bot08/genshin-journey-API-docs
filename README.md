# Genshin journey API

## Introduction

The API is based on the cockpit CMS, so you may read the [documentation](https://getcockpit.com/documentation) first.

## Collections

+ charactersv2
+ dict
+ gacha
+ gachaWeapons

## Singletons

+ about

## Token

Token: `a4191046104f8f3674f788e804c2d0`\
It can be sent in a request (see examples)

It can also be sent in headers:
```
Cockpit-Token: a4191046104f8f3674f788e804c2d0
```

## Examples

The simplest request. Displays all fields, sorted by id (default):

```
https://sushicat.pp.ua/api/genshin/api/collections/get/charactersv2?token=a4191046104f8f3674f788e804c2d0
```

The following examples will output only 4 fields, rare characters in front, start with 1 item and display only 36 pieces (useful for pages):

#### get

url:
```
https://sushicat.pp.ua/api/genshin/api/collections/get/charactersv2?sort[rarity]=-1&skip=0&limit=36&fields[name]=1&fields[nameeng]=1&fields[rarity]=1&fields[ico]=1&token=a4191046104f8f3674f788e804c2d0
```

#### post

url:
```
https://sushicat.pp.ua/api/genshin/api/collections/get/charactersv2?token=a4191046104f8f3674f788e804c2d0
```
body (json application/json):
```
{
  "fields": {
    "name": 1,
    "nameeng": 1,
    "rarity": 1,
    "ico": 1
  },
  "limit": 37,
  "skip": 0,
  "sort": { 
    "rarity": -1 
  }
}
```

See the [cockpit documentation](https://getcockpit.com/documentation) for more information.

## Authorization (without any access)

login: test\
password: test123

## Preview

[BASE64 image](https://sushicat.pp.ua/api/image.php?path=api/genshin/storage/uploads/2022/12/05/itto-3-3_uid_638e1f8dd1901.jpg)
