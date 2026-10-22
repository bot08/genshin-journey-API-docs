# Genshin Journey API

API for accessing data from [genshin-journey.space](https://genshin-journey.space).

## Base URLs

The API is available through multiple endpoints to ensure reliability and language support:

**Russian Data:**
* Primary: `https://gj.sushicat.pp.ua`
* Fallback 1: `https://sushicat.pp.ua/api/genshin`
* Fallback 2: `https://api.genshin-journey.space/.netlify/functions/index`

**Ukrainian Data:**
* Primary: `https://sushicat.pp.ua/api/genshin-ua`
* Fallback: `https://api.genshin-journey.space/.netlify/functions/index-ua`

*For simplicity in the examples below, `[BASE_URL]` will generally refer to one of the primary base URLs (e.g., `https://gj.sushicat.pp.ua`). Choose the appropriate one for your needs.*

## Architecture Overview

The backend architecture consists of multiple components:

1. **Cockpit CMS (v0.12.x)** - Main database: characters, banners, weapons, artifacts and the rest of the content
2. **Additional Scripts** - Custom endpoints for comments
3. **Proxy API** - Fallback server at `api.genshin-journey.space` with additional undocumented scripts

## Authentication

Read requests are public: no token is needed.

## Data Collections

The API provides access to the following Cockpit CMS collections:

- **charactersv2**
- **dict**
- **gacha**
- **gachaWeapons**
- **chronicle**
- **weapons**
- **artefacts**

## Singletons

- **aboutv2**

### Get *charactersv2* (example)

#### Basic Request (All Fields)
```http
GET [BASE_URL]/api/collections/get/charactersv2
```

#### Advanced Request with Filtering
**GET Request:**
```http
GET [BASE_URL]/api/collections/get/charactersv2?sort[rarity]=-1&skip=0&limit=36&fields[name]=1&fields[nameeng]=1&fields[rarity]=1&fields[ico]=1
```

**POST Request:**
```http
POST [BASE_URL]/api/collections/get/charactersv2
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

### Get *weapons*

One entry per weapon, everything released up to the current game version. Texts are the official Russian localization.

**Fields:**
- `name` - name, e.g. `Чёрный меч`
- `nameeng` - URL slug (paimon.moe id), e.g. `the_black_sword`
- `rarity` - `1`–`5`
- `type` - `Меч`, `Двуручный меч`, `Копьё`, `Катализатор` or `Лук`
- `ico` - icon, PNG 256×256: `{"path": "/storage/uploads/..."}`
- `ver` - game version of release, e.g. `1.0`
- `atkmin` / `atkmax` - base ATK at level 1 / max level, e.g. `42` / `510`
- `substat` - secondary stat name, empty for 1★–2★, e.g. `Шанс крит. попадания`
- `substatmin` / `substatmax` - secondary stat at level 1 / max level, e.g. `6%` / `27,6%`
- `skillname` / `skill` - passive name / text, refinements as `20%/25%/30%/35%/40%`
- `desk` - description

#### List by Type, Rarest First, with Pagination
```http
GET [BASE_URL]/api/collections/get/weapons?filter[type]=Меч&sort[rarity]=-1&sort[_id]=-1&limit=20&skip=0&fields[name]=1&fields[nameeng]=1&fields[rarity]=1&fields[ico]=1
```

#### Search by Name
```http
GET [BASE_URL]/api/collections/get/weapons?filter[name][$regex]=небесн
```

#### By ID
```http
GET [BASE_URL]/api/collections/get/weapons?filter[nameeng]=the_black_sword
```

### Get *artefacts*

One entry per artifact set.

**Fields:**
- `name` - set name, e.g. `Сердце горна`
- `nameeng` - URL slug (paimon.moe id for old sets), e.g. `heart_of_the_furnace`
- `rarity` - max rarity, `1`–`5`
- `rarities` - rarity range for display, e.g. `4-5`
- `ico` - icon (flower; circlet for the 1-piece «Шаман» sets): `{"path": "/storage/uploads/..."}`
- `ver` - game version of release, e.g. `7.0`
- `bonus1` - 1-piece bonus (only the «Шаман» sets)
- `bonus2` / `bonus4` - 2-piece / 4-piece bonus
- `flower`, `plume`, `sands`, `goblet`, `circlet` - piece names

#### List, Rarest First
```http
GET [BASE_URL]/api/collections/get/artefacts?sort[rarity]=-1&sort[_id]=-1&limit=20&skip=0
```

#### By ID
```http
GET [BASE_URL]/api/collections/get/artefacts?filter[nameeng]=noblesse_oblige
```

### Sorting, Search and Pagination

Both collections were filled in release order, so `_id` follows the release date.

- Newest first: `sort[_id]=-1`
- Rarest first: `sort[rarity]=-1&sort[_id]=-1`
- Least rare first: `sort[rarity]=1&sort[_id]=1`
- Alphabetical: `sort[name]=1`
- Search: `filter[name][$regex]=<text>` is case-insensitive.
- Pagination: `limit` and `skip`; the response has `total`.
- Images: `[BASE_URL]` + `ico.path`.

### Legacy Weapons and Artifacts Scripts (deprecated)

`/additional/weapons/get.php` and `/additional/artefacts/get.php` still respond, but they are no longer updated and will be removed. Use the `weapons` and `artefacts` collections instead.

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
GET https://api.genshin-journey.space/.netlify/functions/index/api/collections/get/charactersv2?filter[nameeng]=faruzan
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

For more detailed information about query parameters, filtering, and advanced usage, refer to the [Cockpit CMS documentation](https://github.com/agentejo/cockpit-docs) as the API is built on Cockpit CMS v0.12.x.

## Important Notes

1. The backend architecture is complex with multiple data sources
2. Some scripts and endpoints are undocumented (like AI service)
3. The Cockpit CMS is legacy but contains the majority of the data
4. Always use the primary URLs when possible; fallback URLs are for emergency use only
