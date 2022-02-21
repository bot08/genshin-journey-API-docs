# Genshin jorney API

## Введение

API работает на cockpit CMS, поэтому для начала ознакомитесь с его [документацией](https://getcockpit.com/documentation).

## Collections

+ charactersv2
+ dict
+ gacha

## Singletons

+ about

## Token

Токен: a4191046104f8f3674f788e804c2d0\
Его можно отправлять в запросе (см. примеры)

Также его можно отсылать в headers:
```
Cockpit-Token: a4191046104f8f3674f788e804c2d0
```

## Примеры

Самый простой запрос. Выведет все поля, сортировка по id(по умолчанию):

```
https://sushicat.pp.ua/api/genshin/api/collections/get/charactersv2?token=a4191046104f8f3674f788e804c2d0
```

Следующие примеры выведут только 4 поля, редкие персонажи впереди, начинает с 1 элемента и отобразит только 36 штук(полезно для страниц):

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


Больше информации читайте в [документации cockpit](https://getcockpit.com/documentation).

## Авторизация (без любого доступа)

login: test\
password: test123

## Ошибка 403

Если вы столкнулись с данной проблемой, то установите в header запроса:
```
User-Agent: "любое устройство"
```

Также она может быть связана с неправильным токеном (см. token)
