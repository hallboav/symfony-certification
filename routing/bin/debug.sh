// Lista todas as rotas
php bin/console debug:router

// Specific information on a single route
php bin/console debug:router article_show

// Test whether a URL matches some route
php bin/console router:match /blog/my-latest-post
