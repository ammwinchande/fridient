# Fridient

## Tasks for Completion of Fridient API

1. One endpoint to create an ingredient\
    To create an ingredient you must supply `name`, `measure`, `supplier`.\
    The API responds with an object of the ingredient\
    The measure is a unit of measure such as `g, kg, pieces`. Depending on whether it is 200grams of sugar or perhaps 8 potatoes.

2. One endpoint to list ingredients\
    This should list all ingredients and should be paginated.

3. One endpoint to create a recipe\
    To create a recipe you must supply `name`, `description` and `an array of ingredients` and the `amount` of the ingredient required\
    The `description` is a free text field to describe the recipe steps.\
    The ingredients array includes an `amount` of each `ingredient` required and maps back to the `measure` required for that ingredient from the ingredients endpoint.
4. One endpoint to list recipes\
    This should list all recipes and should be paginated.
5. One endpoint to create a box for a user\
    A box is an order from a customer which includes up to 4 recipes\
    To create a box you must supply a `delivery_date` which is any date in the future along with an `array of recipe IDs (up to 4)` that the user would like to receive.\
    The endpoint should return `failures for invalid recipe IDs` or `delivery_dates` in the past or within the next 48 hours.\
    The API should respond with an object of the box.
6. One endpoint to view the ingredients required to be ordered by the company\
    Upon supplying an `order_date` the API must response with the ingredients and amount of each ingredient which should be ordered to fulfill all orders for the 7 days from the `order_date`.

## BONUS

Having unit/integration tests is a strong bonus.\
Add seeders\
Add filters for ingredient supplier, box delivery_date

## NOTE

The request input should be validated before processing. The server should return proper error responses in case validation fails.

A database must be used (at HelloChef we typically use MySQL). Database installation and initialization must be done in start.sh

All responses must be in json format for success and failure responses.

Relations between models can be made however they make sense. If extra tables are needed please make them.

Tables should have migrations
