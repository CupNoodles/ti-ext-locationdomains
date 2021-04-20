## Per-location Domains

This extensions extends igniter.local in order to allow for different Tasty Igniter locations to utilize different domains names on the same installation. 

### Installation

Clone the files from this repository into `<your_tastyigniter_path>/extensions/cupnoodles/locationdomains`. 

In order for the links on the `/locations` list (locallist) to be updated, you'll need to override the component partial in `igniter/local/components/locallist/list.blade/php` by copying `example.list.blade.php` (included in this repo) into `themes/<your_theme>/_partials/locallist/list.blade.php`. Further edits to your template files are up to you!

### Configuration

This extension will not work out of the box without editing your server configuration files in order to accept traffic going to alternate domains. 

#### Nginx 

In your server block, you should have a line that looks like 

```
server_name _ <your_domain>; 
````

Expand this line with further domains or subdomains, like

```
server_name _ <your_domain1> <subdomain.your_domain1> <your_domain2>; 
````

Furthermore, assets won't load until you allow CORS from your various domains. Add CORS for subdomains with something like 

```
map $http_origin $allow_origin {
    ~^https?://(.*\.)?my-domain.com(:\d+)?$ $http_origin;
    ~^https?://(.*\.)?localhost(:\d+)?$ $http_origin;
    default "";
}

server {
    listen 80 default_server;
    server_name _;
    add_header 'Access-Control-Allow-Origin' $allow_origin;
    # ...
}

```

Don't forget to update your SSL certificates to validate other domain names as well!
