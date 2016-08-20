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
/kartoffel/api/search/location?q=Berlin
/kartoffel/api/search/location?q=Berlin&only_city=0
```
  
### Hotel collection
 
 Proxy for hotel collection with path resolver
 
```
/kartoffel/api/search/hotel-collection?path=8514&start_date=2016-08-20&end_date=2016-08-25
/kartoffel/api/search/hotel-collection?path=Berlin
/kartoffel/api/search/hotel-collection?path=8514
 ```