# Genshin journey API

## Introduction

The API is based on the cockpit CMS (v0.12.x), so you may read the [documentation](https://getcockpit.com/documentation) first.

## Collections

+ charactersv2
+ dict
+ gacha
+ gachaWeapons

## Singletons

+ aboutv2

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

## Weapons script
At the moment the weapons do not exist in the cockpit CMS. They are obtained and parsed from another site using a simple script.

So script can take type and id parameters.

#### Type

It must get type of weapon + sorting (normal, reversed, sorted).

Request looks like this:
```
https://sushicat.pp.ua/api/genshin/additional/weapons.php?type=sword_normal
```

#### Id

Just specify the desired Id here.

Request looks like this:
```
https://sushicat.pp.ua/api/genshin/additional/weapons.php?id=the_black_sword
```

## Authorization (without any access)

login: test\
password: test123

## Fallback

If the user cannot contact the main server, a proxy server can be used. <sub>(not recommended in other cases)</sub>

Example to get data:
```
https://api.genshin-journey.site/.netlify/functions/index/api/collections/get/charactersv2?filter[nameeng]=faruzan&token=a4191046104f8f3674f788e804c2d0
```
Example to get image:
```
https://api.genshin-journey.site/.netlify/functions/img/genshin/storage/uploads/2023/05/11/Faruzan_Portrait_2_uid_645cad680f9f5.png
```

## Preview features

#### Comments <sub>(todo: better backend validation)</sub>

Example to get comments for branch 'diona':
```
https://sushicat.pp.ua/api/genshin/additional/comments/get.php?branch=diona
```

There are also methods available for posting comments and deleting them. After testing, all test comments will be removed.

#### BASE64 image <sub>(todo: reduce photo size)</sub>
```
https://sushicat.pp.ua/api/image.php?path=api/genshin/storage/uploads/2022/12/05/itto-3-3_uid_638e1f8dd1901.jpg
```
