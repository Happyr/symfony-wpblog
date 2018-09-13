# Happyr WordPress Bundle

Let your editors write blog posts with Wordpress excellent backend but still deliver
super quick responses, leverage Twig and integrate with your blog content with
your Symfony application. As an extra bonus, this means that your WordPress application
does not need to be exposed to the internet. 

This is a small bundle that talks to the WordPress REST API. We make sure to cache
each request so your blog do not get overwhelmed with requests. 

## WordPress configuration

We need to rewrite absolute URLs from WordPress. To make things easier for us, please
set your page and post url prefix to "page" and "post" respectively. 

