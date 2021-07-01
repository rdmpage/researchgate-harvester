# ResearchGate harvester

Retrieve thumbnails of people images from ResearchGate so that we can display an avatar for a name. Somewhat inspired by [scholrly/peeper](https://github.com/scholrly/peeper).

ResearchGate is difficult to scrape using naive methods, so ended up saving source for various web pages and parsing that, pulling out the images. 

```
SELECT DISTINCT ?url WHERE {
  ?work wdt:P1433 wd:Q4807825;
    wdt:P1476 ?title;
    wdt:P50 ?author.
  ?author wdt:P2038 ?researchgate.
  BIND(CONCAT("https://www.researchgate.net/profile/", ?researchgate) AS ?url)
}
```

Queries for workers on Australian taxa (based on AFD)

```
SELECT DISTINCT ?author ?name ?rg ?url WHERE
{ 
  ?work wdt:P6982 ?afd .
  {
    ?work wdt:P50 ?author .
    ?author rdfs:label ?name .
    FILTER (lang(?name) = 'en') .
    
    # Filter on those with last name begining with letter
    FILTER (regex(str(?name), "\\s+T\\w+(-\\w+)?$")) .
    
    ?author wdt:P2038 ?rg .
    BIND(CONCAT("https://www.researchgate.net/profile/", ?rg) AS ?url)
  }
}
ORDER BY ?name

```