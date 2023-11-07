# Genshin Journey API

## Introduction

The API is built on Cockpit CMS (v0.12.x). For detailed information, you can refer to the [documentation](https://getcockpit.com/documentation).

## Collections

+ charactersv2
+ dict
+ gacha
+ gachaWeapons

## Singletons

+ aboutv2

## Token

You can include the token in your request in two ways:

1. As a query parameter:
```
https://sushicat.pp.ua/api/genshin/api/collections/get/charactersv2?token=a4191046104f8f3674f788e804c2d0
```

2. In the request headers:
```
Cockpit-Token: a4191046104f8f3674f788e804c2d0
```

## Examples

Here are some example requests:

**Simple Request:** This will display all fields, sorted by ID (default):
```
https://sushicat.pp.ua/api/genshin/api/collections/get/charactersv2?token=a4191046104f8f3674f788e804c2d0
```

The following examples demonstrate how to display only specific fields, rare characters first, start with one item, and display only 36 pieces (useful for paginated results):

**Get Request:**
```
https://sushicat.pp.ua/api/genshin/api/collections/get/charactersv2?sort[rarity]=-1&skip=0&limit=36&fields[name]=1&fields[nameeng]=1&fields[rarity]=1&fields[ico]=1&token=a4191046104f8f3674f788e804c2d0
```

**Post Request (JSON Body):**
```
https://sushicat.pp.ua/api/genshin/api/collections/get/charactersv2?token=a4191046104f8f3674f788e804c2d0
```
Body (JSON application/json):
```json
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

For more information, you can refer to the [Cockpit documentation](https://getcockpit.com/documentation).

## Weapons Script

Currently, weapons are not available in the Cockpit CMS. They are obtained and parsed from another site using a simple script.

### Type

You can specify the type of weapon and sorting (normal, reversed, sorted) using the following request format:
```
https://sushicat.pp.ua/api/genshin/additional/weapons.php?type=sword_normal
```

### ID

Specify the desired ID for the weapon using the following request format:
```
https://sushicat.pp.ua/api/genshin/additional/weapons.php?id=the_black_sword
```

## Authorization (Without Any Access)

- Login: test
- Password: test123

## Fallback

In case the main server is unreachable, a proxy server can be used. (Not recommended in other cases)

Example to get data:
```
https://api.genshin-journey.site/.netlify/functions/index/api/collections/get/charactersv2?filter[nameeng]=faruzan&token=a4191046104f8f3674f788e804c2d0
```

Example to get an image:
```
https://api.genshin-journey.site/.netlify/functions/img/genshin/storage/uploads/2023/05/11/Faruzan_Portrait_2_uid_645cad680f9f5.png
```

## Preview Features

### Comments

To retrieve comments for a specific branch, use the following request format:
```
https://sushicat.pp.ua/api/genshin/additional/comments/get.php?branch=diona
```

There are also methods available for posting comments and deleting them. After testing, all test comments will be removed.

### BASE64 Image (To-Do: Reduce Photo Size)

```
https://sushicat.pp.ua/api/image.php?path=api/genshin/storage/uploads/2022/12/05/itto-3-3_uid_638e1f8dd1901.jpg
```
