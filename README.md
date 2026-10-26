# Genshin Journey API

API for accessing data from [genshin-journey.site](https://genshin-journey.site).

## Base URLs

One server serves every language (see [Languages](#languages)):

* Primary: `https://gj.sushicat.pp.ua`
* Fallback: `https://genshin-journey.site/gj`, a proxy to the same server for users who can't reach it directly. Paths and parameters are the same.

The site checks `[BASE_URL]/test.php` and switches to the fallback when the primary does not answer.

*In the examples below, `[BASE_URL]` is `https://gj.sushicat.pp.ua`.*

## Architecture Overview

The backend architecture consists of multiple components:

1. **Cockpit CMS (v0.12.x)** - Main database: characters, banners, weapons, artifacts and the rest of the content, in Russian, Ukrainian and Japanese
2. **Additional Scripts** - Custom endpoints for comments

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

## Languages

All languages live in the same collections. Russian is stored in the usual fields, the translations next to them as `<field>_uk` and `<field>_ja`. The `lang` parameter puts the chosen language into the usual fields:

- `lang=uk`: Ukrainian.
- `lang=ja`: Japanese.
- `lang=ru` or `lang=default`: Russian, without the `_uk` and `_ja` fields.
- No `lang`: Russian, plus every `_uk` and `_ja` field.

Every collection and singleton is translated.

```http
GET [BASE_URL]/api/collections/get/weapons?lang=uk&filter[nameeng]=the_black_sword
```

- A field without a translation falls back to Russian. Add `ignoreDefaultFallback=1` to get `null` instead.
- With `fields`, also request the translated field, or you get Russian: `lang=uk&fields[name]=1&fields[name_uk]=1`.
- `filter` and `sort` work on the stored fields, so use the suffixed name for translated values: `lang=uk&sort[name_uk]=1`, `lang=uk&filter[type_uk]=Меч`, `lang=ja&filter[name_ja][$regex]=天空`.
- Fields that are not texts (`nameeng`, `rarity`, `ico`, dates) are the same in every language.

The site does exactly this: the Russian site sends `lang=default`, the Ukrainian (`/uk`) `lang=uk`, the Japanese (`/ja`) `lang=ja`, and adds `fields[<field>_<lang>]=1` next to every `fields[<field>]=1`. For example, the characters list on the Ukrainian site:

```http
GET [BASE_URL]/api/collections/get/charactersv2?sort[_id]=-1&limit=20&fields[name]=1&fields[name_uk]=1&fields[nameeng]=1&fields[rarity]=1&fields[ico]=1&lang=uk
```

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

One entry per weapon, everything released up to the current game version. Texts are the official Russian localization; see [Languages](#languages) for Ukrainian and Japanese.

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

### Availability Check

Returns `{"accessibility": true}` when the server is up.

```http
GET [BASE_URL]/test.php
```

### Comments System

The current comments API is in `/additional/comments/v2/`. It shares the comments with the old [v1 API](#comments-v1).

#### Retrieve Comments
```http
GET [BASE_URL]/additional/comments/v2/get.php?branch=diona&owner_code=OWNER_CODE
```
`owner_code` is optional. Each comment has `mine: true` when it was created with that code.

#### Add Comment
First get a proof-of-work challenge:
```http
GET [BASE_URL]/additional/comments/v2/challenge.php
```
Returns `{"challenge": "...", "difficulty": 16}`. Find a `nonce` such that `sha256(challenge + nonce)` starts with `difficulty` zero bits, then:
```http
POST [BASE_URL]/additional/comments/v2/add.php
Content-Type: application/x-www-form-urlencoded

username=User123&avatar_id=1&comment=test123&branch=diona&challenge=CHALLENGE&nonce=NONCE&owner_code=OWNER_CODE&website=
```
Returns `{"comment_id": 1, "owner_code": "..."}`. Store `owner_code` in localStorage and send it with later comments so all of them share one code. `website` is a honeypot that must stay empty. Each challenge works once and expires after 10 minutes. Errors return JSON `{"error": "..."}`: `pow_required`, `pow_expired`, `pow_failed`, `pow_used` (403), `rate_limit`, `rate_limit_global` (429), `duplicate`, `too_many_links`, `bad_data` (400). A ready client is in `additional/comments/v2/client_example.js`.

#### Delete Comment
```http
GET [BASE_URL]/additional/comments/v2/delete.php?id=1&owner_code=OWNER_CODE
GET [BASE_URL]/additional/comments/v2/delete.php?id=1&secret=(admin_code)
```
The author deletes with `owner_code`, the admin with `secret`.

#### All Comments
```http
GET [BASE_URL]/additional/comments/v2/get_all.php
```
Every comment from every branch, newest first.

## Test Authorization

For testing purposes, you can use:
- **Login**: `test`
- **Password**: `test123`

> **Note**: This provides limited access and is intended for testing only.

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
4. Always use the primary URL when possible; the fallback is for emergency use only

## Legacy API (deprecated)

These endpoints still respond, but they are no longer updated and may be removed. Use the replacements listed for each.

### Collection *characters*

The first version of the characters collection: only `name`, `rarity`, `href` and `ico`, and only a few entries. Use `charactersv2`.

```http
GET [BASE_URL]/api/collections/get/characters
```

### Collection *menu*

The menu of the old site: `name`, `href` and `img` for each section. Not used by the current site.

```http
GET [BASE_URL]/api/collections/get/menu
```

### Singleton *about*

The first version of the "About" page: `content`, `copy`, `discord` and `discord2`. Use `aboutv2`.

```http
GET [BASE_URL]/api/singletons/get/about
```

### Weapons Script

Use the `weapons` collection.

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

`/additional/weapons.php` is an older copy of the same script with the same parameters.

### Artifacts Script

Use the `artefacts` collection.

#### By Type
```http
GET [BASE_URL]/additional/artefacts/get.php?type=normal
```

**Available types**: `normal`, `reversed`, `sorted`

#### By ID
```http
GET [BASE_URL]/additional/artefacts/get.php?id=noblesse_oblige
```

### Comments v1

The first comments API, in `/additional/comments/`. Adding a comment needs a promocode. Use [v2](#comments-system). Both APIs share the same comments.

```http
GET [BASE_URL]/additional/comments/get.php?branch=diona
GET [BASE_URL]/additional/comments/add.php?username=User123&avatar_id=1&comment=test123&branch=diona&code_use=CODE1
GET [BASE_URL]/additional/comments/delete.php?secret=(admin_code)&id=1
GET [BASE_URL]/additional/comments/get_all.php
GET [BASE_URL]/additional/comments/promo_validation.php?code_view=CODE1
```

`promo_validation.php?code_view=` returns how many uses the promocode has left.

### Old Servers

Before the move to one server, the data was spread over separate servers. They are no longer updated and do not answer without a token:

* Russian: `https://sushicat.pp.ua/api/genshin`
* Ukrainian: `https://sushicat.pp.ua/api/genshin-ua`
* Netlify proxy: `https://api.genshin-journey.space/.netlify/functions/index` and `index-ua`, images through `.../functions/img/genshin/<path>`

Use `[BASE_URL]` with [`lang`](#languages) instead.
