# merchant-app

There is a small application core in the folder "library".

Application models, configs and view scripts are located in the "app" folder.

The test data and database are placed in "data".

Shell scripts can be executed from "shell".
The setup script is used to process CSV data and store in SQLite the database.
Merchant transactions can be requested with use "requestMerchantTransactions.php MERCHANT_ID". MERCHANT_ID - merchant ID in the database.

The code partially covered with unit tests. They are in the "tests" folder.
