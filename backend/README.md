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
