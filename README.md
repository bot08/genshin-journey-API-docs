# Genshin Journey API

API for accessing data from [genshin-journey.space](https://genshin-journey.space).

## Base URLs

The API is available through multiple endpoints to ensure reliability and language support:

**Russian Data:**
* Primary: `https://sushicat.pp.ua/api/genshin`
* Fallback: `https://api.genshin-journey.space/.netlify/functions/index`

**Ukrainian Data:**
* Primary: `https://sushicat.pp.ua/api/genshin-ua`
* Fallback: `https://api.genshin-journey.space/.netlify/functions/index-ua`

*For simplicity in the examples below, `[BASE_URL]` will generally refer to one of the primary base URLs (e.g., `https://sushicat.pp.ua/api/genshin`). Choose the appropriate one for your needs.*

## Architecture Overview

The backend architecture consists of multiple components:

1. **Cockpit CMS (v0.12.x)** - Main database containing most of the information
2. **Additional Scripts** - Custom endpoints for weapons, artifacts, and comments
3. **Proxy API** - Fallback server at `api.genshin-journey.space` with additional undocumented scripts

## Authentication

Most endpoints require an **API Token**: `a4191046104f8f3674f788e804c2d0`

The token can be used in two ways:

### URL Parameter
```
?token=a4191046104f8f3674f788e804c2d0
```

### Header
```
Cockpit-Token: a4191046104f8f3674f788e804c2d0
```

## Data Collections

The API provides access to the following Cockpit CMS collections:

- **charactersv2**
- **dict**
- **gacha**
- **gachaWeapons**
- **chronicle**

## Singletons

- **aboutv2**

### Get *charactersv2* (example)

#### Basic Request (All Fields)
```http
GET [BASE_URL]/api/collections/get/charactersv2?token=a4191046104f8f3674f788e804c2d0
```

#### Advanced Request with Filtering
**GET Request:**
```http
GET [BASE_URL]/api/collections/get/charactersv2?sort[rarity]=-1&skip=0&limit=36&fields[name]=1&fields[nameeng]=1&fields[rarity]=1&fields[ico]=1&token=a4191046104f8f3674f788e804c2d0
```

**POST Request:**
```http
POST [BASE_URL]/api/collections/get/charactersv2?token=a4191046104f8f3674f788e804c2d0
Content-Type: application/json

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

### Weapons Script

Weapons data is obtained through custom scripts as they are not stored in the Cockpit CMS.

#### By Type
```http
GET [BASE_URL]/additional/weapons/get.php?type=sword_normal
```

**Available types**: `{weapon_type}_{sorting}`
- Weapon types: `sword`, `bow`, `catalyst`, `claymore`, `polearm`
- Sorting options: `normal`, `reversed`, `sorted`

#### By ID
```http
GET [BASE_URL]/additional/weapons/get.php?id=the_black_sword
```

### Artifacts Script

The artifacts endpoint works identically to the weapons script but uses a different endpoint.

#### By Type
```http
GET [BASE_URL]/additional/artefacts/get.php?type=normal
```

#### By ID
```http
GET [BASE_URL]/additional/artefacts/get.php?id=noblesse_oblige
```

### Comments System

#### Retrieve Comments
```http
GET [BASE_URL]/additional/comments/get.php?branch=diona
```

#### Add Comment
```http
GET [BASE_URL]/additional/comments/add.php?username=User123&avatar_id=1&comment=test123&branch=diona&code_use=CODE1
```

#### Delete Comment (Admin)
```http
GET [BASE_URL]/additional/comments/delete.php?secret=(admin_code)&id=1
```

#### Validate Promocode
```http
GET [BASE_URL]/additional/comments/promo_validation.php?code_view=CODE1
```

## Test Authorization

For testing purposes, you can use:
- **Login**: `test`
- **Password**: `test123`

> **Note**: This provides limited access and is intended for testing only.

## Fallback/Proxy Server

If the main server is unavailable, use the proxy server (not recommended for regular use):

```http
GET https://api.genshin-journey.space/.netlify/functions/index/api/collections/get/charactersv2?filter[nameeng]=faruzan&token=a4191046104f8f3674f788e804c2d0
```

Images can be retrieved for both Russian and Ukrainian versions:

```http
GET https://api.genshin-journey.space/.netlify/functions/img/genshin/storage/uploads/2023/05/11/Faruzan_Portrait_2_uid_645cad680f9f5.png
```

## Preview Features

### BASE64 Image Conversion
Convert images to BASE64 format (optimization in progress):

```http
GET https://sushicat.pp.ua/api/image.php?path=api/genshin/storage/uploads/2022/12/05/itto-3-3_uid_638e1f8dd1901.jpg
```

## Additional Documentation

For more detailed information about query parameters, filtering, and advanced usage, refer to the [Cockpit CMS documentation](https://getcockpit.com/documentation) as the API is built on Cockpit CMS v0.12.x.

## Important Notes

1. The backend architecture is complex with multiple data sources
2. Some scripts and endpoints are undocumented (like AI service)
3. The Cockpit CMS is legacy but contains the majority of the data
4. Always use the primary URLs when possible; fallback URLs are for emergency use only
