## Welcome to commission fee calculation app

### How to run a test
php artisan test

You can find the test case in the Tests/Feature/CommissionTest.php it is hard coded.

### How to run and use diffrent csv files
Add your new csv file in the public/CSV/ folder and run:
php artisan app:commission file_name.csv

## In General

Most of the code is written in the Models. I have used a factory pattern mostly so we have folders for "user" and "transaction" in which we have transaction.php which creates an object for the new transaction and saves some of the information in memory, in transactions.php most of the logic is placed, we have two different transactions "withdraw" and "deposit" witch fee amounts are stored at the top of the code, the deposit is a much simpler transaction witch only calculates the percent but in the "withdraw" we have much complex logic such as it the transaction was in the same week (basically comparing it to the last transaction) testing if the amount is more then fee free and etcetera. The main part of the calculation is the init function where the new user instance is created (if it doesn't exist in the array), after getting the user it makes the transaction object (if it doesn't exist as well), and in the new object we save the amount which is over the free limit if so, we save it in the array of transactions based on the user ID. 

The heart of the calculation is the above_max_amount method which is in the commission file it calculates the correct amount for the fee.
