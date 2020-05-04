# Fridient

A fresh Ingridients API

## Usage

To get started, make sure you have [Docker installed](https://docs.docker.com/docker-for-mac/install/) on your system, and then clone this repository.

Then, open a terminal (GitBash for Windows) and from this cloned respository's root, run\
\
    - `docker-compose up -d --build`.

Open up your browser of choice to [http://localhost:8080](http://localhost:8080) and you should see your Laravel app running as intended.\
\
    - `docker-compose run --rm composer update`\
\
    - `docker-compose run --rm artisan migrate --seed`

## View End points

The following are the application end-points

1. One endpoint to create an ingredient\
    To create an ingredient you must supply `name`, `measure`, `supplier`.\
    The API responds with an object of the ingredient\
    The measure is a unit of measure such as `g, kg, pieces`. Depending on whether it is 200grams of sugar or perhaps 8 potatoes.

    Suppliers and Ingredients, can be created as follows
    - Send POST request to URL below\
    [http://localhost:8080/api/v1/suppliers](http://localhost:8080/api/v1/suppliers)\
    Body of the request data sample:\
    `{`
	    `"code" : "M1607FD",`
	    `"name" : "MuJr Suppliers Ltd"`
   `}`

    - Send POST request to URL below\
    [http://localhost:8080/api/v1/ingredients](http://localhost:8080/api/v1/ingredients)\
    Body of the request data sample:\
    `{`
	    `"supplier_id" : "1",`
	    `"name" : "potatoes",`
	    `"measure" : "pieces"`
   `}`

2. One endpoint to list ingredients\
    This should list all ingredients and should be paginated.

    - Send GET request to URL below\
    [http://localhost:8080/api/v1/ingredients](http://localhost:8080/api/v1/ingredients)

    - Send GET request to URL below, to filter ingredients by supplier
    [http://localhost:8080/api/v1/ingredients/?supplier_code=code](http://localhost:8080/api/v1/ingredients/?supplier_code=code)

    - **(List of all Suppliers)** Send GET request to URL below\
    [http://localhost:8080/api/v1/ingredients](http://localhost:8080/api/v1/ingredients)

3. One endpoint to create a recipe\
    To create a recipe you must supply `name`, `description` and `an array of ingredients` and the `amount` of the ingredient required\
    The `description` is a free text field to describe the recipe steps.\
    The ingredients array includes an `amount` of each `ingredient` required and maps back to the `measure` required for that ingredient from the ingredients endpoint.

    - Send POST request to URL below\
    [http://localhost:8080/api/v1/recipes](http://localhost:8080/api/v1/recipes)\
    Body of the request data sample:\
    `{`
	    `"name" : "potato salad",`
	    `"descrption" : "lorem amet ipsum musec it just sample desc pction",`
	    `"ingredients" : ["{'ingredient_id':8,'amount':6}","{'ingredient_id':1,'amount':4}","{'ingredient_id':6,'amount':8}"]`
   `}`

4. One endpoint to list recipes\
    This should list all recipes and should be paginated\

    - Send GET request to URL below\
    [http://localhost:8080/api/v1/recipes](http://localhost:8080/api/v1/recipes)
    Body of the request data sample:\
    `{`
	    `"supplier_id" : "1",`
	    `"name" : "potatoes",`
	    `"measure" : "pieces"`
   `}`

5. One endpoint to create a box for a user\
    A box is an order from a customer which includes up to 4 recipes\
    To create a box you must supply a `delivery_date` which is any date in the future along with an `array of recipe IDs (up to 4)` that the user would like to receive.\
    The endpoint should return `failures for invalid recipe IDs` or `delivery_dates` in the past or within the next 48 hours.\
    The API should respond with an object of the box.\

    - Send POST request to URL below - to create a Box\
    [http://localhost:8080/api/v1/boxes](http://localhost:8080/api/v1/boxes)\
    `{`
	    `"delivery_date" : "2020-05-07",`
	    `"recipe_ids" : [1, 2, 3, 4, 7]`
   `}`

    - Send GET request to URL below - get all Boxes\
    [http://localhost:8080/api/v1/boxes](http://localhost:8080/api/v1/boxes)

6. One endpoint to view the ingredients required to be ordered by the company
    - Send GET request to URL below - to create a Box\
    [http://localhost:8080/api/v1/ingredients-orders](http://localhost:8080/api/v1/ingredients-orders)

    Upon supplying an `order_date` the API must response with the ingredients and amount of each ingredient which should be ordered to fulfill all orders for the 7 days from the `order_date`.

    - Send GET request to URL below - to create a Box\
    [http://localhost:8080/api/v1/ingredients-orders/?order_date=**2020-05-02**](http://localhost:8080/api/v1/ingredients-orders/?order_date=**2020-05-02**)

**NOTE:**

Order date should be in format of: `YYYY-mm-dd` e.g. 2020-05-02

For better experience creating and testing use: `POSTMAN`

**To be Improved:**

Create a pivot table that could store `recipe_id`, `ingredient_id`, and `amount` i.e. a table will be link for recipes and ingredients, and then store the array of ids in recipes table - ingredients column for easier data storing and fetching.

- lead to massive code improvement and performance
