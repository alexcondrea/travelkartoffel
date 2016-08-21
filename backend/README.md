Trivago Hackathon 2016 - travelkartoffel
=========

API / Backend for Frontend <-> trivago API
http://company.trivago.com/hackathon-2016/

## Links

 * https://api.trivago.com/webservice/tas/secure/docs/index.html
 * https://github.com/trivago/tas-sdk-php
 * https://api.trivago.com/webservice/tas/secure/docs/index.html#header-php-example
 
## Search

### Locations
 
```
http://tripvago.ga/kartoffel/api/search/location?q=Berlin
http://tripvago.ga/kartoffel/api/search/location?q=Berlin&only_city=0
```
  
### Hotel collection
 
 Proxy for hotel collection with path resolver
 
```
http://tripvago.ga/kartoffel/api/search/hotel-collection?path=8514&start_date=2016-08-20&end_date=2016-08-25
http://tripvago.ga/kartoffel/api/search/hotel-collection?path=Berlin
http://tripvago.ga/kartoffel/api/search/hotel-collection?path=8514
 ```
 
 ### Hotel collection

Predefined route / step
 
```
http://tripvago.ga/kartoffel/api/storage
http://tripvago.ga/kartoffel/api/storage/berlin-leipzig-dortmund
```

### Price Route Calculation

Try to found a cheapest hotel route combination

```
http://tripvago.ga/kartoffel/api/price?start_date=2016-08-30
```

```
{
  "bestStep": {
    "steps": [
      {
        "location": "Berlin",
        "nights": 2,
        "price": 318,
        "start": "2016-08-30",
        "end": "2016-09-01"
      },
      {
        "location": "Leipzig",
        "nights": 5,
        "price": 400,
        "start": "2016-09-01",
        "end": "2016-09-06"
      },
      {
        "location": "Dortmund",
        "nights": 3,
        "price": 177,
        "start": "2016-09-06",
        "end": "2016-09-09"
      }
    ],
    "price": 895
  },
  "result": [
    {
      "steps": [
        {
          "location": "Berlin",
          "nights": 2,
          "price": 318,
          "start": "2016-08-30",
          "end": "2016-09-01"
        },
        {
          "location": "Leipzig",
          "nights": 5,
          "price": 400,
          "start": "2016-09-01",
          "end": "2016-09-06"
        },
        {
          "location": "Dortmund",
          "nights": 3,
          "price": 177,
          "start": "2016-09-06",
          "end": "2016-09-09"
        }
      ],
      "price": 895
    },
    {
      "steps": [
        {
          "location": "Leipzig",
          "nights": 5,
          "price": 565,
          "start": "2016-08-30",
          "end": "2016-09-04"
        },
        {
          "location": "Berlin",
          "nights": 2,
          "price": 406,
          "start": "2016-09-04",
          "end": "2016-09-06"
        },
        {
          "location": "Dortmund",
          "nights": 3,
          "price": 177,
          "start": "2016-09-06",
          "end": "2016-09-09"
        }
      ],
      "price": 1148
    },
    {
      "steps": [
        {
          "location": "Berlin",
          "nights": 2,
          "price": 318,
          "start": "2016-08-30",
          "end": "2016-09-01"
        },
        {
          "location": "Dortmund",
          "nights": 3,
          "price": 198,
          "start": "2016-09-01",
          "end": "2016-09-04"
        },
        {
          "location": "Leipzig",
          "nights": 5,
          "price": 600,
          "start": "2016-09-04",
          "end": "2016-09-09"
        }
      ],
      "price": 1116
    },
    {
      "steps": [
        {
          "location": "Dortmund",
          "nights": 3,
          "price": 144,
          "start": "2016-08-30",
          "end": "2016-09-02"
        },
        {
          "location": "Berlin",
          "nights": 2,
          "price": 438,
          "start": "2016-09-02",
          "end": "2016-09-04"
        },
        {
          "location": "Leipzig",
          "nights": 5,
          "price": 600,
          "start": "2016-09-04",
          "end": "2016-09-09"
        }
      ],
      "price": 1182
    },
    {
      "steps": [
        {
          "location": "Leipzig",
          "nights": 5,
          "price": 565,
          "start": "2016-08-30",
          "end": "2016-09-04"
        },
        {
          "location": "Dortmund",
          "nights": 3,
          "price": 144,
          "start": "2016-09-04",
          "end": "2016-09-07"
        },
        {
          "location": "Berlin",
          "nights": 2,
          "price": 230,
          "start": "2016-09-07",
          "end": "2016-09-09"
        }
      ],
      "price": 939
    },
    {
      "steps": [
        {
          "location": "Dortmund",
          "nights": 3,
          "price": 144,
          "start": "2016-08-30",
          "end": "2016-09-02"
        },
        {
          "location": "Leipzig",
          "nights": 5,
          "price": 580,
          "start": "2016-09-02",
          "end": "2016-09-07"
        },
        {
          "location": "Berlin",
          "nights": 2,
          "price": 230,
          "start": "2016-09-07",
          "end": "2016-09-09"
        }
      ],
      "price": 954
    }
  ]
}
```

### Price route save up

HTML callback to get price save up on a recombined route 

```
[POST] http://tripvago.ga/kartoffel/api/price?start_date=2016-08-30&current_price=10
```

Content to post
```
[
      {
        "location": "Berlin",
        "nights": 1
      },
      {
        "location": "Freiburg",
        "nights": 3
      },
      {
        "location": "Duisburg",
        "nights": 6
      }
]
```

```
Be flexible and save some money: Recombine your steps to Dortmund, Berlin, Leipzig and save up to 235 €
```
