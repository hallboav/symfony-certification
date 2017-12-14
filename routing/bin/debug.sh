# Lista todas as rotas
bin/console debug:router

# Specific information on a single route
bin/console debug:router article_show

# Test whether a URL matches some route
bin/console router:match /blog/my-latest-post
