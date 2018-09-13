# Happyr WordPress Bundle

Let your editors write blog posts with Wordpress excellent backend but still deliver
super quick responses, leverage Twig and integrate with your blog content with
your Symfony application. As an extra bonus, this means that your WordPress application
does not need to be exposed to the internet. 

This is a small bundle that talks to the WordPress REST API. We make sure to cache
each request so your blog do not get overwhelmed with requests. 

## WordPress configuration

### Rewriting links

We need to rewrite absolute URLs from WordPress. To make things easier for us, please
set your page and post url prefix to "page" and "post" respectively. 

(TODO add image)

### Invalidate cache

You should configure Symfony to be very aggressive when caching resources from
WordPress. But when an editor makes an update you need to invalidate the cache
and redownload the updated resource. 

The Symfony bundle provides an endpoint to invalidate cache. You should use this
endpoint when a post in updated and deleted. 

(TODO add a small wordpress plugin for this in `Resources/Wordpress`) 

## Symfony installation

There are quite a few moving parts to set up this bundle. But they all make perfect 
sense. Lets take them one by one: 

### API endpoint

Where is your WordPress blog? You should define the endpoint to the build-in REST 
API. In the example below we assume you access your WordPress app with the following 
URL: `http://demo.wp-api.org`. 

```yaml
# /config/packages/happyr_wordpress.yaml
wordpress:
  url: 'http://demo.wp-api.org/wp-json/wp/v2/'
```

### Templates

The bundle comes with 2 default templates. One for an index page that list your 
latest posts and one template for a single post/page. You should of course replace
these with something you like better. This could easily be done with some configuration:

```yaml
# /config/packages/happyr_wordpress.yaml
wordpress:
  # ...
  controller:
    index_template: index.html.twig
    page_template: page.html.twig
```

### Routes

To enable the default controllers you need to include the provided routes.yaml. 
```yaml
# /config/routes.yaml
wordpress:
    resource: '@WordpressBundle/Resources/config/routes.yaml'
    prefix: '/p' # optional
```

You may of course use your own controllers. Just make sure that you define a route
named `happyr_wordpress_page`.

```yaml
# /config/packages/happyr_wordpress.yaml
wordpress:
    # ...
    controller: false
  
```
```yaml
# /config/routes.yaml
# ...

happyr_wordpress_page:
    path: /wp/{slug}
    methods: 'GET'
    controller: App\Controller\MyWordpressController::show
    requirements:
        slug: '.+'
```

### Caching

WordPress is a great tool but it is slower than your Symfony application. Make 
sure we cache all responses from Wordpress. We use `Symfony\Contracts\Cache\CacheInterface`
for caching because it got stampede protection built-in. 

```yaml
# /config/packages/happyr_wordpress.yaml
wordpress:
  # ...
  cache:
    service: 'App\Cache\SymfonyCache'
    ttl: 604800 # One week
```

### Parsers

When we fetch data from WordPress we need to parse it somehow. We need to make sure
all links refer to the symfony application and not the WordPress application. We
also need to handle the image references. 

You may disable parses you do not want with configuration: 

```yaml
# /config/packages/happyr_wordpress.yaml
wordpress:
  # ...
  parser:
    image: false
    link: false
    url: false
```

You may also add your own parsers by register a new service and tag it with
`happyr_wordpress.parser.page` or `happyr_wordpress.parser.menu`.

### Images

We do not want any references to images to go to the WordPress application. We 
need to download the image and upload it somewhere good. Like AWS S3. You can 
configure the `RewriteImageReferences` parser with a custom uploader to achieve
this. Make sure your uploader implements `ImageUploaderInterface`.

```yaml
# /config/packages/happyr_wordpress.yaml
wordpress:
  # ...
  parser:
    image: 
      uploader: 'App\MyUploaderService'
```

The default uploader uploads images to a local folder. This is alright if there 
only is a few images and you have CloudFront or any other reverse proxy caches 
in front of your Symfony application. 

 ```yaml
 # /config/packages/happyr_wordpress.yaml
 wordpress:
   # ...
   local_image_uploader:
     local_path: '%kernel.project_dir%/public/uploads'
     public_prefix: '/uploads'
 ```
