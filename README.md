egoShoes
========

Here is my implementation of the technical task.

For this task I have chosen to use Symfony as a PHP framework. 
To run the application you need to run the command:

bin/console read:file 

with a parameter in my case "stock.csv", which triggers an event in the controller that reads the data from the file and creates entites, which will be imported into the database once reading is finished.
